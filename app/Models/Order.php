<?php

namespace App\Models;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'payment',
        'shipping',
        'status',
        'pickup_at',
        'total_amount',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function lists()
    {
        return $this->hasMany(OrderList::class, 'order_id','id');
    }
}
