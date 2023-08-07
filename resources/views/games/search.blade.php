@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex">
        <h1 class="me-auto">遊戲列表</h1>
    </div>
    <div class="list-group">
        @if($games->isEmpty())
            沒找到
        @else
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
            @can('admin')
            <div class="d-flex justify-content-end">
                <a href="{{route('games.edit',['game'=>$game->id])}}" class="btn btn-outline-primary" style="margin-right:10px ">編輯</a>
                <form action="{{route('games.destroy',$game)}}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger">刪除</button>
                </form>
            </div>
            @endcan
            @can('manager')
            <div class="d-flex justify-content-end">
                <a href="{{route('games.edit',['game'=>$game->id])}}" class="btn btn-outline-primary" style="margin-right:10px ">編輯</a>
                <form action="{{route('games.destroy',$game)}}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger">刪除</button>
                </form>
            </div>
            @endcan
        @endforeach
        @endif
    </div>
    {{$games->links()}}
    </div>
    </div>
</div>
@endsection