@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="font-thin text-4xl">購物車</h1>
    @if ($games->isEmpty())
        <p>購物車是空的</p>
    @else
    @if(auth()->check())
    <form action="{{ route('shoplist.clean') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-primary">清空購物車</button>
    </form>
    @endif
    <div class="list-group">
        @foreach($games as $game)
        <div class="qwer">
            <div class="list-group-item list-group-item-action m-1">
                <div class="d-flex justify-content-between ">
                    <!-- 標題 -->
                    <h2 class="font-bold text-lg" style="margin-bottom: 20px ">
                        <a href="{{route('games.show',$game)}}">{{$game->title}}</a>
                        <p style="font-size: 15px">#{{$game->tag}}</p>
                    </h2>
                    <!-- 圖片 -->
                    <div class="justify-content-end">
                        <img src="{{ asset('storage/' . $game->image) }}"  style="width: 200px;" alt="遊戲圖片">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <!-- 價格和發行 -->
                    <p class="" style="font-size: 15px">
                        價格:{{$game->price}}
                    </p>
                    <p class="" style="font-size: 15px">{{$game->created_at}} 由 {{$game->user->name}} 發行</p>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <form action="{{ route('shoplist.destroy', $game->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger">刪除</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-end">
        <form action="{{ route('shoplist.buy') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">購買</button>
        </form>
    </div>
    @endif
</div>
@endsection