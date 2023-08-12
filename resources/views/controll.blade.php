@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex">
        <h1 class="me-auto">管理頁</h1>
    </div>
    @foreach($users as $user)
    <hr>
    <div class="list-group-item list-group-item-action m-1">
        <div>
            <div class="list-group list-group-horizontal justify-content-between" style="margin-bottom: 20px ">
                <div class="col-md-3">
                    <p style="font-size: 15px" class="list-group-item">ID : {{$user->id}}</p>
                </div >
                <div class="col-md-3">
                    <p style="font-size: 15px" class="list-group-item">Name : {{$user->name}}</p>
                </div>
                <div class="col-md-3">
                    <p style="font-size: 15px" class="list-group-item">Email : {{$user->email}}</p>
                </div>
                <div class="col-md-3">
                    <p style="font-size: 15px" class="list-group-item">Role : {{$user->role}}</p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            @if($user->id !==1)
            <div>
                <form action="{{ route('users.set_admin', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit">設為admin</button>
                </form>
            </div>
            <div>
                <form action="{{ route('users.set_manager', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit">設為manager</button>
                </form>
            </div>
            <div>
                <form action="{{ route('users.set_user', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit">設為user</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
<style>
    @media screen and (min-width:720px) {

    }
    @media screen and (max-width:720px) {
    }
</style>
@endsection
