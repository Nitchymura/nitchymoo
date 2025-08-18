<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostBody;

class PostBodyController extends Controller
{
    private $post_body;

    public function __construct(PostBody $post_body){
        $this->post_body = $post_body;
    }

    public function store(Request $request, $post_id){
        // Validation...
        $request->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // ファイルが選ばれている場合
    // if ($request->has('photo')) {
    //     foreach ($request->file('photo') as $photo) {
    //         if ($photo && $photo->isValid()) {
    //             // ファイルの内容を読み込む
    //             $fileContent = file_get_contents($photo->getRealPath());

    //             // MIMEタイプを取得（例：image/jpeg）
    //             $mimeType = $photo->getMimeType();

    //             // Base64にエンコードしてデータURLを作成
    //             $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);

    //             // PostBodyに保存（post_id を関連付け）
    //             PostBody::create([
    //                 'post_id' => $post_id,  // 受け取った post_id を関連付け
    //                 'image' => $base64Image,  // Base64形式で保存
    //             ]);
    //         }
    //     }
    // }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                if ($photo && $photo->isValid()) {
                    // ファイルの中身を読み込む
                    $fileContent = file_get_contents($photo->getRealPath());
        
                    // MIMEタイプを取得（例：image/jpeg）
                    $mimeType = $photo->getMimeType();
        
                    // Base64にエンコードしてデータURLを作成
                    $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);
        
                    // 優先度の設定
                    $priority = $request->input("priorities.$i") ?? ($i + 1);
        
                    // データベースに保存
                    PostBody::create([
                        'post_id' => $post_id,
                        'photo' => $base64Image, // ← ここがBase64データ
                        'priority' => $priority,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Photos uploaded successfully');
    }


    public function update(Request $request, $post_id){
        $body_a = $this->post_body->findOrFail($post_id);
        $body_a->delete();
    
        // ② 次にアップロードファイルを処理
        if ($request->hasFile('photos')) {
           
        return true;
    }
    
    }
}
