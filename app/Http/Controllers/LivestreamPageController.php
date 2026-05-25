<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livestream;
class LivestreamPageController extends Controller
{
    public function index()
    {
        $livestream = Livestream::with('products')
            ->where('is_active', true)
            ->latest()
            ->first();

        return view('livestream.index', compact('livestream'));
    }
}
