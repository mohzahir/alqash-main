<?php

namespace App\Model;
use App\Model\WishlistCategory;


use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{

    protected $casts = [
        'product_id'  => 'integer',
        'customer_id' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function wishlistProduct()
    {
        return $this->belongsTo(Product::class, 'product_id')->active();
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select(['id','slug']);
    }

    public function product_full_info()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
     public function wishlist_categories()
    {
        return $this->belongsTo(WishlistCategory::class, 'wishlist_category_id');
    }
}
