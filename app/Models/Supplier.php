<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'address',
        'ward',
        'district',
        'province',
        'email',
        'phone_number',
        'bank_account',
    ];

    protected $guarded = ['id'];

    public function productTitles()
    {
        return $this->hasMany(ProductTitle::class, 'supplier_id');
    }
}