<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $logs = InventoryLog::with('product', 'user')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.inventory.index', compact('logs'));
    }
}
