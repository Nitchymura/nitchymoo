<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class PostBody extends Model
{
    protected $table = 'post_bodies';
    protected $fillable = ['post_id', 'photo', 'priority'];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
