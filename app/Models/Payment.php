<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'order_id',
        'amount',
        'status',
        'snap_token',
        'payment_method',
        'payment_data'
    ];

    protected $casts = [
        'payment_data' => 'array',
        'amount' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusTextAttribute()
    {
        $statusTexts = [
            'pending' => 'Menunggu Pembayaran',
            'success' => 'Berhasil',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            'denied' => 'Ditolak',
            'challenge' => 'Ditinjau',
            'refunded' => 'Dikembalikan'
        ];

        return $statusTexts[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending' => 'yellow',
            'success' => 'green',
            'cancelled' => 'red',
            'expired' => 'gray',
            'denied' => 'red',
            'challenge' => 'orange',
            'refunded' => 'blue'
        ];

        return $statusColors[$this->status] ?? 'gray';
    }
}
