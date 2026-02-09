<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTitle extends Model
{
    use HasFactory;

    protected $table = 'producttitles';

    protected $fillable = [
        'name',
        'author',
        'description',
        'product_type_id',
        'supplier_id',
    ];

    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_title_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}