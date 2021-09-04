<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
       'card_id', 'amount_refunded', 'captured' ,'order_id','payment_id','username','type','forpayment', 'state', 'city', 'amount', 'commission', 'status'
    ];
}


