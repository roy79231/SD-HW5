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
        $this->middleware(('auth'))->except(['index', 'show', 'search', 'index_all']);
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
        } elseif ($sort === 'timeReverse') {
            // 按留言數排序：按遊戲的留言數量排序
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'price') {
            $query->orderByDesc('price');
        } elseif ($sort === 'priceReverse') {
            $query->orderBy('price', 'asc');
        }
        $games = $query->paginate(5);
        if ($sort === 'comments') {
            // 按留言數排序：按遊戲的留言數量排序
            $games = Game::withCount('comments')
                ->orderBy('comments_count', 'desc')
                ->paginate(5);
        } elseif ($sort === 'commentsReverse') {
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
        foreach ($game->comments as $comment) {
            $comment->delete();
        }
        if ($game->image) {
            Storage::delete('public/' . $game->image);
        }
        $game->delete();
        return redirect()->route('root')->with('notice' . '遊戲已刪除');
    }

    public function search(Request $request)
    {
        // 取得搜尋關鍵字
        $keyword = $request->query('query');

        // 使用搜尋關鍵字進行遊戲標題的模糊搜尋
        $games = Game::where('title', 'like', '%' . $keyword . '%')->paginate(5);

        return view('games.search', ['games' => $games]);
    }
    public function shoplist()
    {
        $user = auth()->user();
        // 確認使用者是否存在購物車欄位，若不存在則初始化為空陣列
        if (!$user->shopping_cart) {
            $user->shopping_cart = [];
        }

        // 取得購物車內的遊戲 ID
        $gameIds = $user->shopping_cart;

        // 根據遊戲 ID 取得遊戲資料
        $games = Game::whereIn('id', $gameIds)->get();

        $totalAmount = $games->sum('price');


        // 將遊戲資料傳遞到 shoplist.blade.php 模板中
        return view('games.shoplist', ['games' => $games, 'totalAmount' => $totalAmount]);
    }
    public function addToCart($id)
    {
        // 取得當前登入的使用者
        $user = auth()->user();

        // 確認使用者是否存在購物車欄位，若不存在則初始化為空陣列
        if (!$user->shopping_cart) {
            $user->shopping_cart = [];
        }

        // 取得目前的購物車內容
        $shoppingCart = $user->shopping_cart;

        // 將遊戲 ID 加入購物車
        if (!in_array($id, $shoppingCart)) {
            // 遊戲 ID 不存在於購物車中，才將其加入
            $shoppingCart[] = $id;

            // 將修改後的購物車內容儲存回資料庫
            $user->shopping_cart = $shoppingCart;
            $user->save();

            return redirect()->back()->with('notice', '遊戲已新增到購物車！');
        } else {
            // 遊戲已存在於購物車中，直接返回並顯示提示訊息
            return redirect()->back()->with('notice', '遊戲已經在購物車中！');
        }
    }
    public function destroyShoplist($id){
        // 取得當前登入的使用者
        $user = auth()->user();

        // 在購物車內容中尋找要刪除的遊戲 ID 的索引
        $index = array_search($id, $user->shopping_cart);

        $shoppingCart = $user->shopping_cart;

        unset($shoppingCart[$index]);

            // 重新索引陣列，以避免留下空洞
        $user->shopping_cart = array_values($shoppingCart);

            // 將修改後的購物車內容儲存回資料庫
        $user->save();

        return redirect()->back()->with('notice', '遊戲已從購物車中移除！');
    }
    
    public function cleanShoplist(){
        $user = auth()->user();

        $user->shopping_cart = [];

        $user->save();

        return redirect()->back()->with('notice', '購物車已清空！');
    }
    public function buyFromShoplist(){
    // 取得當前登入的使用者
    $user = auth()->user();

    // 取得購物車內容
    $shoppingCart = $user->shopping_cart;
    
    
    $ownedGames = $user->owned_games;
    
    if (!$ownedGames) {
        $ownedGames = [];
    }
    // 將購物車內容複製到 owned_games 中
    $ownedGames = array_merge($ownedGames, $shoppingCart);

    $user->owned_games = array_values($ownedGames);
    // 清空購物車
    $user->shopping_cart = [];

    // 儲存修改後的使用者資料
    $user->save();

    return redirect()->route('shoplist')->with('notice', '購買成功！');
}
}
