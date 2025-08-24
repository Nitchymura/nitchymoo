<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID = 2;
    const GUEST_ROLE_ID = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(){
        return $this->hasMany(Post::class)->withTrashed()->latest();
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

        //user has many follows (user follows many users)
    public function follows(){
        return $this->hasMany(Follow::class, 'follower_id');
    }

    //user has many followers
    public function followers(){
        return $this->hasMany(Follow::class, 'followed_id');
    }

    public function isFollowed(){
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
    }

    public function followingYou(){
        return $this->follows()->where('followed_id', Auth::user()->id)->exists();
    }

    public function followingUsers(){
    // follows: 自分がフォローしている Follow レコード
    return $this->follows()->with('followedUser')->get()->map(function($follow){
        return $follow->followedUser;
    });
}

}
