<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(Request $request){
        if($request->search){
            //search results
            $all_users = $this->user->latest()->where('name', 'LIKE', '%'.$request->search.'%')->paginate(10);
            //SELECT * FROM posts WHERE description LIKE '%searchword%'
        }else{
            $all_users = $this->user->orderBy('name')->withTrashed()->paginate(10);
        }

        return view('admin.users.index')->with('all_users', $all_users)->with('search', $request->search);
    }

    public function updateRoleID(Request $request, $id){
        $user_a = $this->user->findOrFail($id);
        $user_a->role_id = $request->role_id;

        $user_a->save();

        return redirect()->route('admin.users')->with('user', $user_a);
    }

    public function deactivate($id){
        $this->user->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }

    public function delete($id){
        // $this->post->destroy($id);
        $this->user->findOrFail($id)->forceDelete();
        return redirect()->back();
    }
}
