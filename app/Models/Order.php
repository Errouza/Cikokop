<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'nama_pelanggan',
        'telepon_pelanggan',
        'tipe_pengambilan',
        'payment_method',
        'total_harga',
        'status',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'total_harga' => 'float',
    ];
}
