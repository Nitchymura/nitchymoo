<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Comment;

class ProfileController extends Controller
{
    private $user;
    private $comment;

    public function __construct(User $user, Comment $comment){
        $this->user = $user;
        $this->comment = $comment;
    }

    public function show($id){
        $user_a = $this->user->findOrFail($id);
        $all_comments = $this->comment->where('user_id', $user_a->id)->latest()->take(5)->get();

        return view('user.profiles.show')->with('user', $user_a)->with('all_comments',$all_comments);
    }

    public function edit(){
        // $user_a = $this->user->findOrFail(Auth::user()->id);        
        return view('user.profiles.edit');
    }

    public function update(Request $request){
        $request->validate([
            'avatar' => 'max:2048|mimes:jpeg,jpg,png,gif',
            'name' => 'required|max:50',
            'email' => 'required|max:50|email|unique:users,email,'.Auth::user()->id,
            'introduction' => 'max:1500'
        ]);

        $user_a = $this->user->findOrFail(Auth::user()->id);

        $user_a->name = $request->name;
        $user_a->email = $request->email;
        $user_a->introduction = $request->introduction;
        if($request->avatar){
            $user_a->avatar = "data:image/".$request->avatar->extension().";base64,".base64_encode(file_get_contents($request->avatar));
        }

        $user_a->save();

        return redirect()->route('profile.show',Auth::user()->id);
    }

    public function updateRoleID(Request $request, $user_id){
        $user_a = $this->user->findOrFail($user_id);
        $user_a->role_id = $request->role_id;

        return redirect()->route('admin.users')->with('user', $user_a);
    }

    public function followers($id){
        $user_a = $this->user->findOrFail($id);

        return view('user.profiles.followers')->with('user', $user_a);
    }

    public function following($id){
        $user_a = $this->user->findOrFail($id);

        return view('user.profiles.following')->with('user', $user_a);
    }

    public function changePassword(){
        return view('user.profiles.change-password');
    }

    public function updatePassword(Request $request){
        //check if wrong old password
        $user_a = $this->user->findOrFail(Auth::user()->id);
        if(!Hash::check($request->old_password, $user_a->password)){
            //validation error
            return redirect()->back()->with('wrong_password_error', 'Wrong current password. Please try again.');
        }
        //new password the same as old password
        if($request->new_password == $request->old_password){
            //validation error
            return redirect()->back()->with('same_password_error', 'New password cannt be the same as current. Please try again.');
        }

        //new password confirmation (not the same)
        $request->validate([
            'new_password' => 'required|min:8|confirmed|alpha_num'
        ]);

        //update password
        $user_a->password = Hash::make($request->new_password);
        $user_a->save();

        return redirect()->back()->with('success_password_change', 'Password successfully changed!');

    }
    public function deleteAvatar(Request $request){
        $user = Auth::user();

        if ($user->avatar) {
            $user->avatar = null;
            $user->save();

            return response()->json(['message' => 'Avatar deleted'], 200);
        }

        return response()->json(['message' => 'No avatar found'], 404);
    }

}
