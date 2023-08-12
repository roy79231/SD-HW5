@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex">
        <h1 class="me-auto">遊戲總覽</h1>
        @can('admin')
        <a href="{{ route('games.create') }}" style="font-size: 30px">新增遊戲</a>
        @elsecan('manager')
        <a href="{{ route('games.create') }}" style="font-size: 30px">新增遊戲</a>
        @endcan
    </div>
    <select name="sort" id="sort" onchange="submitForm()">
        <option value="default" @if($sort === 'default') selected @endif>預設排序</option>
        <option value="time" @if($sort === 'time') selected @endif>按時間排序(最新)</option>
        <option value="timeReverse" @if($sort === 'timeReverse') selected @endif>按時間排序(最舊)</option>
        <option value="comments" @if($sort === 'comments') selected @endif>按留言數排序(由高到低)</option>
        <option value="commentsReverse" @if($sort === 'commentsReverse') selected @endif>按留言數排序(由低到高)</option>
        <option value="price" @if($sort === 'price') selected @endif>按價格排序(由高到低)</option>
        <option value="priceReverse" @if($sort === 'priceReverse') selected @endif>按價格排序(由低到高)</option>
    </select>
    <div class="list-group">
        @foreach($games as $game)
        <div class="qwer">
            <div class="list-group-item list-group-item-action m-1">
                <div class="d-flex justify-content-between ">
                    <!-- 標題 -->
                    <h2 class="font-bold text-lg" style="margin-bottom: 20px ">
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
                    <p class="d-none d-md-block" style="font-size: 20px">{{$game->created_at}} 由 {{$game->user->name}} 發行</p>
                </div>
            </div>

            @can('admin')
            <div class="d-flex justify-content-end">
                <a href="{{route('games.edit',['game'=>$game->id])}}" class="btn btn-outline-primary" style="margin-right:10px">編輯</a>
                <form action="{{route('games.destroy',$game)}}" method="post"  onsubmit="return confirmDelete()">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger">刪除</button>
                </form>
            </div>
            @endcan
            @can('manager')
            <div class="d-flex justify-content-end">
                <a href="{{route('games.edit',['game'=>$game->id])}}" class="btn btn-outline-primary" style="margin-right:10px" >編輯</a>
                <form action="{{route('games.destroy',$game)}}" method="post" onsubmit="return confirmDelete()">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger">刪除</button>
                </form>
            </div>
            @endcan
        </div>
        @endforeach
    </div>
    {{$games->links()}}
    </div>
</div>
<script>
    function submitForm() {
        // 獲取選擇的值
        const selectedValue = document.getElementById('sort').value;
        
        // 根據選擇的值進行相應的操作
        if (selectedValue === 'default') {
            // 如果選擇的是預設排序，則直接跳轉到 index 頁面
            window.location.href = "{{ route('all') }}";
        } else {
            // 否則根據選擇的值提交表單
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('all') }}";

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sort';
            input.value = selectedValue;

            form.appendChild(input);
            document.body.appendChild(form);

            form.submit();
        }
    }
    function confirmDelete() {
        return confirm("確定要刪除這個遊戲嗎？");
    }
</script>
@endsection
