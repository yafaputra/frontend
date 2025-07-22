<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\Course;
use Exception;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function createSnapToken(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'course_id' => 'required|exists:course_description,id',
                'amount' => 'required|numeric|min:1'
            ]);

            $user = $request->user();
            $courseId = $request->course_id;
            $amount = $request->amount;

            // Get course data
            $course = DB::table('course_description')->where('id', $courseId)->first();

            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            // Check if user already purchased this course
            $existingPayment = Payment::where('user_id', $user->id)
                                    ->where('course_id', $courseId)
                                    ->where('status', 'success')
                                    ->first();

            if ($existingPayment) {
                return response()->json(['error' => 'You have already purchased this course'], 400);
            }

            // Generate unique order ID
            $orderId = 'ORDER-' . $user->id . '-' . $courseId . '-' . time();

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $courseId,
                'order_id' => $orderId,
                'amount' => $amount,
                'status' => 'pending'
            ]);

            // Prepare transaction details for Midtrans
            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int) $amount
            ];

            // Prepare item details
            $itemDetails = [
                [
                    'id' => $courseId,
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => $course->title ?? 'Course',
                    'category' => 'Education'
                ]
            ];

            // Prepare customer details
            $customerDetails = [
                'first_name' => $user->name ?? 'Customer',
                'email' => $user->email,
                'phone' => $user->phone ?? '08123456789'
            ];

            // Prepare transaction array
            $transactionArray = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => [
                    'gopay', 'bank_transfer', 'credit_card', 'cstore', 'bca_va',
                    'bni_va', 'bri_va', 'other_va', 'qris'
                ],
                'vtweb' => [],
                'callbacks' => [
                    'finish' => url('/payment/finish')
                ]
            ];

            Log::info('Creating Midtrans transaction', [
                'order_id' => $orderId,
                'amount' => $amount,
                'user_id' => $user->id
            ]);

            // Create snap token
            $snapToken = \Midtrans\Snap::getSnapToken($transactionArray);

            // Update payment record with snap token
            $payment->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'message' => 'Snap token created successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating snap token', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create payment token: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $paymentType = $notification->payment_type;

            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType
            ]);

            // Find payment record
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                Log::error('Payment not found for order_id: ' . $orderId);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $payment->status = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    $payment->status = 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                $payment->status = 'success';
            } else if ($transactionStatus == 'pending') {
                $payment->status = 'pending';
            } else if ($transactionStatus == 'deny') {
                $payment->status = 'denied';
            } else if ($transactionStatus == 'expire') {
                $payment->status = 'expired';
            } else if ($transactionStatus == 'cancel') {
                $payment->status = 'cancelled';
            } else if ($transactionStatus == 'refund') {
                $payment->status = 'refunded';
            }

            // Update payment data
            $payment->payment_method = $paymentType;
            $payment->payment_data = json_encode($notification->getResponse());
            $payment->save();

            // If payment is successful, you might want to grant access to the course
            if ($payment->status === 'success') {
                // Add logic here to grant course access to user
                // For example, create a record in user_courses table
                $this->grantCourseAccess($payment->user_id, $payment->course_id);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error handling Midtrans notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json(['error' => 'Failed to process notification'], 500);
        }
    }

    public function checkPaymentStatus(Request $request, $orderId)
    {
        try {
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Optional: Check status from Midtrans API
            try {
                $status = \Midtrans\Transaction::status($orderId);

                // Update local status based on Midtrans response
                if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                    $payment->status = 'success';
                    $payment->save();

                    // Grant course access if not already granted
                    if ($payment->status === 'success') {
                        $this->grantCourseAccess($payment->user_id, $payment->course_id);
                    }
                }

            } catch (\Exception $e) {
                Log::warning('Could not check status from Midtrans API', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'payment' => $payment,
                'status' => $payment->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to check payment status'], 500);
        }
    }

    protected function grantCourseAccess($userId, $courseId)
    {
        try {
            // Check if access already granted
            $existingAccess = DB::table('user_courses')
                               ->where('user_id', $userId)
                               ->where('course_id', $courseId)
                               ->first();

            if (!$existingAccess) {
                DB::table('user_courses')->insert([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                    'enrolled_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Log::info('Course access granted', [
                    'user_id' => $userId,
                    'course_id' => $courseId
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error granting course access', [
                'user_id' => $userId,
                'course_id' => $courseId,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getUserPayments(Request $request)
    {
        try {
            $user = $request->user();

            $payments = Payment::where('user_id', $user->id)
                              ->with('course')
                              ->orderBy('created_at', 'desc')
                              ->get();

            return response()->json([
                'success' => true,
                'payments' => $payments
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching user payments', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to fetch payments'], 500);
        }
    }
}
