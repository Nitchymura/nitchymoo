<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use App\Models\Post;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\PostBody;
use App\Models\Like;


class PostController extends Controller
{
    private $category;
    private $post;
    private $post_body;
    private $like;

    public function __construct(Category $category, Post $post, PostBody $post_body, Like $like){
        $this->category = $category;
        $this->post = $post;
        $this->post_body = $post_body;
        $this->like = $like;
    }
    
    public function create(){
        $all_categories = $this->category->all();
        return view('user.posts.create', compact('all_categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'categories'   => ['required','array','between:1,3'],
        'title'        => ['required','string'],
        'description'  => ['required','string','max:1000'],
        'term_start'   => ['nullable','date'],
        'term_end'     => ['nullable','date','after_or_equal:term_start'],
        'image'        => ['required','image','mimes:jpg,jpeg,png,gif','max:1500'],     // メイン必須のまま
        'photos.*.*'   => ['nullable','image','mimes:jpg,jpeg,png,gif','max:2048'],    // ← 2段配列対応（photos[new][i]）
    ]);

    DB::transaction(function () use ($request) {

        // ---- Post 作成 ----
        $post = new Post();
        $post->title       = $request->title;
        $post->description = $request->description;
        $post->term_start  = $request->term_start ?: null;
        $post->term_end    = $request->term_end ?: null;
        $post->user_id     = Auth::id();

        // メイン画像（GDで圧縮→Base64）
        if ($request->hasFile('image')) {
            $post->image = $this->gdCompressToDataUrl($request->file('image'));
        }
        $post->save();

        // ---- カテゴリ保存 ----
        foreach ($request->categories as $category_id) {
            CategoryPost::create([
                'category_id' => $category_id,
                'post_id'     => $post->id,
            ]);
        }

        // ---- サブ画像 (最大3枠) ----
        // create 画面は photos[new][i] / priorities[new][i] で飛んでくる想定
        $photosAll        = $request->file('photos') ?? [];                 // ['new' => [1=>UploadedFile,...]] あるいは [1=>UploadedFile,...]
        $photosNew        = $photosAll['new'] ?? $photosAll;                // 'new' キーがあればそれを、無ければフラット配列を使う
        $prioritiesAll    = $request->input('priorities', []);              // ['new'=>[1=>1,...]] or [1=>1,...]
        $prioritiesNew    = $prioritiesAll['new'] ?? $prioritiesAll;

        for ($slot = 1; $slot <= 6; $slot++) {
            /** @var \Illuminate\Http\UploadedFile|null $uploaded */
            $uploaded = $photosNew[$slot] ?? null;
            // 万一 0 始まりで入ってくるフォームにも対応
            if (!$uploaded && isset($photosNew[$slot - 1])) {
                $uploaded = $photosNew[$slot - 1];
            }

            if ($uploaded) {
                $dataUrl  = $this->gdCompressToDataUrl($uploaded);
                $priority = $prioritiesNew[$slot] ?? $slot;

                PostBody::create([
                    'post_id'  => $post->id,
                    'photo'    => $dataUrl,
                    'priority' => $priority,
                ]);
            }
        }
    });

    return redirect()->route('home')->with('status', 'Post created successfully.');
}




    public function show($id){
        $post_a = $this->post->findOrFail($id);
        $all_bodies = $this->post_body->where('post_id', $id)->get();
        

        return view('user.posts.show', compact('all_bodies'))->with('post', $post_a);
    }

    public function edit($id){
        $all_categories = $this->category->all();
        $post_a = $this->post->findOrFail($id);
        $all_bodies = $this->post_body->where('post_id', $id)->get();

        $selected_categories = [];
        foreach($post_a->categoryPosts as $category_post){
            $selected_categories [] = $category_post->category_id;
        }
        return view('user.posts.edit')->with('post', $post_a)
                                        ->with('all_categories', $all_categories)
                                        ->with('all_bodies', $all_bodies)
                                        ->with('selected_categories', $selected_categories);
    }

    

