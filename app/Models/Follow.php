<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follows';

    // 『id』カラムを作っていないテーブルなら ↓ を追加
    public $incrementing = false;
    protected $primaryKey = null;

    // タイムスタンプ列が無いなら ↓ を追加
    public $timestamps = false;

    // 一括代入で許可する属性（←これが無くて怒られてます）
    protected $fillable = ['follower_id', 'followed_id'];
    
    //follow belongs to user (opposite to follows())
    public function followed(){
        return $this->belongsTo(User::class, 'followed_id')->withTrashed();
    }

    //follow belongs to user (opposite of followers())
    public function follower(){
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    }

    // Follow.php
    public function followedUser()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }

}
