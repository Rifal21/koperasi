<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    /** @use HasFactory<\Database\Factories\ItemsFactory> */
    use HasFactory;

    protected $table = 'items';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'code',
        'category',
        'stock',
        'price_buy',
        'price_sell',
        'supplier_id'
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
