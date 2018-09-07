<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    #用户注册
    public function create() {
        return view('user/create');
    }

    #用户登录
    public function login() {
        return view('user/login');
    }
}
