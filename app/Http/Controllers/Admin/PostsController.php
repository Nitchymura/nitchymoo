<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    private $post;

    public function __construct(Post $post){
        $this->post = $post;
    }

    public function index(Request $request){
        if($request->search){
            //search results
            $all_posts = $this->post->latest()->where('description', 'LIKE', '%'.$request->search.'%')->paginate(10);
            //SELECT * FROM posts WHERE description LIKE '%searchword%'
        }else{
            $all_posts = $this->post->withTrashed()->latest()->paginate(10);
        }

        return view('admin.posts.index')->with('all_posts', $all_posts)->with('search', $request->search);
    }

    public function deactivate($id){
        $this->post->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->post->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }
}
