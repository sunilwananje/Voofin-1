<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
	use SoftDeletes;
    protected $table = "invoices";
    protected $dates = ['deleted_at'];

    public function invoiceItems()
    {
        return $this->hasMany('InvoiceItem', 'invoice_id');
    }

}

