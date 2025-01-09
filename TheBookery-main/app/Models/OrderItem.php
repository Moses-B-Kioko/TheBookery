<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
