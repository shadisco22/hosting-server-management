<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class Admin extends Controller
{
    public function createOperator(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = new User();
        $user-> f_name = $request->input('f_name');
        $user-> l_name = $request->input('l_name');
        $user-> address = $request->input('address');
        $user-> phone = $request->input('phone');
        $user-> email = $request->input('email');
        $user-> password = Hash::make($request->input('password'));
        $user-> role = "Operator";
        $user-> save();
        return response()->json(['status' => 'success',
                                 'message' => 'Operator added successfully']);
    }

    public function update(Request $request,$id)
    {
        // $user = User::find($id);
        // $user->role = $request->input('role');
        // $user->save();
        // return response()->json($user);
    }
    public function show()
    {
        $users = User::all()->where('role','!=','Admin');
        $customers=customer::all();
        return response()->json(['users' => $users,'customers' => $customers]) ;

    }
    public function destroy(User $operator, $id)
    {
        $operator = User::find($id);
        if ($operator->delete()) {
            return response()->json(['status' => 'success',
                                     'message' => 'Operator deleted successfully']);
        } else {
            return response()->json(['status' => 'failed',
                                     'message' => 'Operator deletion failed']);
        }
    }
}


