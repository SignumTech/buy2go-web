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
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

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
            "sku" => "required",
            "taxable" => "required",
            "unit" => "required",
            "expiry_date" => "required",
            "minimum_order" => "required"
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
            $product->taxable = $request->taxable;
            $product->unit = $request->unit;
            $product->minimum_order = $request->minimum_order;
            $product->expiry_date = $request->expiry_date;
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
        return Product::find($id);
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
        $this->validate($request, [
            "p_name" => "required | string",
            "description" => "required | string",
            "cat_id" => "required | integer",
            "price" => "required",
            "quantities" => "required",
            "p_commission" => "required | integer",
            "p_image" => "required",
            "sku" => "required",
            "taxable" => "required",
            "unit" => "required",
            "expiry_date" => "required",
            "minimum_order" => "required"
        ]);
        try{
            DB::beginTransaction();
            $product = Product::find($id);
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
            $product->taxable = $request->taxable;
            $product->unit = $request->unit;
            $product->minimum_order = $request->minimum_order;
            $product->expiry_date = $request->expiry_date;
            $product->save();

            /////////////update category to a leaf///////
            $category = Category::find($request->cat_id);
            if($category->tree_level == 'NODE'){
                $category->tree_level = 'LEAF';
                $category->save();
            }
            //////////////////////////////////////////////
            $check = WarehouseDetail::where('p_id', $product->id)->get();
            foreach($check as $c){
                $c->delete();
            }
            
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return $product;
    }

    public function getProductWarehouses($id){
        $warehouses = WarehouseDetail::join('warehouses', 'warehouse_details.warehouse_id', '=', 'warehouses.id')
                                     ->where('p_id', $id)
                                     ->get();
                                     
        return $warehouses;
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
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2084',
            'p_id' => 'required'
        ]);
        //dd($request->cat_id);
        $product = Product::find($request->p_id);
        if(Storage::exists('public/products/'.$product->p_name)){
            Storage::delete('public/products/'.$product->p_name);
            Storage::delete('public/productsThumb/'.$product->p_name);
        }
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
            $product->p_image = $fileNameToStore;
            $product->save();
            $data['fileName'] = $fileNameToStore;

            return $data; 
        }
        else{
            return response(422, "No file");
        }
    }

    public function getProductsList(){
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        foreach($products as $product){
            $product->stock = WarehouseDetail::where('p_id', $product->id)->sum('quantity');
        }
        
        return $products;                
    }

    public function getPriceRange(){
        $data = [];
        $data['max'] = Product::max('price');
        $data['min'] = Product::min('price');

        return $data;
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

        try{
            DB::beginTransaction();
            if($request->p_id){
                $product = Product::find($request->p_id);
            }
            else{
                $product = new Product;
            }
            $product->p_name = $request->p_name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->brand_id = $request->brand_id;
            $product->cat_id = $request->cat_id;
            $product->commission = $request->p_commission;
            $product->p_image = $request->p_image;
            $product->sku = $request->sku;
            $product->supplier = $request->supplier;
            $product->taxable = $request->taxable;
            $product->p_status = "DRAFT";
            $product->unit = $request->unit;
            $product->minimum_order = $request->minimum_order;
            $product->expiry_date = $request->expiry_date;

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
                                        ->orderBy('created_at', 'DESC')
                                        ->paginate(12);
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
                                        ->orderBy('created_at', 'DESC')
                                        ->paginate(12);
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
        $products = Product::where('featured', 'FEATURED')->orderBy('created_at', 'DESC')->get();
        return $products;
    }

    public function filterProducts(Request $request){
        $products = Product::when($request->p_name !=null, function ($q) use($request){
                                return $q->where('p_name', 'LIKE', '%'.$request->p_name.'%');
                            })
                            ->when($request->featured !=null, function ($q) use($request){
                                return $q->where('featured', $request->featured);
                            })
                            ->when($request->status !=null, function ($q) use($request){
                                return $q->where('p_status', $request->status);
                            })
                            ->when(count($request->range)>0, function ($q) use($request){
                                return $q->whereBetween('price', $request->range);
                            })
                            ->when($request->cat_id !=null, function ($q) use($request){
                                return $q->whereIn('cat_id', $this->getCategoryIds($request->cat_id));
                            })
                            ->orderBy('created_at', 'DESC')
                            ->paginate(10);
        foreach($products as $product){
            $product->stock = WarehouseDetail::where('p_id', $product->id)->sum('quantity');
        }

        return $products;
    }
    public function getCategoryIds($id){
        $ids = [];
        array_push($ids, $id);
        $ids = array_merge($ids, $this->getChildrenIds(Category::find($id),$ids));
        //dd($this->getChildren(Category::find($id)));
        return $ids;
    }

    public function getChildrenIds($parent,$ids){
        $children = Category::where('parent_id', $parent->id)->get();
        if(count($children) > 0){
            foreach($children as $child){
                array_push($ids, $child->id);
                $ids = $this->getChildrenIds($child, $ids);
            }
            return $ids;
        }
        else{
            return $ids;
        }
    }
    public function exportProducts(Request $request){
        
        $products = Product::when($request->p_name !=null, function ($q) use($request){
            return $q->where('p_name', 'LIKE', '%'.$request->p_name.'%');
        })
        ->when($request->featured !=null, function ($q) use($request){
            return $q->where('featured', $request->featured);
        })
        ->when($request->status !=null, function ($q) use($request){
            return $q->where('p_status', $request->status);
        })
        ->when(count($request->range)>0, function ($q) use($request){
            return $q->whereBetween('price', $request->range);
        })
        ->when($request->cat_id !=null, function ($q) use($request){
            return $q->whereIn('price', $this->getCategoryIds($request->cat_id));
        })
        ->orderBy('created_at', 'DESC')
        ->select('id', 'p_name', 'price', 'description', 'commission', 'sku', 'supplier', 'featured', 'minimum_order', 'unit', 'expiry_date', 'created_at', 'updated_at')
        ->get();
        foreach($products as $product){
            $product->stock = WarehouseDetail::where('p_id', $product->id)->sum('quantity');
        }
        return Excel::download(new ProductsExport($products), 'products.xlsx');
    }

    public function getDeletedProducts(){
        $products = Product::onlyTrashed()->orderBy('deleted_at', 'DESC')->paginate(10);        
        return $products;  
    }

    public function restoreProduct($id){
        $product = Product::withTrashed()->find($id);
        $product->restore();

        return $product;
    }
}
