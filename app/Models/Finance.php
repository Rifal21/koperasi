<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    /** @use HasFactory<\Database\Factories\FinanceFactory> */
    use HasFactory;

    protected $table = 'finances';
    protected $guarded = ['id'];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class);
    }
}
