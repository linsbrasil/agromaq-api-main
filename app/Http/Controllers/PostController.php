<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostModel;


class PostController extends Controller
{
    public static function index()
    {
        $posts = PostModel::all();
        return $posts;
    }
    public static function store($idpost, $title)
    {
        $post = PostModel::create([
            'idpost' => $idpost,
            'title' => $title
        ]);
        return $post;
    }
}
