<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;

class FaqsController extends Controller
{
    private $faq;

    public function __construct(FAQ $faq){
        $this->faq = $faq;
    }

    public function index(){
        $all_faqs = $this->faq->latest()->get();
        return view('admin.questions.index')->with('all_faqs', $all_faqs);
    }

    public function store(Request $request){
        $request->validate([
            'question' => 'required|max:1000',
            'answer' => 'nullable|max:3000'
        ]);

        $new_faq = $this->faq;
        $new_faq->question = $request->question;
        $new_faq->answer = $request->answer;
        $new_faq->save();

        return redirect()->back();
    }

    // public function edit($id){
    //     $faq = $this->faq->findOrFail($id);
    //     return view('admin.faqs.edit')->with('faq', $faq);
    // }

    public function update(Request $request, $id){
        $request->validate([
            'question' => 'required|max:1000',
            'answer' => 'nullable|max:3000'
        ]);

        $faq = $this->faq->findOrFail($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return redirect()->route('admin.faqs');
    }

    public function delete($id){
        // $this->post->destroy($id);
        $this->faq->findOrFail($id)->forceDelete();
        return redirect()->route('admin.faqs');
    }

}
