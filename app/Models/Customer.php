<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['customer_name'];

    public function customer_address(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'id');
    }
}
