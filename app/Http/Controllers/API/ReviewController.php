<?php

namespace App\Http\Controllers\API;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $review = Review::all();
        if($review) {
            return response()->json([
                'message' => 'All Reviews',
                'status' => 'Success',
                'data'=> $review

            ], 201);
        }else {
            return response()->json([
                'message' => 'Something Erorr',
                'status' => 'error',
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
        $review = Review::create([
            'comment' => $request->comment,
            'star' => $request->rating,
            'product_id' => $request->product_id,
            'user_id' => Auth::id()
        ]);
        //  return $review;

        if($review) {
            return response()->json([
                'message' => 'Add Review Successfuly',
                'status' => 'Success',
                'data'=> $review

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
        $review = Review::find($id);

        if($review){
            return response()->json([
                'message' => 'Add Review Successfuly',
                'status' => 'Success',
                'data'=> $review
            ],201);

        }else{

            return response()->json([
                'message' => 'Not Found',
                'status' => 'error',
            ], 200);

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
}
