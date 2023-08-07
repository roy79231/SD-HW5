@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- 文章內容 -->
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-4x1 mx-4" style="font-size: 50px">{{ $game->title }}</div>
            <div class="d-flex align-items-center mx-4">
                <img src="http://i-gorgeous.com/upload/images/34.png" alt="Avatar" class="rounded-circle mx-2"
                    style="width: 35px; height: 35px;">
                <div class="font-weight-normal">{{ $game->user->name }}</div>
                <div class="font-weight-light text-secondary">&nbsp;&nbsp;-&nbsp;&nbsp;{{ $game->created_at }}</div>
            </div>
        </div>
        <div>
            <div>#{{ $game->tag }}</div>
        </div>
        <hr>
        <img src="{{ asset('storage/' . $game->image) }}" class="img-fluid" alt="遊戲圖片">
        @if(auth()->check())
        @if(in_array($game->id, auth()->user()->owned_games))
            <p class="btn btn-primary">開始遊玩</p>
        @else
            <form action="{{ route('games.addToCart', ['gameId' => $game->id]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-primary">新增遊戲到購物車</button>
            </form>
        @endif
        @endif
        <p class="my-5 mx-4" style="font-size: 20px">
            {!! nl2br($game->content) !!}
        </p>
        <div>
            總留言數：{{ $totalComments }}
        </div>
        <div>
            總讚數：{{ $totalLikes }}
        </div>
        <div>
            總倒讚數：{{ $totalDislikes }}
        </div>
        <a href="{{ route('root') }}" style="font-size: 20px">回熱門遊戲列表</a>
        @foreach ($game->comments as $comment)
            <div>
                {{ $comment->content }}
                <!-- 显示讚/倒讚选项 -->
                @if ($comment->like_type === 1)
                    <span>讚</span>
                @elseif ($comment->like_type === 2)
                    <span>倒讚</span>
                @else
                    <span>暂无选择</span>
                @endif
                <!-- 留言者信息 -->
                <div>{{ $comment->user->name }} - {{ $comment->created_at }}</div>
                <!-- 编辑和删除按钮 -->
                <!-- 删除按钮 -->
                @if(auth()->check() && auth()->user()->id===$comment->user_id)
                <form action="{{ route('comments.destroy', ['gameId' => $game->id, 'comment' => $comment->id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit">删除</button>
                </form>
                @endif
            </div>
            <hr>
        @endforeach

        <!-- 添加留言表单 -->
        @if(auth()->check())
        <form action="{{ route('comments.store', ['gameId' => $game->id]) }}" method="post">
            @csrf
            <div>
                <textarea name="content" rows="3"></textarea>
            </div>
            <div>
                <!-- 添加讚/倒讚选项 -->
                <input type="radio" name="like_type" value="1">讚
                <input type="radio" name="like_type" value="2">倒讚
            </div>
            <div>
                <button type="submit">提交留言</button>
            </div>
        </form>
        @endif
    </div>
@endsection
