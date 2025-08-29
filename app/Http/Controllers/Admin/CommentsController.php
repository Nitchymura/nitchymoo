<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Faq;

class CommentsController extends Controller
{
    private $comment;
    private $faq;

    public function __construct(Comment $comment, Faq $faq){
        $this->comment = $comment;
        $this->faq = $faq;
    }

    public function index(Request $request){
        if($request->search){
            //search results
            $all_comments = $this->comment->latest()->where('body', 'LIKE', '%'.$request->search.'%')->paginate(10);
            //SELECT * FROM comments WHERE description LIKE '%searchword%'
        }else{
            $all_comments = $this->comment->withTrashed()->latest()->paginate(10);
        }
        $all_faqs = $this->faq->latest()->get();
        return view('admin.comments.index')->with('all_comments', $all_comments)->with('search', $request->search)->with('all_faqs', $all_faqs);
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
    public function delete($id){
        // $this->post->destroy($id);
        $this->comment->findOrFail($id)->forceDelete();
        return redirect()->back();
    }
}
