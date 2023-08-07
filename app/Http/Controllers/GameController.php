<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(('auth'))->except(['index', 'show','search','index_all']);
    }
    public function index()
    {   
        $games = Game::withCount('comments')
        ->orderBy('comments_count', 'desc')
        ->paginate(5);

        return view('games.index', ['games' => $games]);
    }
    public function index_all(Request $request)
    {   
        $sort = $request->input('sort');

        $query = Game::query();
    
        if ($sort === 'time') {
            // 按時間排序：最新的排在前面
            $query->orderByDesc('created_at');
        }
        elseif ($sort === 'timeReverse') {
            // 按留言數排序：按遊戲的留言數量排序
            $query->orderBy('created_at','asc');
        }
        elseif( $sort === 'price'){
            $query->orderByDesc('price');
        }
        elseif( $sort === 'priceReverse'){
            $query->orderBy('price','asc');
        }
        $games = $query->paginate(5);
        if ($sort === 'comments') {
            // 按留言數排序：按遊戲的留言數量排序
            $games = Game::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->paginate(5);
        }
        elseif ($sort === 'commentsReverse') {
            // 按留言數排序：按遊戲的留言數量排序
            $games = Game::withCount('comments')
            ->orderBy('comments_count', 'asc')
            ->paginate(5);
        }
        return view('games.index_all', ['games' => $games, 'sort' => $sort]);
    }
    public function create()
    {
        return view('games.create');
    }
    public function store(Request $request)
    {
        $content = $request->validate([
            'title' => 'required',
            'tag' => 'required',
            'price' => 'required',
            'content' => 'required|min:10',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
        ]);
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $content['image'] = $imagePath;
        }
        auth()->user()->games()->create($content);
        return redirect()->route('root')->with('notice', '遊戲新增成功!');
    }
    public function edit($id)
    {
        $games = Game::find($id);
        return view('games.edit', ['game' => $games]);
    }
    public function update(Request $request, $id)
    {
        $game = Game::find($id);
        $content = $request->validate([
            'title' => 'required',
            'tag' => 'required',
            'price' => 'required',
            'content' => 'required|min:10',
        ]);
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            ]);
            if ($game->image) {
                Storage::delete('public/' . $game->image);
            }
            // 保存新图片到 storage/app/public/images 目录
            $imagePath = $request->file('image')->store('images', 'public');
            $content['image'] = $imagePath;
        }
        $game->update($content);
        return redirect()->route('root')->with('notice', '遊戲內容更新成功');
    }
    public function show($id)
    {
        $game = Game::find($id);
        $totalComments = $game->comments()->count();
        $totalLikes = $game->comments()->where('like_type', 1)->count();
        $totalDislikes = $game->comments()->where('like_type', 2)->count();
        return view('games.show', [
            'game' => $game, 'totalComments' => $totalComments, 'totalLikes' => $totalLikes,
            'totalDislikes' => $totalDislikes,
        ]);
    }
    public function destroy($id)
    {
        $game = Game::find($id);
        foreach($game->comments as $comment){
            $comment->delete();
        }
        $game->delete();
        return redirect()->route('root')->with('notice' . '遊戲已刪除');
    }
    public function search(Request $request){
        // 取得搜尋關鍵字
        $keyword = $request->query('query');

        // 使用搜尋關鍵字進行遊戲標題的模糊搜尋
        $games = Game::where('title', 'like', '%'.$keyword.'%')->paginate(5);

        return view('games.search', ['games' => $games]);
    }
    
}
