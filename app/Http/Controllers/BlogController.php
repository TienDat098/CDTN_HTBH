<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {           
        $blogs = Blog::where('status', 1)->latest()->paginate(9);
        return view('blogs.index', compact('blogs'));
    }
    public function show($slug)
    {
        // Tìm bài viết theo slug và phải ở trạng thái đang hiển thị (status = 1)
        $blog = Blog::where('slug', $slug)->where('status', 1)->firstOrFail();
        
        return view('blogs.show', compact('blog'));
    }
}
