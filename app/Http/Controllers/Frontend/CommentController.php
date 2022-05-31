<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'comment' => ['required']
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }
        $result = Comment::create([
            'post_id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment
        ]);
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => "Comment Successfully.",
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "Some Problem",
        ]);
    }

    public function getComments()
    {


        try {
            $comments = Comment::with('posts')->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'contects' => $comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'contects' => $e->getMessage(),
            ]);
        }
    }
}
