<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUser(Request $request)
    {

        $user = $request->user();
        return response()->json($user, 200);
    }

    public function password(Request $request)
    {
        $rules = [
            'password' => ['required', 'min:5']
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'password tidak sesuai',
                'errors' => $validator->errors()
            ], 422);
        };

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diubah'
        ], 200);
    }
}
