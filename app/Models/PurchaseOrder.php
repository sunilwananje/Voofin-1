<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class PurchaseOrder extends Model
{
    protected $table = "purchase_orders";
    protected $dates = ['deleted_at'];


    public function poItems()
    {
        return $this->hasMany('PurchaseOrderItem', 'purchase_order_id');
    }
}
