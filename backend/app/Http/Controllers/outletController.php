<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\outlet;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class outletController extends Controller
{
    private $outlet;
    private $category;

    public function __construct()
    {
        $this->outlet = new outlet();
        $this->category = new category();
    }

    public function index(){
        $role = Auth::user()->role;
        $outlets = $this->outlet->select('name', 'alamat')->get();
        if($role != "admin" && $role != "owner"){
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        return response()->json($outlets);
    }

    // public function getCategory(Request $request){
    //     $category = Cache::remember('product_category',600,
    //     function(){
    //         return $this->category::select('id','name')->get();
    //     });
            
    //     return response()->json($category);
    // }


    public function getCategory(Request $request)
{
    $start = microtime(true);

    $category = $this->category
        ->select('id', 'name')
        ->get();

    logger()->info('CATEGORY QUERY TIME', [
        'seconds' => microtime(true) - $start,
    ]);

    return response()->json($category);
}

    
    public function showProductByOutlet(Request $request, $outlet_id = null)
    {
        $user = $request->user();

        $outlet_id = $user->outlet_id;

        $product = products::with([
            'productDetails' => function ($query) use ($outlet_id) {
                $query->where('outlet_id', $outlet_id)
                    ->with('varians');
            }
        ])
        ->select('id', 'category_id', 'name', 'image')
        ->get();

        return response()->json($product);
    }

    
}
