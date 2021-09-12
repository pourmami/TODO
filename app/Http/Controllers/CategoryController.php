<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;

class CategoryController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if ($this->user->is_admin) {
            $categories = Category::all();
            return \response()->json([
                "success" => true,
                "categories" => $categories
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string|unique:categories'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new post
        $post = Category::create([
            'name' => $request->name,
            'slug' => str_replace(' ', '-', $request->name)
        ]);

        //Post created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], Response::HTTP_OK);
    }


    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post not found.'
            ], 400);
        }

        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(Request $request, Category $category)
    {
        //Validate data
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string|unique:categories'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update post
        $post = $post->update([
            'name' => $request->name,
            'slug' => str_replace(' ', '-', $request->name),
        ]);

        //post updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'post updated successfully',
            'data' => $post
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'post deleted successfully'
        ], Response::HTTP_OK);
    }

    public function get_tags($id)
    {
        return Category::findOrFail($id)->tags;
    }
}
