<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userListing(){
        $users = User::where('role', '!=', '1')->get();
        return view('users.user_listing',compact( 'users'));
    }

    public function userEdit($id){
        $users = User::where('id',$id)->get();
        return view('users.edit',compact( 'users'));
    }

    public function userSave(Request $request)
    {
        $users = User::find($request->user_id);
        $users->name = $request->name;
        $users->email = $request->email;
        $users->phone_number = $request->phone_number;
        $users->save();
        
        return redirect()->route('userListing')->with('success', 'Details updated successfully.');
    }

    public function userDelete($id){
        $users = User::find($id);
        $users->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');    
    }
 
    // public function botUser($id){
    //     $users = User::where('role', '!=', '1')->get();
    //     return view('users.user_listing',compact( 'users'));
    // }


}
 