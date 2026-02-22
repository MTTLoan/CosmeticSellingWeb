<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'address',
        'ward',
        'district',
        'province',
        'manager_id',
    ];

    protected $guarded = ['id'];

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_branches', 'branch_id', 'product_id')
                    ->withPivot('quantity');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'branches_discounts', 'branch_id', 'discount_id');
    }
}