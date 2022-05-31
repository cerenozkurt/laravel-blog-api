<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Validator;
use PDO;


class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categorys = Category::orderBy('id', 'desc')->get();
            if ($categorys) {
                return response()->json([
                    'success' => true,
                    'categorys' => $categorys
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'category_name' => ['required', 'string', 'max:20', 'min:10', 'unique:categories'],
            ];

            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all(),
                ]);
            } else {
                $result = Category::create([
                    'category_name' => $request->category_name
                ]);
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => "Category Add Successfully",
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Some Problem",
                    ]);
                }
            }
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
            $categorys = Category::findOrFail($id);
            if ($categorys) {
                return response()->json([
                    'success' => true,
                    'categories' => $categorys
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
            $categorys = Category::findOrFail($id);
            $rules = [
                'category_name' => ['required', 'string', 'max:20', 'min:10', 'unique:categories'],
            ];
            $validation = Validator::make($request->all(), $rules);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validation->errors()->all()
                ]);
            } else {
                $categorys->category_name = $request->category_name;
                $result = $categorys->save();
                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Category Update Successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Some Problem'
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $result = Category::findOrFail($id)->delete();
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Category Delete Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Some Problem'
                ]);
            }
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
            $categorys = Category::where('category_name', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
            if ($categorys) {
                return response()->json([
                    'success' => true,
                    'category' => $categorys
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getTotalCategory()
    {
        try {
            $categorys = Category::where('category_name')->count();
            if ($categorys) {
                return response()->json([
                    'success' => true,
                    'categorys' => $categorys
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