<?php

namespace App\Http\Controllers;

use App\Model\WishlistCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class wishlistCategoryController extends Controller
{
    public function storeCategoryWishlist(Request $request)
    {
        // return "yes";
         if($request->name && $request->name != '')
        {
            
            $create = WishlistCategory::create(
                [
                    'name'      => $request->name,
                    'user_id'   => auth('customer')->user()->id
                ]);
                
                
            Toastr::success(translate('Your Wish List Folder Created Successfully'));
            return redirect()->back();
             
        }
    }
    
    
    public function showCustomerFolders()
    {
        $wishlist_Category = WishlistCategory::where('user_id',auth('customer')->id())->with('wishlists')->paginate(10);
       return view(VIEW_FILE_NAMES['show_wishlist_folders'], compact('wishlist_Category'));
    }
    
    
    public function updateCategoryWishlist(Request $request)
    {
       
        $data = WishlistCategory::find($request->id);
        
        if($data && $data->count() > 0)
        {
            $data->update(
                [
                   'name' => $request->name     
                ]);
        }
        
           Toastr::success(translate('Your Wish List Folder Updated Successfully'));
            return redirect()->back();
    }
    
    
     public function deleteCategoryWishlist(Request $request)
    {
    //   return $request->id;
        $data = WishlistCategory::with('wishlists')->find($request->id);
        
    DB::beginTransaction();
    
        if($data->wishlists && $data->wishlists->count() > 0)
        {
            foreach($data->wishlists as $wishlist)
            {
                $wishlist->delete();
            }
            
            $data->delete();
        
        }else
        {
             $data->delete();
        }
         DB::commit();
          DB::rollback();
           Toastr::success(translate('Your Wish List Folder Deleted Successfully'));
            return redirect()->back();
    }
}
