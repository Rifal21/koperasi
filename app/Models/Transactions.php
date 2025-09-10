<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionsFactory> */
    use HasFactory;

    protected $table = 'transactions';
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function finance()
    {
        return $this->hasOne(Finance::class);
    }

    protected static function booted()
    {
        static::creating(function ($transaction) {
            // jika invoice_number kosong, generate otomatis
            if (empty($transaction->invoice_number)) {
                $latest = Transactions::latest()->first();
                $number = $latest ? ((int) str_replace('INV-', '', $latest->invoice_number)) + 1 : 1;
                $transaction->invoice_number = 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
