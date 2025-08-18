<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'pivot_category_post';
    public $timestamps = false;
    protected $fillable = ['category_id', 'post_id'];
    
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function post(){
        return $this->belongsTo(Post::class)->withTrashed();
    }
}
