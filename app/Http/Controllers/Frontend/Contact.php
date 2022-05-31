<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact as ModelsContact;

class Contact extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'subject' => ['required'],
            'message' => ['required']
        ];
        $validation = Validator::make($request->all(), $rules);
        if($validation -> fails()){
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->all()
            ]);
        }
        else{
            $result = ModelsContact::create([
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => "Contact Successfully . Admin will response you via email",
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Some Problem",
                ]);
            }
        }
    }
}
