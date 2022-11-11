<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Image;
class categoriesController extends Controller
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
            "cat_name" => "required | string",
            "cat_type" => "required | string",
            "parent_id" => "required | integer"
        ]);

        $category = new Category;
        $category->cat_name = $request->cat_name;
        $category->cat_type = $request->cat_type;
        if($request->cat_type == "PARENT")
        {
            $category->parent_id = 0;
        }
        else
        {
            $category->parent_id = $request->parent_id;
            $category->cat_image = 'placeholder.jpg';
        }
        $category->save();

        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::find($id);
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
            "cat_name" => "required | string",
            "cat_type" => "required | string",
            "parent_id" => "required | integer"
        ]);

        $category = Category::find($id);
        $category->cat_name = $request->cat_name;
        $category->cat_type = $request->cat_type;
        if($request->cat_type == "PARENT")
        {
            $category->parent_id = 0;
        }
        else
        {
            $category->parent_id = $request->parent_id;
        }
        $category->save();

        return $category;
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

    public function getMainCategories(){

        $categories = Category::where('cat_type', 'PARENT')->get();
        foreach($categories as $cat){
            $cat->items = $this->categoryItemCount($cat->id);
        }
        return $categories;
    }

    public function getSubCategories(){

        $categories = Category::where('cat_type', 'CHILD')->get();
        
        foreach($categories as $cat){
            $count = Product::where('cat_id', $cat->id)->count();
            $cat->items = $count + $this->categoryItemCount($cat->id);
            $cat->parent_name = Category::where('id', $cat->parent_id)->select('cat_name')->first()->cat_name;
        }
        return $categories;
    }
    public function categoryItemCount($id){
        $count = 0;
        $category = Category::where('parent_id', $id)->get();
        if(count($category)>0){
            foreach($category as $cat){
                $product = Product::where('cat_id', $cat->id)->get();
                if(count($product) > 0){
                    $count = $count + Product::where('cat_id', $cat->id)->count();
                }
                $count = $count + $this->categoryItemCount($cat->id); 
            }
        }
        return $count;
    }
    public function chooseSubCategories(){

        $data = [];
        $index = 0;
        $parents = Category::where('cat_type', 'PARENT')->get();
        
        foreach($parents as $parent){
            $data[$index]['id'] = $parent->id;
            $data[$index]['label'] = $parent->cat_name;
            $data[$index]['children'] = $this->getChildren($parent);
            $index++;
        }

        return $data;
    }

    public function getNodeCategories($id){
        $ids = [];
        //dd($this->getChildren(Category::find($id)));
        $ids = $this->getChildrenIds(Category::find($id),$ids);
        $categories = Category::where('tree_level', 'NODE')
                              ->whereNotIn('id', $ids)->get();
        return $categories;
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

    public function uploadSubPic(Request $request){
        $this->validate($request, [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2084',
            'cat_id' => 'required'
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
                        
            $thumbnailPath = storage_path().'/app/public/settings/';
            //$thumbnailPath = public_path().'\storage\settings\\';
            /*$thumbnailImage->resize(null, 320, function ($constraint){
                $constraint->aspectRatio();
            });*/
            $thumbnailImage->save($thumbnailPath.$fileNameToStore);

            $subCat = Category::find($request->cat_id);
            $subCat->cat_image = $fileNameToStore;
            $subCat->save();

            return $subCat; 
        }
        else{
            return response(422, "No file");
        }

    }
    
    public function getChildren($parent){
        
        $data = [];
        $index = 0;
        $children = Category::where('parent_id', $parent->id)->get();
        if(count($children) > 0){
            foreach($children as $child){
                $data[$index]['id'] = $child->id;
                $data[$index]['label'] = $child->cat_name;
                $data[$index]['children'] = $this->getChildren($child);
                $index++;
            }
            return $data;
        }
        else{
            return "";
        }
    }

    public function getImediateSubCat($id){
        $categories = Category::where('parent_id',$id)
                              ->select('id', 'cat_name', 'cat_image')
                              ->get();
        return $categories;
    }

    public function makeChild(Request $request){
        $this->validate($request, [
            "cat_id" => "required",
            "parent_id" => "required"
        ]);

        $category = Category::find($request->cat_id);
        $category->cat_type = "CHILD";
        $category->parent_id = $request->parent_id;
        $category->cat_image = 'placeholder.jpg';
        $category->save();

        return $category;
    }
}
