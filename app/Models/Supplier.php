<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;
    protected $table = 'suppliers';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'pic',
        'address',
        'phone',
        'email',
    ];

    public function items()
    {
        return $this->hasMany(Items::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transactions::class);
    }
}
