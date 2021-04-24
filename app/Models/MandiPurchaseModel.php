<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class MandiPurchaseModel extends Model
{
    protected $fillable = ['bag_price','id','customer_id','item_id','weight_of_one_bag','total_bag','total_weight',
        'total_amt','filling_of_bag','loading_of_bag','loading_of_bag','stitching_of_bag','bag_price','price','katt','market_fee_of_bag'];
}
