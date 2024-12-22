<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id','status','total_price'];
    // make relationship
    public function customer()
    {
        $this->belongsTo(Customer::class);
    }
    public function payment()
    {
        $this->hasOne(Payment::class);
    }
}