    public function update(Request $request, $id)
{
    $request->validate([
        'categories'   => ['required','array','between:1,3'],
        'title'        => ['required','string'],
        'description'  => ['required','string','max:1000'],
        'term_start'   => ['nullable','date'],
        'term_end'     => ['nullable','date','after_or_equal:term_start'],
        'image'        => ['nullable','image','mimes:jpg,jpeg,png,gif','max:1500'],
        'photos.*.*'   => ['nullable','image','mimes:jpg,jpeg,png,gif','max:2048'],
    ]);

    $post = Post::findOrFail($id);

    DB::transaction(function () use ($request, $post, $id) {

        // ---- メイン情報 ----
        $post->title       = $request->title;
        $post->description = $request->description;
        $post->term_start  = $request->term_start ?: null;
        $post->term_end    = $request->term_end ?: null;

        if ($request->hasFile('image')) {
            $post->image = $this->gdCompressToDataUrl($request->file('image')); // ←ここだけ差し替え
            // $post->image = "data:image/".$request->image->extension().";base64,".base64_encode(file_get_contents($request->image));
        }
        $post->save();

        // ---- カテゴリ ----
        $post->categoryPosts()->delete();
        foreach ($request->categories as $category_id) {
            CategoryPost::create([
                'category_id' => $category_id,
                'post_id'     => $post->id,
            ]);
        }

        // ---- サブ画像(最大3枠) ----
        $filesArray        = $request->file('photos')[$id] ?? [];
        $prioritiesArray   = $request->input("priorities.$id", []);
        $existingIdsArray  = $request->input("existing_photos.$id", []);
        $deleteFlagsArray  = $request->input("delete_photos.$id", []);

        for ($slot = 1; $slot <= 6; $slot++) {
            /** @var \Illuminate\Http\UploadedFile|null $uploaded */
            $uploaded   = $filesArray[$slot] ?? null;
            $priority   = $prioritiesArray[$slot] ?? $slot;
            $existingId = $existingIdsArray[$slot] ?? null;
            $deleteFlag = (string)($deleteFlagsArray[$slot] ?? '0');

            $existing = $existingId
                ? PostBody::where('id', $existingId)->where('post_id', $post->id)->first()
                : null;

            // 新規＝作成/置換
            if ($uploaded) {
                $dataUrl = $this->gdCompressToDataUrl($uploaded); // ←ここも差し替え
                if ($existing) {
                    $existing->update(['photo' => $dataUrl, 'priority' => $priority]);
                } else {
                    PostBody::create(['post_id' => $post->id, 'photo' => $dataUrl, 'priority' => $priority]);
                }
                continue;
            }

            // 削除
            if ($deleteFlag === '1' && $existing) {
                $existing->delete();
                continue;
            }

            // priority変更のみ
            if ($existing && (int)$existing->priority !== (int)$priority) {
                $existing->update(['priority' => $priority]);
            }
        }
    });

    return redirect()->route('post.show', $post->id)->with('status', 'Post updated successfully.');
    }

    private function gdCompressToDataUrl(\Illuminate\Http\UploadedFile $file, int $maxWidth = 1600, int $quality = 78): string
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

    public function deleteImage(Request $request, $id){
        $post_a = $this->post->findOrFail($id);

        if ($post_a->image) {
            $post_a->image = null;
            $post_a->save();

            return response()->json(['message' => 'Image deleted'], 200);
        }

        return response()->json(['message' => 'No Image found'], 404);
    }

    public function toggleLike($id){
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // 存在しなければ404
    $post = Post::findOrFail($id);

    // すでにLikeしているかを軽量に確認
    $exists = $post->likes()->where('user_id', $user->id)->exists();

    if ($exists) {
        // 取り消し
        $post->likes()->where('user_id', $user->id)->delete();
        $liked = false;
    } else {
        // 付与（リレーション経由）
        $post->likes()->create(['user_id' => $user->id]);
        $liked = true;
    }

    // 最新件数を返す
    $count = $post->likes()->count();

    return response()->json([
        'liked' => $liked,
        'like_count' => $count,
    ]);
}




