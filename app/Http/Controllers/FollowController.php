<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Follow;
use App\Models\User;


class FollowController extends Controller
{
    private $follow;

    public function __construct(Follow $follow){
        $this->follow = $follow;
    }

    public function store($user_id){
        $this->follow->follower_id = Auth::user()->id;
        $this->follow->followed_id = $user_id;

        $this->follow->save();
        return redirect()->back();
    }

        public function delete($user_id){
        //delete()
        $this->follow->where('follower_id', Auth::user()->id)
                    ->where('followed_id', $user_id)
                    ->delete();

        return redirect()->back();
    }

    public function toggleFollow($userId)
    {
        $me = Auth::user();
        if (!$me) return response()->json(['error' => 'Unauthorized'], 401);
        if ((int)$userId === (int)$me->id) {
            return response()->json(['error' => 'Cannot follow yourself'], 422);
        }

        $target = User::findOrFail($userId);

        // ※ カラム名の向きが逆だと「Follow→Following」が効きません。
        $exists = Follow::where('follower_id', $me->id)
                        ->where('followed_id', $target->id)
                        ->exists();

        if ($exists) {
            Follow::where('follower_id', $me->id)
                  ->where('followed_id', $target->id)
                  ->delete();
            $following = false;
        } else {
            // 二重登録を避ける
            Follow::firstOrCreate([
                'follower_id' => $me->id,
                'followed_id' => $target->id,
            ]);
            $following = true;
        }

        // ★ 余計なキーを返さず、following だけ返す
        return response()->json(['following' => $following]);
    }
}
