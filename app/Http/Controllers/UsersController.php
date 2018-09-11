<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    public function __construct() {
        #登录以外用户能访问的操作
        $this->middleware('auth', [
            'except' => ['create', 'store', 'index']
        ]);
        
        #未登录才能访问的操作
        $this->middleware('guest', [
            'only' => ['create'],
        ]);
    }

    #用户列表
    public function index() {
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

    #用户信息
    public function show(User $user) {
        //用户授权--只有本用户才有访问改方法的权限
        $this->authorize('update', $user);
        return view('users.show', compact('user'));
    }

    #用户注册
    public function create() {
        return view('users.create');
    }

    #注册
    public function store(Request $request) {
        //字段验证
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|min:6|confirmed',
            'email' => 'required|email|unique:users|max:255',
        ]);

        //注册
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        //注册成功，跳转后提示信息
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    #编辑资料
    public function edit(User $user) {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    #修改资料
    public function update(User $user, Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|min:6|confirmed'
        ]);

        $this->authorize('update', $user);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = $request->password;
        }
        $user->update($data);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }

    #删除用户
    public function destroy(User $user) {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '用户删除成功！');
        return redirect()->route('users.index');
    }
}
