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
        
        // Tentukan outlet_id berdasarkan role
        // if ($user->role === 'kasir') {
        //     $outlet_id = $user->outlet_id;
        // }

        // if (!$outlet_id) {
        //     return response()->json([
        //         'message' => 'Outlet is not available',
        //     ], 400);
        // }

        // // Bedakan cache key jika struktur kolom yang diambil berbeda
        // $cacheKey = "product_outlet_{$outlet_id}_role_{$user->role}";

        // $product = Cache::remember($cacheKey, 600, function () use ($outlet_id, $user) {
        //     $selectFields = ($user->role === 'kasir') 
        //         ? ['id', 'name'] 
        //         : ['id', 'name', 'address'];

        //     return $this->outlet->select($selectFields)
        //         ->with(['products' => function ($query) {
            //             // Pilih kolom spesifik & bisa tambahkan filter/limit jika perlu
            //             $query->select('id', 'outlet_id', 'name', 'price', 'stock', 'image');
        //         }])
        //         ->get(); // Gunakan find() karena query berdasarkan ID unik (mengembalikan 1 object, bukan collection)
        // });

        // if (!$product) {
        //     return response()->json([
        //         'message' => 'Outlet not found',
        //     ], 404);
        // }
        $user = $request->user();
        $outlet_id = $user->outlet_id;
        //$product = Cache::remember('product_outlet_'.$outlet_id,now()->addMinutes(5),function() use ($outlet_id){ -->
            $product = products::where('outlet_id', $outlet_id)
            ->select('id', 'outlet_id','category_id', 'name', 'price', 'stock', 'image')
            ->get()
            ->toArray();
            //return $product;
        //});
        return response()->json($product);
    }

    
}
