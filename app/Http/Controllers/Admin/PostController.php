<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Post;

use Exception;

class PostController extends Controller
{

    public function index()
    {
        try {
            $posts = Post::orderBy('id', 'desc')->with('categories')->get();
            if ($posts) {
                return response()->json([
                    'succes' => true,
                    'posts' => $posts,

                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request, $id)
    {
        try {
            $rules = [
                'title' => ['required'],
                'description' => ['required'],
                'image' => ['required'],

            ];
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            }

            $filename = "";
            if ($request->file('image')) {
                $filename = $request->file('image')->store('posts', 'public');
            } else {
                $filename = "null";
            }



            $result = Post::create([
                'cat_id' => $id,
                'title' => $request->title,
                'description' => $request->description,
                'image' => $filename,
                'views' => 0,
            ]);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => "Post Add Successfully"
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "Some Problem",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $posts = Post::findOrFail($id);
            if ($posts) {
                return response()->json([
                    'success' => true,
                    'posts' => $posts
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $posts = Post::findOrFail($id);
            $rules = [
                'title' => ['required'],
                'description' => ['required'],
                'cat_id' => ['required']

            ];
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            }

            $filename = "";
            $destination = public_path('storage\\' . $posts->image);
            if ($request->file('new_image')) {
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $filename = $request->file('new_image')->store('posts', 'public');
            }
            $filename = $request->old_image;



            $posts->title = $request->title;
            $posts->description = $request->description;
            $posts->cat_id = $request->cat_id;
            $posts->image = $filename;
            $result = $posts->save();
            if ($result) {
                return response()->json([
                    'succes' => true,
                    'message' => "Post Update Successfully"
                ]);
            }
            return response()->json([
                'succes' => false,
                'message' => "Some Problem."
            ]);
        } catch (Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $posts = Post::findOrFail($id)->delete();
            $destination = public_path('storage\\', $posts->image);

            if (File::exists($destination)) {
                File::delete($destination);
            }

            $result = $posts->delete();

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post Delete Successfully'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Some Problem'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function search($search)
    {
        try {
            $posts = Post::where('title', 'like', '%' . $search . '%')->orderBy('id', 'desc')->with('categories')->get();
            if ($posts) {
                return response()->json([
                    'success' => true,
                    'posts' => $posts
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
