<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model\Wishlist;
use App\User;

class WishlistCategory extends Model
{
    use HasFactory;

    protected $table = 'wishlist_categories';
    protected $guarded = [];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class,'wishlist_category_id')->with('wishlistProduct');
    }
    
    
    public function user()
    {
        return $this->hasOne(User::class,'user_id');
    }
}
