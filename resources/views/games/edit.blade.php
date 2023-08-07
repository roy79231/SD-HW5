@extends('layouts.app')

@section('content')
<div class="container">
<h1 class="font-thin text-4xl">編輯遊戲資訊</h1>
@if($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{route('games.update',$game)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <div class="field my-2">
        <p style="font-size: 30px">名稱<p>
        <input type="text" value="{{$game->title}}" name="title" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" style="font-size: 20px"/>
    </div>
    <div class="field my-2">
        <p style="font-size: 30px">標籤<p>
        <input type="text" value="{{$game->tag}}" name="tag" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" style="font-size: 20px"/>
    </div>
    <div class="field my-2">
        <p style="font-size: 30px">當前圖片<p>
        <img src="{{ asset('storage/' . $game->image) }}" alt="遊戲圖片" class="img-fluid">
        <p style="font-size: 30px">更換圖片<p>
        <input type="file" name="image">
    </div>        
    <div class="field my-2">
        <p style="font-size: 30px">內文<p>
        <textarea name="content" cols="30" rows="10" class="form-control" id="exampleFormControlTextarea1" rows="3" style="font-size: 20px">{{$game->content}}</textarea>
    </div>
    <div class="field my-2">
        <p style="font-size: 30px">價格<p>
        <input type="text" value="{{$game->price}}" name="price" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" style="font-size: 20px"/>
    </div>
    <div class="actions">
        <button type="submit" class="btn btn-primary btn-lg">編輯完成</button>
    </div>
</form>
</div>
@endsection