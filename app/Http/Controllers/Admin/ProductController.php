<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;   
use App\Models\Brand;      
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\InventoryLog;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category','brand','stock'])
            ->orderBy('id','desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();

    return view('admin.products.create', compact('categories','brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $image = null;
            if($request->hasFile('image')){
            // Lưu ảnh vào thư mục storage/app/public/products
             $image = $request->file('image')->store('products', 'public');
            }
            $barcode = 'SP' . time();
            $product = Product::create([
                'category_id'=>$request->category_id,
                'brand_id'=>$request->brand_id,
                'barcode'=>$barcode,
                'name'=>$request->name,
                'slug'=>Str::slug($request->name),
                'import_price'=>$request->import_price,
                'sell_price'=>$request->sell_price,
                'unit'=>$request->unit,
                'description'=>$request->description,
                'status'=>$request->status
            ]);

            ProductStock::create([
                'product_id'=>$product->id,
                'quantity'=>$request->quantity
            ]);
            //ghi log nhập kho nếu có số lượng nhập vào
            if ($request->quantity > 0) {
            InventoryLog::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'type' => 'import', 
                'note' => 'Nhập kho lần đầu khi tạo sản phẩm',
                'created_by' => auth()->id() ?? 1 
            ]);
        }
            if ($image) {
                $product->images()->create([
                    'image_url' => $image,
                    'is_primary' => 1
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success','Thêm sản phẩm thành công');
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
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.edit', compact('product','categories','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Product $product)
    {
        
        $product->update([
            'category_id'=>$request->category_id,
            'brand_id'=>$request->brand_id,
            'name'=>$request->name,
            'slug'=>Str::slug($request->name),
            'import_price'=>$request->import_price,
            'sell_price'=>$request->sell_price,
            'unit'=>$request->unit,
            'description'=>$request->description,
            'status'=>$request->status
        ]);
        // Lấy số lượng cũ trước khi ghi đè
        $oldQuantity = $product->stock->quantity ?? 0;
        $newQuantity = $request->quantity;
        $diff = $newQuantity - $oldQuantity;
        $product->stock()->updateOrCreate(
            ['product_id' => $product->id], 
            ['quantity' => $newQuantity]
        );
        // Kiểm tra chênh lệch để ghi log Nhập hoặc Xuất
        if ($diff > 0) {
            InventoryLog::create([
                'product_id' => $product->id,
                'quantity' => $diff,
                'type' => 'import',
                'note' => 'Cập nhật tăng tồn kho thủ công',
                'created_by' => auth()->id() ?? 1
            ]);
        } elseif ($diff < 0) {
            InventoryLog::create([
                'product_id' => $product->id,
                'quantity' => abs($diff), // abs() để lấy số dương cho quantity
                'type' => 'export',
                'note' => 'Cập nhật giảm tồn kho thủ công',
                'created_by' => auth()->id() ?? 1
            ]);
        }
        // Xử lý Ảnh (Chỉ cập nhật nếu có chọn file mới)
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
        $primaryImage = $product->images()->where('is_primary', 1)->first();
        if ($primaryImage) {
            $primaryImage->update(['image_url' => $imagePath]);
        } else {
            // chua có thì tạo mới
            $product->images()->create([
                'image_url' => $imagePath,
                'is_primary' => 1
            ]);
        }
    }
        return redirect()->route('admin.products.index')
            ->with('success','Cập nhật sản phẩm thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success','Đã xóa sản phẩm');
    }
}
