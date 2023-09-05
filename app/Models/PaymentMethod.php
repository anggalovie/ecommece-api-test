<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_method';
    protected $fillable = ['name', 'is_active'];
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
