<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function categoryPosts(){
        return $this->hasMany(CategoryPost::class);
    }

    public function posts(){
        return $this->belongsToMany(Post::class, 'pivot_category_post', 'category_id', 'post_id');
    }
}
