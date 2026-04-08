<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Promotion extends Model
{
    use HasFactory;

   protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_value',
        'quantity',
        'used_count',
        'start_date',
        'end_date',
        'status'
    ];

   
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
