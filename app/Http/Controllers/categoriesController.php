<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
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

    public function chooseSubCategories(){

        $data = [];
        $index = 0;
        $parents = Category::where('cat_type', 'PARENT')->get();
        
        foreach($parents as $parent){
            $data[$index]['parent'] = $parent->cat_name;
            $data[$index]['children'] = $this->getChildren($parent);
            $index++;
        }

        return $data;
    }

    public function getChildren($parent){
        
        $data = [];
        $index = 0;
        $children = Category::where('parent_id', $parent->id)->get();
        if(count($children) > 0){
            foreach($children as $child){
                $data[$index]['parent'] = $child->cat_name;
                $data[$index]['children'] = $this->getChildren($child);
                $index++;
            }
            return $data;
        }
        else{
            return $data;
        }
    }
}
