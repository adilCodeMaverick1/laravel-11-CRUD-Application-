<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'vendor_id',
        'user_id',
        'tracker',
        'amount',
        'status',
        'payment_method',
        'payment_reference',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->id)) {
                $order->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

}
