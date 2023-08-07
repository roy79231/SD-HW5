@extends('layouts.app')

@section('content')
<div class="container">
    @foreach($users as $user)
    <div class="list-group-item list-group-item-action m-1">
        <div class="d-flex justify-content-between ">
            <h2 class="font-bold text-lg" style="margin-bottom: 20px ">
                <p style="font-size: 15px">{{$user->id}}</p>
                <p style="font-size: 15px">{{$user->name}}</p>
                <p style="font-size: 15px">{{$user->email}}</p>
                <p style="font-size: 15px">{{$user->role}}</p>
            </h2>
            @if($user->id !==1)
            <div>
                <form action="{{ route('users.set_admin', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit">設為boss</button>
                </form>
            </div>
            <div>
                <form action="{{ route('users.set_manager', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit">設為管理员</button>
                </form>
            </div>
            <div>
                <form action="{{ route('users.set_user', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit">設為普通用户</button>
                </form>
            </div>
            @endif
    </div>
    @endforeach
</div>
@endsection
