@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex">
        <h1 class="me-auto">熱門TOP5</h1>
        @can('admin')
        <a href="{{ route('games.create') }}" style="font-size: 30px">新增遊戲</a>
        @elsecan('manager')
        <a href="{{ route('games.create') }}" style="font-size: 30px">新增遊戲</a>
        @endcan
        
    </div>
    <div class="list-group">
        @foreach($games as $game)
        <div class="qwer">
            <div class="list-group-item list-group-item-action m-1">
                <div class="d-flex justify-content-between ">
                    <!-- 標題 -->
                    <h2 class="font-bold text-lg" style="margin-bottom: 30px ">
                        <a href="{{route('games.show',$game)}}">{{$game->title}}</a>
                        <br>
                        <br>
                        <p style="font-size: 20px">#{{$game->tag}}</p>
                    </h2>
                    <!-- 圖片 -->
                    <div class="justify-content-end">
                        <img src="{{ asset('storage/' . $game->image) }}"  style="width: 200px;" alt="遊戲圖片">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <!-- 價格和發行 -->
                    <p class="" style="font-size: 20px">
                        價格:{{$game->price}}
                    </p>
                    <p class="d-none d-md-block" style="font-size: 20px" >{{$game->created_at}} 由 {{$game->user->name}} 發行</p>
                </div>
            </div>
            @can('admin')
            <div class="d-flex justify-content-end">
                <a href="{{route('games.edit',['game'=>$game->id])}}" class="btn btn-outline-primary" style="margin-right:10px ">編輯</a>
                <form action="{{route('games.destroy',$game)}}" method="post" onsubmit="return confirmDelete()">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger">刪除</button>
                </form>
            </div>
            @endcan
            @can('manager')
            <div class="d-flex justify-content-end">
                <a href="{{route('games.edit',['game'=>$game->id])}}" class="btn btn-outline-primary" style="margin-right:10px ">編輯</a>
                <form action="{{route('games.destroy',$game)}}" method="post" onsubmit="return confirmDelete()">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger" >刪除</button>
                </form>
            </div>
            @endcan
        </div>
        @endforeach
    </div>
</div>
<script>
    function confirmDelete() {
        return confirm("確定要刪除這個遊戲嗎？");
    }
</script>
@endsection
