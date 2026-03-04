<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:brands,name','logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048','description' => 'nullable|string']);
         $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }
        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), 
            'logo' => $logoPath,
            'description' => $request->description,
            'status' => $request->status ?? 1
        ]);
         return redirect()->route('admin.brands.index')
                         ->with('success', 'Thêm thương hiệu thành công!');
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
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Brand $brand)
    {
        $request->validate(['name' => 'required|unique:brands,name,' . $brand->id,'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048','description' => 'nullable|string']);
        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
        // Xóa logo cũ
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $logoPath = $request->file('logo')->store('brands', 'public');
    }
        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logoPath,
            'description' => $request->description,
            'status' => $request->status
        ]);
         return redirect()->route('admin.brands.index')
                         ->with('success', 'Cập nhật thương hiệu thành công!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
        {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }
        $brand->delete();
        return redirect()->route('admin.brands.index')
                         ->with('success', 'Xóa thương hiệu thành công!');
    }
    
}
