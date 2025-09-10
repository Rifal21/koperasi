<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionItemFactory> */
    use HasFactory;

     protected $fillable = [
        'transaction_id', 'item_id', 'quantity', 'price', 'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }

    public function item()
    {
        return $this->belongsTo(Items::class);
    }
}
