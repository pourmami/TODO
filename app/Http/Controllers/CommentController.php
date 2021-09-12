<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;

class CommentController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->user
            ->comments()
            ->get();
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
        $data = $request->only('post_id', 'text');
        $validator = Validator::make($data, [
            'post_id' => 'required',
            'text' => 'required|min:5'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new Comment
        if ($request->has('parent_id') && Comment::has('parent_id')) {
            $comment = $this->user->comments()->create([
                'user_id' => $this->user->id,
                'post_id' => $request->post_id,
                'parent_id' => $request->parent_id,
                'text' => $request->text
            ]);
        }else{
            $comment = $this->user->comments()->create([
                'user_id' => $this->user->id,
                'post_id' => $request->post_id,
                'text' => $request->text
            ]);
        }

        //Comment created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data' => $comment
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
        $comment = $this->user->Comments()->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, comment not found.'
            ], 400);
        }

        return $comment;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        //Validate data
        $data = $request->only('post_id', 'text');
        $validator = Validator::make($data, [
            'comment_id' => 'required',
            'text' => 'required|min:5'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update comment
        $comment = $comment->update([
            'comment_id' => $request->category_id,
            'text' => $request->text
        ]);

        //comment updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data' => $comment
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ], Response::HTTP_OK);
    }

    public function get_tags($id)
    {
        return (Comment::findOrFail($id))->tags;
    }
}
