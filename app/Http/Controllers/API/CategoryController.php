<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        if($categories->count() > 0) {
            return response()->json([
                'message' => 'All Categories',
                'status' => 'Success',
                'data' => $categories
            ], 201);
        }else {
            return response()->json([
                'message' => 'No Data',
                'status' => 'Success',
                // 'data' =>'no dada'
            ], 200);
        }



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // Validate Data
         $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'image' => 'required',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Upload File
        $img_name = rand() . time() . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('uploads/categories'), $img_name);

        // convert name to json
        $name = json_encode([
            'en' => $request->name_en,
            'ar' => $request->name_ar,
        ], JSON_UNESCAPED_UNICODE);

        // Insert To Database
      $category =  Category::create([
            'name' => $name,
            'image' => $img_name,
            'parent_id' => $request->parent_id
        ]);

        // return $category;

        if($category) {
            return response()->json([
                'message' => 'Product Created Successfuly',
                'status' => 'Success',
                'data'=> $category

            ], 201);
        }else {
            return response()->json([
                'message' => 'Something Erorr',
                'status' => 'error',
            ], 200);
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
        $category = Category::find($id);
        if($category) {
            return response()->json([
                'message' => 'Found Data',
                'status' => 'Success',
                'data' => $category
            ], 200);
        }else {
            return response()->json([
                'message' => 'No Found Data',
                'status' => 'Success',
                'data' => []
            ], 404);
        }
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
        $category = Category::findOrFail($id);
        $data = $request->all();
        // Uploads the files
        $img_name = $category->image;
        if($request->hasFile('image')) {
            $img_name = rand().time().$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/categories'), $img_name);
            $data['image'] = $img_name;
        }

        if($request->has('name_en')) {
            $name = json_encode([
                'en' => $request->name_en,
                'ar' => $category->name_ar,
            ], JSON_UNESCAPED_UNICODE);
        }

        if($request->has('name_ar')) {
            $name = json_encode([
                'en' => $category->name_en,
                'ar' => $request->name_ar,
            ], JSON_UNESCAPED_UNICODE);
        }

        if($request->has('name_en') || $request->has('name_ar')) {
            $data['name'] = $name;
            unset($data['name_en']);
            unset($data['name_ar']);
        }

        $final = $category->update($data);
        if($final) {
            return response()->json([
                'message' => 'Product '.$id .' Updated Successfuly',
                'status' => 'Success',
                'data'=> $data

            ], 201);
        }else {
            return response()->json([
                'message' => 'Something Erorr',
                'status' => 'error',
            ], 200);
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
        $category= Category::destroy($id);

        if($category) {
            return response()->json([
                'message' => 'Category '.$id .' Deleted Successfuly',
                'status' => 'Success',

            ], 201);
        }else {
            return response()->json([
                'message' => 'Something Erorr',
                'status' => 'error',
            ], 200);
        }
    }
}
