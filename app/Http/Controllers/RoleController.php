<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RoleController extends Controller
{
    //
    public function index(){
        $users = User::all();
        return view('controll',['users'=>$users]);
    }
    public function setAdmin(User $user)
    {
        $user->update(['role' => User::ROLE_ADMIN]);
        return redirect()->back()->with('notice', '用戶已更新為admin');
    }
    public function setManager(User $user)
    {
        $user->update(['role' => User::ROLE_MANAGER]);
        return redirect()->back()->with('notice', '用戶已更新為manager');
    }

    public function setUser(User $user)
    {
        $user->update(['role' => User::ROLE_USER]);
        return redirect()->back()->with('notice', '用戶已更新為user');
    }

}
