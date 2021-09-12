<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;

class PostController extends Controller
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
            $posts = Post::all();
            return \response()->json([
                "success" => true,
                "posts" => $posts
            ]);
        } else {
            $posts = $this->user->posts()->get();
            return \response()->json([
                "success" => true,
                "posts" => $posts
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
        $data = $request->only('title', 'category_id', 'description');
        $validator = Validator::make($data, [
            'title' => 'required|string|unique:posts',
            'category_id' => 'required',
            'description' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new post
        $post = $this->user->posts()->create([
            'user_id' => $this->user->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => str_replace(' ', '-', $request->title),
            'description' => $request->description
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
        $post = $this->user->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post not found.'
            ], 400);
        }

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function comments($id)
    {
        $post = $this->user->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post not found.'
            ], 400);
        }

        return $post->comments;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(Request $request, Post $post)
    {
        //Validate data
        $data = $request->only('title', 'category', 'description');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'category' => 'required',
            'description' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update post
        $post = $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => str_replace(' ', '-', $request->title),
            'description' => $request->description
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
    public function destroy(Post $post)
    {
        if ($post->user_id == $this->user->id) {
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'post deleted successfully'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Access denied!'
            ], 403);
        }
    }

    public function get_tags($id)
    {
        return Post::findOrFail($id)->tags;
    }
}
