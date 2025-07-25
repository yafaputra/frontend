<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Transaction;
use App\Models\Payment; // Pastikan model Payment sudah ada
use App\Models\CourseDescriptions;
use App\Models\UserProfile;
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

    /**
     * Create Snap Token for payment
     */
    public function createSnapToken(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'course_id' => 'required|exists:course_description,id',
                'user_profile_id' => 'required|exists:users_profile,id',
                'amount' => 'required|numeric|min:1'
            ]);

            $courseId = $request->course_id;
            $userProfileId = $request->user_profile_id;
            $amount = $request->amount;

            // Get course data
            $course = CourseDescriptions::findOrFail($courseId);

            // Get user profile data
            $userProfile = UserProfile::findOrFail($userProfileId);

            // Generate unique order ID
            $orderId = 'ORDER-' . uniqid();

            // === AWAL: SIMPAN TRANSAKSI PENDING KE DATABASE ANDA ===
            // Asumsikan Anda punya model Payment atau bisa langsung pakai DB::table
            DB::table('payments')->insert([
                'order_id' => $orderId,
                'user_profile_id' => $userProfileId,
                'course_id' => $courseId,
                'amount' => $amount,
                'status' => 'pending', // Set initial status as pending
                'transaction_id' => null, // Akan diupdate dari notifikasi Midtrans
                'payment_type' => null, // Akan diupdate dari notifikasi Midtrans
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // === AKHIR: SIMPAN TRANSAKSI PENDING KE DATABASE ANDA ===

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
                'first_name' => $userProfile->fullname ?? 'Customer',
                'email' => $userProfile->email,
                'phone' => $userProfile->phone ?? '08123456789'
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
                'callbacks' => [
                    'finish' => url('/payment/finish')
                ]
            ];

            Log::info('Creating Midtrans transaction', [
                'order_id' => $orderId,
                'amount' => $amount,
                'user_profile_id' => $userProfileId
            ]);

            // Create snap token
            $snapToken = \Midtrans\Snap::getSnapToken($transactionArray);

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

    /**
     * Handle Midtrans notification
     */
    public function handleNotification(Request $request)
    {
        try {
            $notif = new \Midtrans\Notification();

            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;
            $transactionTime = $notif->transaction_time;
            $paymentType = $notif->payment_type;
            $grossAmount = $notif->gross_amount;
            $transactionId = $notif->transaction_id;


            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            // === AWAL: LOGIKA UPDATE STATUS PEMBAYARAN DI DATABASE ANDA ===
            $mappedStatus = $this->mapPaymentStatus($transactionStatus);

            DB::table('payments')
                ->where('order_id', $orderId)
                ->update([
                    'status' => $mappedStatus,
                    'transaction_id' => $transactionId,
                    'payment_type' => $paymentType,
                    'updated_at' => now(),
                    // Anda bisa menambahkan kolom lain seperti transaction_time, dll.
                ]);

            Log::info('Payment status updated in DB', [
                'order_id' => $orderId,
                'new_status' => $mappedStatus
            ]);
            // === AKHIR: LOGIKA UPDATE STATUS PEMBAYARAN DI DATABASE ANDA ===


            // Grant course access if payment is successful
            // Pastikan Anda mendapatkan user_id dan course_id yang benar
            // notif->customer_details->user_id tidak selalu tersedia, bergantung pada parameter yang Anda kirim ke Midtrans.
            // Paling aman adalah mengambilnya dari data order yang sudah Anda simpan di database.
            $payment = DB::table('payments')->where('order_id', $orderId)->first();

            if ($payment && ($transactionStatus == 'capture' || $transactionStatus == 'settlement')) {
                $this->grantCourseAccess(
                    $payment->user_profile_id, // Ambil dari data pembayaran yang disimpan
                    $payment->course_id
                );
            }

            return response()->json(['status' => 'success']);

        } catch (Exception $e) {
            Log::error('Error handling Midtrans notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Check payment status directly from Midtrans
     */
    public function checkPaymentStatus(Request $request, $orderId)
    {
        try {
            // Check status from Midtrans
            $status = \Midtrans\Transaction::status($orderId);

            Log::info('Payment status check', [
                'order_id' => $orderId,
                'status' => $status->transaction_status
            ]);

            $mappedStatus = $this->mapPaymentStatus($status->transaction_status);

            // === AWAL: UPDATE STATUS PEMBAYARAN DI DATABASE ANDA SAAT MANUAL CHECK ===
            DB::table('payments')
                ->where('order_id', $orderId)
                ->update([
                    'status' => $mappedStatus,
                    'transaction_id' => $status->transaction_id,
                    'payment_type' => $status->payment_type,
                    'updated_at' => now(),
                ]);

            Log::info('Payment status updated in DB via manual check', [
                'order_id' => $orderId,
                'new_status' => $mappedStatus
            ]);
            // === AKHIR: UPDATE STATUS PEMBAYARAN DI DATABASE ANDA SAAT MANUAL CHECK ===


            // Grant access if payment is successful
            $payment = DB::table('payments')->where('order_id', $orderId)->first();
            if ($payment && ($status->transaction_status == 'capture' || $status->transaction_status == 'settlement')) {
                $this->grantCourseAccess(
                    $payment->user_profile_id, // Ambil dari data pembayaran yang disimpan
                    $payment->course_id
                );
            }

            return response()->json([
                'order_id' => $orderId,
                'status' => $status->transaction_status,
                'payment_status' => $mappedStatus,
                'amount' => $status->gross_amount,
                'payment_type' => $status->payment_type,
                'transaction_time' => $status->transaction_time
            ]);

        } catch (Exception $e) {
            Log::error('Error checking payment status', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Failed to check payment status',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Grant course access to user
     */
    protected function grantCourseAccess($userId, $courseId)
    {
        try {
            // Check if access already granted
            $existingAccess = DB::table('user_courses')
                               ->where('user_id', $userId)
                               ->where('course_id', $courseId)
                               ->exists();

            if (!$existingAccess) {
                DB::table('user_courses')->insert([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                    'enrolled_at' => now(),
                    'progress' => 0,
                    'completed' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Log::info('Course access granted', [
                    'user_id' => $userId,
                    'course_id' => $courseId
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error granting course access', [
                'user_id' => $userId,
                'course_id' => $courseId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Map Midtrans status to simpler status
     */
    protected function mapPaymentStatus($midtransStatus)
    {
        $statusMap = [
            'capture' => 'completed',
            'settlement' => 'completed',
            'pending' => 'pending',
            'deny' => 'failed',
            'expire' => 'expired',
            'cancel' => 'cancelled'
        ];

        return $statusMap[$midtransStatus] ?? $midtransStatus;
    }

    /**
     * Get payment history for user (optional)
     */
    public function getUserPayments(Request $request)
    {
        try {
            $userProfileId = $request->input('user_profile_id');

            if (!$userProfileId) {
                return response()->json(['error' => 'User profile ID is required'], 400);
            }

            // Ambil data pembayaran dari database lokal Anda
            $payments = DB::table('payments')
                          ->where('user_profile_id', $userProfileId)
                          ->get();

            return response()->json([
                'success' => true,
                'payments' => $payments,
                'message' => 'Payment history fetched successfully.'
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching user payments', [
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to fetch payments'], 500);
        }
    }
}
