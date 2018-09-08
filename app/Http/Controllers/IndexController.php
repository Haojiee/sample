<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class IndexController extends Controller {
    public function home() {
        return view('index.home');
    }

    public function help() {
        return view('index.help');
    }

    public function about() {
        return view('index.about');
    }
}