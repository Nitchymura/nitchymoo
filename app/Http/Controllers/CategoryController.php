<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Post;

class CategoryController extends Controller
{
    private $category;
    private $category_post;
    private $post;

    public function __construct(Category $category, CategoryPost $category_post, Post $post){
        $this->category = $category;
        $this->category_post = $category_post;
        $this->post = $post;
    }

    public function show($id){
        $all_categories = $this->category->all();
        $category_a = $this->category->findOrFail($id); 
        $all_posts = $category_a->posts()
        ->latest('posts.term_start')   // 並び順（任意）
        ->with(['user','postBodies'])      // 必要ならEager Load
        ->paginate(9);

        return view('user.posts.category.category-show')->with('all_categories', $all_categories)->with('all_posts', $all_posts)->with('category', $category_a);                                   
    }
}
