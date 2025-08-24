<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Comment;

class ProfileController extends Controller
{
    private $user;
    private $comment;

    public function __construct(User $user, Comment $comment){
        $this->user = $user;
        $this->comment = $comment;
    }

    public function show($id){
        $user_a = $this->user->findOrFail($id);
        $all_comments = $this->comment->where('user_id', $user_a->id)->latest()->take(5)->get();
        $suggested_users = $this->getSuggestedUsers();
        return view('user.profiles.show')->with('user', $user_a)->with('all_comments',$all_comments)->with('suggested_users', $suggested_users);
    }

    public function edit(){
        // $user_a = $this->user->findOrFail(Auth::user()->id);        
        return view('user.profiles.edit');
    }

    // 
    
    public function update(Request $request)
{
    $request->validate([
        'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif', // maxは削除
        'name' => 'required|max:50',
        'email' => 'required|max:50|email|unique:users,email,' . Auth::user()->id,
        'introduction' => 'max:1500'
    ]);

    $user = $this->user->findOrFail(Auth::user()->id);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->introduction = $request->introduction;

    if ($request->hasFile('avatar')) {
        $user->avatar = $this->gdCompressToDataUrl($request->file('avatar'), 400, 78);
    }

    $user->save();

    return redirect()->route('profile.show', Auth::user()->id);
}
    private function gdCompressToDataUrl(\Illuminate\Http\UploadedFile $file, int $maxWidth = 1200, int $quality = 78): string
{
    // GDが無ければフォールバック
    if (!extension_loaded('gd')) {
        $binary = file_get_contents($file->getRealPath());
        $mime = $file->getMimeType() ?: 'application/octet-stream';
        return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }

    // 画像情報
    $path = $file->getRealPath();
    $info = @getimagesize($path);
    if ($info === false) {
        // 画像判定NGなら素で返す
        $binary = file_get_contents($path);
        $mime = $file->getMimeType() ?: 'application/octet-stream';
        return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }

    [$width, $height, $type] = $info;

    // 入力をGDで読み込み
    switch ($type) {
        case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($path); break;
        case IMAGETYPE_PNG:  $src = @imagecreatefrompng($path);  break;
        case IMAGETYPE_GIF:  $src = @imagecreatefromgif($path);  break;
        default:
            // JPEG/PNG/GIF以外は素で返す（mimesで弾いてる想定）
            $binary = file_get_contents($path);
            $mime = $file->getMimeType() ?: 'application/octet-stream';
            return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }
    if (!$src) {
        $binary = file_get_contents($path);
        $mime = $file->getMimeType() ?: 'application/octet-stream';
        return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }

    // リサイズ幅を決定（幅が大きい時のみ縮小）
    if ($width > $maxWidth) {
        $newW = $maxWidth;
        $newH = (int)round($height * ($newW / $width));
    } else {
        $newW = $width;
        $newH = $height;
    }

    // 出力キャンバス（JPEG想定：真っ白背景）
    $dst = imagecreatetruecolor($newW, $newH);
    // PNG/GIFの透明は白背景に合成（サイズ優先でJPEGに統一するため）
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);

    // 高品質リサンプル
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

    // バッファにJPEG出力
    ob_start();
    imagejpeg($dst, null, max(1, min(100, $quality))); // 1〜100にクリップ
    $jpegData = ob_get_clean();

    imagedestroy($src);
    imagedestroy($dst);

    return 'data:image/jpeg;base64,' . base64_encode($jpegData);
}


    public function updateRoleID(Request $request, $user_id){
        $user_a = $this->user->findOrFail($user_id);
        $user_a->role_id = $request->role_id;

        return redirect()->route('admin.users')->with('user', $user_a);
    }

    public function followers($id){
        $user_a = $this->user->findOrFail($id);

        return view('user.profiles.followers')->with('user', $user_a);
    }

    public function following($id){
        $user_a = $this->user->findOrFail($id);

        return view('user.profiles.following')->with('user', $user_a);
    }

    public function changePassword(){
        return view('user.profiles.change-password');
    }

    public function updatePassword(Request $request){
        //check if wrong old password
        $user_a = $this->user->findOrFail(Auth::user()->id);
        if(!Hash::check($request->old_password, $user_a->password)){
            //validation error
            return redirect()->back()->with('wrong_password_error', 'Wrong current password. Please try again.');
        }
        //new password the same as old password
        if($request->new_password == $request->old_password){
            //validation error
            return redirect()->back()->with('same_password_error', 'New password cannt be the same as current. Please try again.');
        }

        //new password confirmation (not the same)
        $request->validate([
            'new_password' => 'required|min:8|confirmed|alpha_num'
        ]);

        //update password
        $user_a->password = Hash::make($request->new_password);
        $user_a->save();

        return redirect()->back()->with('success_password_change', 'Password successfully changed!');

    }
    public function deleteAvatar(Request $request){
        $user = Auth::user();

        if ($user->avatar) {
            $user->avatar = null;
            $user->save();

            return response()->json(['message' => 'Avatar deleted'], 200);
        }

        return response()->json(['message' => 'No avatar found'], 404);
    }

    private function getSuggestedUsers(){
        //get a list of all users
        $all_users = $this->user->all()->except(Auth::user()->id);

        $suggested_users = []; //empty array
        $count = 0;
        foreach($all_users as $user){
            //if user is not followed yet...
            if(!$user->isFollowed() && $count<10){
                $suggested_users []= $user;
                $count++;
            }
        }
        return $suggested_users;
    }

// 共通のビュー用関数
protected function renderProfileUsers($type)
{
    // type に応じて対象ユーザーを取得
    switch($type) {
        case 'suggested':
            $users = $this->getSuggestedUsers();
            break;
        case 'followers':
            $users = $this->getFollowers();
            break;
        case 'following':
            $users = $this->getFollowing();
            break;
        default:
            $users = collect();
    }

    // すべてのページで suggested_users も渡す
    $suggested = $this->getSuggestedUsers();

    return view("user.profiles.$type", [
        $type.'_users' => $users,
        'suggested_users' => $suggested,
    ]);
}

public function allSuggested()
    {
        $suggested_users = $this->getSuggestedUsers();
        return view('user.profiles.suggested', compact('suggested_users'));
    }


}
