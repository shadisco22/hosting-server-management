<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\activities_log;
use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        $user->f_name = $request->input('f_name');
        $user->l_name = $request->input('l_name');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role = "Operator";
        if ($user->save()) {
            activities_log::create([
                'user_id' => Auth::id(),
                'activity_type' => 'create',
                'on_table' => 'users',
                'record_id' => $user->id
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Operator added successfully'
        ]);
    }

    // public function update(Request $request,$id)
    // {
    //     $user = User::find($id);
    //     $user->role = $request->input('role');
    //     $user->save();
    //     return response()->json($user);
    // }

    public function operatorInfo()
    {
        //Auth::id()
        $operator_id = Auth::id();
        $operator = User::find($operator_id);
            if($operator != null){
                    $operator_info = [
                        'id' => Auth::id(),
                        'f_name' => $operator->f_name,
                        'l_name' => $operator->l_name,
                        'address' => $operator->address,
                        'email' => $operator->email,
                        'phone' => $operator->phone
                    ];

                    return response()->json($operator_info);
            }
            else{
                return response()->json(['status' => 'failed',
                                         'message' => 'Operator not found']);
            }
    }

     public function adminInfo()
    {
        //Auth::id()
        $admin_id = Auth::id();
        $admin = User::find($admin_id);
            if($admin != null){
                    $admin_info = [
                        'id' => Auth::id(),
                        'f_name' => $admin->f_name,
                        'l_name' => $admin->l_name,
                        'address' => $admin->address,
                        'email' => $admin->email,
                        'phone' => $admin->phone
                    ];

                    return response()->json($admin_info);
            }
            else{
                return response()->json(['status' => 'failed',
                                         'message' => 'Admin not found']);
            }
    }

    public function show()
    {
        $users = User::all()->where('role', '!=', 'Admin');
        $_users = collect([]);
        foreach($users as $user){
            $created_at = new  Carbon($user->created_at);
            $created_at = '20'.$created_at->format('y-m-d');
            if($user->role == 'Customer'){
                $customer = customer::where('user_id',$user->id)->first();
                $_users->push([
                    'id' => $customer->id,
                    'f_name' => $user->f_name,
                    'l_name' => $user->l_name,
                    'role' => $user->role,
                    'email' => $user->email,
                    'address' => $user->address,
                    'phone' => $user->phone,
                    'company_name' => $customer->company_name,
                    'created_at' => $created_at
                ]);
            }else{
                $_users->push([
                    'id' => $user->id,
                    'f_name' => $user->f_name,
                    'l_name' => $user->l_name,
                    'role' => $user->role,
                    'email' => $user->email,
                    'address' => $user->address,
                    'phone' => $user->phone,
                    'created_at' => $created_at
                ]);

            }
        }
        return response()->json(['users' => $_users]);
    }
    public function update(Request $request, User $operator, $id)
    {
        // Update opertor profile

        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::find($id);
        if ($user != null) {
            User::whereId($user->id)->update([
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            activities_log::create([
                'user_id' => Auth::id(),
                'activity_type' => 'update',
                'on_table' => 'users',
                'record_id' => $user->id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Operator updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Operator does not exist'
            ]);
        }
    }

    public function destroy(User $operator, $id)
    {
        $operator = User::find($id);
        if ($operator->delete()) {
            activities_log::create([
                'user_id' => Auth::id(),
                'activity_type' => 'delete',
                'on_table' => 'users',
                'record_id' => $id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Operator deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Operator deletion failed'
            ]);
        }
    }
}
