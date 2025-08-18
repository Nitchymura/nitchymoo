<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\PostBody;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $post;
    private $user; 
    private $category;
    private $post_body;

    public function __construct(Post $post, User $user, Category $category, PostBody $post_body){
        $this->post = $post;
        $this->user = $user;
        $this->category = $category;
        $this->post_body = $post_body;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


public function index(Request $request)
{
    $sort   = $request->get('sort', 'latest'); // latest | oldest
    $search = $request->get('search');    

    $query = Post::query();

    // 検索（任意）
    if (filled($search)) {
        $query->where('description', 'LIKE', "%{$search}%");
    }

    // 並び替え
    switch ($sort) {
        case 'oldest':
            $query->orderBy('term_start', 'asc');
            break;
        case 'latest':
        default:
            $query->orderBy('term_start', 'desc');
            break;
    }

    // ページネーション（クエリ保持）
    $home_posts = $query->paginate(9)->appends($request->query());
    $user_intro = $this->user->where('id', 1)->value('introduction');

    return view('user.home', [
        'all_posts'       => $home_posts,          // Blade 側の変数名と合わせる
        // 'suggested_users' => $this->getSuggestedUsers(),
        'search'          => $search,
        'sort'            => $sort,
        'user_intro'      => $user_intro,
    ]);
}


    // public function index(Request $request)
    // {
    //     if($request->search){
    //         //search results
    //         $home_posts = $this->post->latest()->where('description', 'LIKE', '%'.$request->search.'%')->get();
    //         //SELECT * FROM posts WHERE description LIKE '%searchword%'
    //     }else{
    //         $home_posts = $this->post->latest()->get();
    //     }


    //     return view('user.home')->with('all_posts', $home_posts)
    //                             ->with('suggested_users', $this->getSuggestedUsers())
    //                             ->with('search', $request->search);
    // }

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

    public function allSuggested(){
        return view('user.profiles.suggested')->with('suggested_users', $this->getSuggestedUsers());
    }


}
