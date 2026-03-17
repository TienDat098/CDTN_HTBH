<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name', 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048']);

        if ($request->hasFile('image')) {
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('images/categories'), $imageName);
            } else {
                $imageName = null;
            }
    Category::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name), 
        'status' => $request->status ?? 1,
        'image' => $imageName
    ]);

    return redirect()->route('admin.categories.index')->with('success', 'Thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
       return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|unique:categories,name,' . $category->id,'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048']);

        $imageName = $category->image;

        if ($request->hasFile('image')) {

            if ($category->image && file_exists(public_path('images/categories/'.$category->image))) {
                unlink(public_path('images/categories/'.$category->image));
            }
            $imageName = time().'_'.$request->image->getClientOriginalName();
            $request->image->move(public_path('images/categories'), $imageName);
            
        }

                $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'status' => $request->status,
                'image' => $imageName
            ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công!');
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Xóa thành công!');
    }
}
