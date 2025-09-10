<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $table = 'invoices';
    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }

}