    public function deactivate($id){
        $this->post->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->post->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

    public function delete($id){
        // $this->post->destroy($id);
        $this->post->findOrFail($id)->forceDelete();
        return redirect()->route('home');
    }
}
// public function update(Request $request, $id){
    //     $request->validate([
    //         'categories' => 'required|array|between:1,3',
    //         'title' => 'required',
    //         'description' => 'required|max:1000',
    //         'image' => 'max:1048|mimes:jpg,jpeg,png,gif'
    //     ]);
    //     $post_a = $this->post->findOrFail($id);

    //     $post_a->description = $request->description;
    //     $post_a->title = $request->title;
    //     $post_a->term_start = $request->term_start;
    //     $post_a->term_end = $request->term_end;
    //     if($request->image)
    //         $post_a->image = "data:image/".$request->image->extension().";base64,".base64_encode(file_get_contents($request->image));
    //     $post_a->save();

    //     $post_a->categoryPosts()->delete();

    //     foreach($request->categories as $category_id){
    //         CategoryPost::create([
    //             'category_id' => $category_id,
    //             'post_id' => $post_a->id
    //         ]);
    //     }

    //     if ($request->hasFile('photos')) {
    //     foreach ($request->file('photos') as $i => $photo) {
    //         if ($photo) {
    //             // 画像ファイルの内容を読み込む
    //             $fileContent = file_get_contents($photo->getRealPath());

    //             // MIMEタイプを取得（例：image/jpeg）
    //             $mimeType = $photo->getMimeType();

    //             // Base64エンコードしてデータURLを作成
    //             $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

    //             // 優先度の設定
    //             $priority = $request->input("priorities.$i") ?? ($i + 1);

    //             // データベースに保存
    //             PostBody::create([
    //                 'post_id' => $post_a->id,
    //                 'photo' => $base64Image,  // Base64形式で保存
    //                 'priority' => $priority,
    //             ]);
    //         }
    //     }
    // }
    //     return redirect()->route('home');
    // }

    //     public function store(Request $request)
// {
//     $request->validate([
//         'categories' => 'required|array|between:1,3',
//         'title' => 'required',
//         'description' => 'required|max:1000',
//         'image' => 'required|max:1048|mimes:jpg,jpeg,png,gif',
//         'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
//     ]);

//     // Postの作成
//     $post = new Post();
//     $post->description = $request->description;
//     $post->title = $request->title;
//     $post->term_start = $request->term_start;
//     $post->term_end = $request->term_end;
//     $post->user_id = Auth::user()->id;
//     $post->image = "data:image/".$request->image->extension().";base64,".base64_encode(file_get_contents($request->image));
//     $post->save();

//     // カテゴリの保存
//     foreach ($request->categories as $category_id) {
//         CategoryPost::create([
//             'category_id' => $category_id,
//             'post_id' => $post->id
//         ]);
//     }

//     // 画像がアップロードされている場合、PostBodyController にリダイレクト
//     // if ($request->hasFile('photo')) {
//     //     return redirect()->route('post.body.store', ['post_id' => $post->id])->with('photo', $request->file('photo'));
//     // }

//     if ($request->hasFile('photos')) {
//         foreach ($request->file('photos') as $i => $photo) {
//             if ($photo) {
//                 // 画像ファイルの内容を読み込む
//                 $fileContent = file_get_contents($photo->getRealPath());

//                 // MIMEタイプを取得（例：image/jpeg）
//                 $mimeType = $photo->getMimeType();

//                 // Base64エンコードしてデータURLを作成
//                 $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

//                 // 優先度の設定
//                 $priority = $request->input("priorities.$i") ?? ($i + 1);

//                 // データベースに保存
//                 PostBody::create([
//                     'post_id' => $post->id,
//                     'photo' => $base64Image,  // Base64形式で保存
//                     'priority' => $priority,
//                 ]);
//             }
//         }
//     }

//     return redirect()->route('home');
// }
