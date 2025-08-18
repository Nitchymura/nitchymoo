<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentsController extends Controller
{
    private $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function index(Request $request){
        if($request->search){
            //search results
            $all_comments = $this->comment->latest()->where('body', 'LIKE', '%'.$request->search.'%')->paginate(10);
            //SELECT * FROM comments WHERE description LIKE '%searchword%'
        }else{
            $all_comments = $this->comment->withTrashed()->latest()->paginate(10);
        }

        return view('admin.comments.index')->with('all_comments', $all_comments)->with('search', $request->search);
    }

    public function deactivate($id){
        $this->comment->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->comment->onlyTrashed()->findOrFail($id)->restore();
        //restore() -- restores a soft-deleted record
        //  onlyTrashed() -- get only soft-deleted records
        return redirect()->back();
    }
}
