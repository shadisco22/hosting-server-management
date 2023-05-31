<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class Admin extends Controller
{
    public function createOperater(Request $request)
    {
        $user = new User();
        $user-> f_name = $request->input('f_name');
        $user-> l_name = $request->input('l_name');
        $user-> address = $request->input('address');
        $user-> phone = $request->input('phone');
        $user-> email = $request->input('email');
        $user-> password = $request->input('password');
        $user-> role = $request->input('operater');
        $user-> save();
        return response()->json(['message' => 'success']);
    }

    public function update(Request $request,$id)
    {
        $user = User::find($id);
        $user->role = $request->input('role');
        $user->save();
        return response()->json($user);
    }
    public function show()
    {
        $user = User::all();
        return response()->json($user) ;

    }

}


