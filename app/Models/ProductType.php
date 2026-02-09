<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $table = 'producttypes';

    protected $fillable = [
        'name',
        'quantity',
        'category',
    ];

    protected $guarded = ['id'];

    public function productTitles()
    {
        return $this->hasMany(ProductTitle::class, 'product_type_id');
    }
}