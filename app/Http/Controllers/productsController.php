<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\WarehouseDetail;
use App\Models\Category;
use App\Models\Wishlist;
use DB;
use Image;
use Storage;
class productsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "p_name" => "required | string",
            "description" => "required | string",
            "cat_id" => "required | integer",
            "price" => "required",
            "quantities" => "required",
            "p_commission" => "required | integer",
            "p_image" => "required",
            "sku" => "required"
        ]);
        try{
            DB::beginTransaction();
            $product = new Product;
            $product->p_name = $request->p_name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->brand_id = $request->brand_id;
            $product->cat_id = $request->cat_id;
            $product->commission = $request->p_commission;
            $product->p_image = $request->p_image;
            $product->sku = $request->sku;
            $product->supplier = $request->supplier;
            $product->p_status = "PUBLISHED";

            $product->save();

            /////////////update category to a leaf///////
            $category = Category::find($request->cat_id);
            if($category->tree_level == 'NODE'){
                $category->tree_level = 'LEAF';
                $category->save();
            }
            //////////////////////////////////////////////

            foreach($request->quantities as $quantity){
                $detail = new WarehouseDetail;
                $detail->p_id = $product->id;
                $detail->warehouse_id = $quantity['warehouse_id'];
                $detail->quantity = $quantity['quantity'];
                $detail->save();
            }
            DB::commit();
            return $product;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Error!', 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadProductPic(Request $request){
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2084',
        ]);
        //dd($request->cat_id);
        if($request->hasFile('photo')){
            
            //Get filename with the extention
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $thumbnailImage = Image::make($request->file('photo'));
            
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('photo')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload Image
            //$realPath = public_path().'\storage\products\\';
            $realPath = storage_path().'/app/public/products/';
            //$thumbnailPath = public_path().'\storage\productsThumb\\';
            $thumbnailPath = storage_path().'/app/public/productsThumb/';
            $thumbnailImage->save($realPath.$fileNameToStore);

            $thumbnailImage->resize(null, 320, function ($constraint){
                $constraint->aspectRatio();
            });
            $thumbnailImage->save($thumbnailPath.$fileNameToStore);
            
            $data = [];
            $data['fileName'] = $fileNameToStore;

            return $data; 
        }
        else{
            return response(422, "No file");
        }

    }

    public function updateProductPic(Request $request){

    }

    public function getProductsList(){
        $products = Product::all();
        foreach($products as $product){
            $product->stock = WarehouseDetail::where('p_id', $product->id)->sum('quantity');
        }

        return $products;                
    }

    public function deleteProductPic(Request $request){
        $this->validate($request, [
            "fileName" => "required"
        ]);

        if(Storage::exists('public/products/'.$request->fileName)){
            Storage::delete('public/products/'.$request->fileName);
            Storage::delete('public/productsThumb/'.$request->fileName);
            return response('successfully deleted', 200);
        }
        else{
            return response('Image doesnt exist', 422);
        }
    }

    public function saveDraft(Request $request){
        $this->validate($request, [
            "p_name" => "required | string",
        ]);

        $product = new Product;
        $product->p_name = $request->p_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->brand_id = $request->brand_id;
        $product->cat_id = $request->cat_id;
        $product->commission = $request->p_commission;
        $product->p_image = $request->p_image;
        $product->p_status = "DRAFT";
        $product->save();
        
        return $product;
    }

    public function productsByCategory($id){
        $data = [];
        $category = Category::find($id);
        if($category->tree_level == 'NODE'){
            $children = Category::where('parent_id', $id)->get();
            foreach($children as $child){
                $data = array_merge($data, $this->getChildrenProducts($child->id));
            }
        }
        else{
            $products = Product::where('cat_id', $id)
                                        ->where('p_status', 'PUBLISHED')
                                        ->get();
            foreach($products as $product){
                $wish_item = Wishlist::where('p_id', $product->id)
                                     ->where('user_id', auth()->user()->id)
                                     ->first();
                if($wish_item){
                    $product->wishlist = true;
                }
                else{
                    $product->wishlist = false;
                }
            }
            $data = array_merge($products->toArray(),$data);
        }
        return $data;
    }

    public function getChildrenProducts($id){
        $data = [];
        $category = $category = Category::find($id);
        if($category->tree_level == 'NODE'){
            $children = Category::where('parent_id', $id)->get();
            foreach($children as $child){
                array_merge($data, $this->getChildrenProducts($child->id));
            }
        }
        else{
            $products = Product::where('cat_id', $id)
                                        ->where('p_status', 'PUBLISHED')
                                        ->get();
            foreach($products as $product){
                $wish_item = Wishlist::where('p_id', $product->id)
                                        ->where('user_id', auth()->user()->id)
                                        ->first();
                if($wish_item){
                    $product->wishlist = true;
                }
                else{
                    $product->wishlist = false;
                }
            }
            $data = array_merge($products->toArray(),$data);
        }
        return $data;
    }

    public function searchItems(Request $request){
        $this->validate($request, [
            "searchQuery" => "required"
        ]);
        $data = [];
        ///////categories/////////////
        $category = Category::where('cat_name', 'LIKE', '%'.$request->searchQuery.'%')->get();
        foreach($category as $cat){
            $products = Product::where('cat_id', $cat->id)->get();
            foreach($products as $product){
                $wish_item = Wishlist::where('p_id', $product->id)
                                     ->where('user_id', auth()->user()->id)
                                     ->first();
                if($wish_item){
                    $product->wishlist = true;
                }
                else{
                    $product->wishlist = false;
                }
            }
            if(count($products)>0){
                $products = $products->toArray();
                $data = array_merge($data,$products);
            }
            
        }

        //////products//////////////////
        $products = Product::where('p_name', 'LIKE', '%'.$request->searchQuery.'%')->get();
        foreach($products as $product){
            $wish_item = Wishlist::where('p_id', $product->id)
                                 ->where('user_id', auth()->user()->id)
                                 ->first();
            if($wish_item){
                $product->wishlist = true;
            }
            else{
                $product->wishlist = false;
            }
        }
        $products = $products->toArray();
        $data = array_merge($data, $products);

        return $data;
    }
    
    public function toggleFeature(Request $request, $id){
        $product = Product::find($id);
        if($product->featured == 'FEATURED'){
            $product->featured = 'NOT_FEATURED';
            $product->save();
        }
        else{
            $product->featured = 'FEATURED';
            $product->save();
        }
        return $product;
    }

    public function getFeatured(Request $request){
        $products = Product::where('featured', 'FEATURED')->get();
        return $products;
    }
}
