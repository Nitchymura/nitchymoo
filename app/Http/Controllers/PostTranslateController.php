<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PostTranslateController extends Controller
{
    public function translate(Post $post)
    {
        $sourceText = trim(strip_tags((string)$post->description));
        if ($sourceText === '') {
            return response()->json(['translation' => '翻訳する本文がありません']);
        }

        try {
            // 無料API MyMemory 呼び出し
            $res = Http::timeout(10)->get('https://api.mymemory.translated.net/get', [
                'q'        => Str::limit($sourceText, 450), // 長文対策で短縮
                'langpair' => 'en|ja',  // 英語→日本語に固定
            ]);

            if (!$res->ok()) {
                return response()->json([
                    'translation' => '翻訳取得に失敗しました。（APIエラー）',
                ], 502);
            }

            $json = $res->json();
            $translation = data_get($json, 'responseData.translatedText');

            if (!$translation) {
                return response()->json([
                    'translation' => '翻訳取得に失敗しました。（応答不正）',
                ], 502);
            }

            return response()->json([
                'translation' => $translation,
                'provider'    => 'MyMemory (free)',
            ]);
        } catch (\Throwable $e) {
            \Log::warning('MyMemory translate failed', ['err' => $e->getMessage()]);
            return response()->json([
                'translation' => '翻訳取得に失敗しました。（通信エラー）',
            ], 502);
        }
    }
}
