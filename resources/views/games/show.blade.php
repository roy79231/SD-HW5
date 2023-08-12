@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- 文章內容 -->
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-4x1 mx-4" style="font-size: 50px">{{ $game->title }}</div>
            <div class="align-items-center mx-4">
                <div class="font-weight-normal d-none d-md-block" style="font-size: 20px ">發行商:{{ $game->user->name }}</div>
                <div class="font-weight-normal d-none d-md-block" style="font-size: 20px ">發行時間:{{ $game->created_at }}</div>
            </div>
        </div>
        <div>
            <p style="font-size: 20px;padding-left:27px">#{{ $game->tag }}</p>
        </div>
        <hr>
        <img src="{{ asset('storage/' . $game->image) }}" class="img-fluid" alt="遊戲圖片">
        @if(auth()->check())
        @if(in_array($game->id, auth()->user()->owned_games))
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" style="margin-top:10px;margin-down:0px;font-size: 20px ">開始遊玩</button>
            </div>
        @else
        <div class="d-flex justify-content-end">
            <p style="font-size: 20px; padding-right:20px;padding-top:10px">價格 : {{$game->price}}</p>
        </div>
        <div class="d-flex justify-content-end">
            <form action="{{ route('games.addToCart', ['gameId' => $game->id]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-primary">新增遊戲到購物車</button>
            </form>
        </div>
        @endif
        @endif
        
        <p class="my-5 mx-4" style="font-size: 20px">
            {!! nl2br($game->content) !!}
        </p >
        <div class="align-items-center mx-4" style="margin-bottom:20px ">
            <div class="font-weight-normal d-md-none" style="font-size: 20px ">發行商:{{ $game->user->name }}</div>
            <div class="font-weight-normal d-md-none" style="font-size: 20px ">發行時間:{{ $game->created_at }}</div>
        </div>
        <div style="font-size: 20px " >
            <div class="d-flex justify-content-between">
                <div>
                    <div>
                        總留言數：{{ $totalComments }}
                    </div>
                    <div>
                        總讚數：{{ $totalLikes }}
                    </div>
                    <div>
                        總倒讚數：{{ $totalDislikes }}
                    </div>
                </div>
                <div>
                    <a href="{{ route('root') }}" style="font-size: 20px">回熱門遊戲列表</a>
                </div>
            </div>
        </div>
        <hr>
        @foreach ($game->comments as $comment)
        <div class="d-flex justify-content-between">
            <div class="col-md-10 align-self-start d-none d-md-block">
                <div style="font-size: 20px">{{ $comment->content }}</div>
            </div>
            <div class="col-md-1.5 d-flex flex-column align-items-start d-none d-md-block">
                <div>
                    <!-- 留言者信息 -->
                    <div style="margin-right: 10px;font-size:20px">{{ $comment->user->name }}</div>
                    <div style="margin-right: 10px;font-size:20px">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                <div class="d-flex justify-content-end">
                    @if ($comment->like_type === 1)
                        <span><i class="bi bi-hand-thumbs-up" style="font-size: 40px"></i></span>
                    @elseif ($comment->like_type === 2)
                        <span><i class="bi bi-hand-thumbs-down" style="font-size: 40px"></i></span>
                    @endif
                    <!-- 删除按钮 -->
                    @if(auth()->check() && auth()->user()->id===$comment->user_id)
                        <form action="{{ route('comments.destroy', ['gameId' => $game->id, 'comment' => $comment->id]) }}" method="post" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">删除</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <!-- 手機板 -->
        <div class="d-md-none">
            <div class="col-md-10 align-self-start">
                <div style="font-size: 20px">{{ $comment->content }}</div>
            </div>
            <br>
            <div class=" d-flex  justify-content-between ">
                <div>
                    <!-- 留言者信息 -->
                    <div style="margin-right: 10px;font-size:20px">by : {{ $comment->user->name }}</div>
                    <div style="margin-right: 10px;font-size:20px">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                <div class="d-flex justify-content-end">
                    @if ($comment->like_type === 1)
                        <span><i class="bi bi-hand-thumbs-up" style="font-size: 40px"></i></span>
                    @elseif ($comment->like_type === 2)
                        <span><i class="bi bi-hand-thumbs-down" style="font-size: 40px"></i></span>
                    @endif
                    <!-- 删除按钮 -->
                    @if(auth()->check() && auth()->user()->id===$comment->user_id)
                        <form action="{{ route('comments.destroy', ['gameId' => $game->id, 'comment' => $comment->id]) }}" method="post" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">删除</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <br>
        <hr>
        @endforeach

        <!-- 添加留言表单 -->
        @if(auth()->check())
        <div  class="d-flex align-items-center justify-content-center my-4">
        <form action="{{ route('comments.store', ['gameId' => $game->id]) }}" method="post" onsubmit="return validateForm()">
            @csrf
            <div id="qq">
                <div>
                    <textarea name="content"  class="form-control" aria-label="With textarea" placeholder="請輸入評論" style="margin-right: 20px;font-size:20px"></textarea>
                </div>
                <div>
                    <input type="radio" name="like_type" value="1"><i class="bi bi-hand-thumbs-up" style="font-size:30px"></i></span>
                    <input type="radio" name="like_type" value="2"><i class="bi bi-hand-thumbs-down" style="font-size:30px"></i></span>
                </div>
                <div>
                    <button type="submit" class="rounded mx-4" style="font-size: 20px">提交評論</button>
                </div>
            </div>
        </form>
        @endif
        </div>
    </div>
    <script>
        function validateForm() {
            var likeType = document.querySelector('input[name="like_type"]:checked');
            if (!likeType) {
                alert("請選擇讚或倒讚選項！");
                return false; // 阻止表單提交
            }
            var content = document.querySelector('textarea[name="content"]').value;
            if (content.trim() === '') {
                alert("請填寫留言內容");
                return false;
            }
            return true; // 允許表單提交
        }
        function confirmDelete() {
        return confirm("確定要刪除這項評論嗎？");
    }
    </script>
    <style>
        @media screen and (min-width:720px) {
            #qq{
                display: flex;
            }
        }
        @media screen and (max-width:1280px) {
        }
    </style>
@endsection
