<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;   
use App\Models\Brand;      
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
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
            
            $request->validate([
                'barcode' => 'required|unique:products,barcode',
            ], [
                'barcode.unique' => 'Mã vạch này đã tồn tại trong hệ thống! Vui lòng kiểm tra lại.',
            ]);

            $image = null;
            if($request->hasFile('image')){
             $image = $request->file('image')->store('products', 'public');
            }
            
            $product = Product::create([
                'category_id'=>$request->category_id,
                'brand_id'=>$request->brand_id,
                'barcode'=>$request->barcode,
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

                    // XỬ LÝ BIẾN THỂ NẾU CÓ
                if ($request->has('variants')) {
                    foreach ($request->variants as $var) {
                        // Nếu người dùng có nhập tên biến thể thì mới lưu
                        if (!empty($var['name'])) {
                            $variant = $product->variants()->create([
                                'name' => $var['name'],
                                'barcode' => $var['barcode'] ?? null,
                                'price' => $var['price'] ?? 0,
                                'stock_quantity' => $var['stock_quantity'] ?? 0,
                                'status' => 1
                            ]);

                            // Ghi nhận kho cho biến thể
                            ProductStock::create([
                                'product_id' => $product->id,
                                'variant_id' => $variant->id,
                                'quantity' => $var['stock_quantity'] ?? 0
                            ]);
                        }
                    }
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
        $product->load('variants');
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
                'note' => 'Cập nhật tăng tồn kho gốc',
                'created_by' => auth()->id() ?? 1
            ]);
        } elseif ($diff < 0) {
            InventoryLog::create([
                'product_id' => $product->id,
                'quantity' => abs($diff), // abs() để lấy số dương cho quantity
                'type' => 'export',
                'note' => 'Cập nhật giảm tồn kho gốc',
                'created_by' => auth()->id() ?? 1
            ]);
        }
        // Xử lý Ảnh 
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

        // XỬ LÝ CẬP NHẬT BIẾN THỂ 
        if ($request->has('variants')) {
            $keptVariantIds = [];

            foreach ($request->variants as $var) {
                if (!empty($var['name'])) {
                    if (isset($var['id'])) {
                        // Cập nhật biến thể cũ
                        $variant = ProductVariant::find($var['id']);
                        if($variant) {
                            $variant->update([
                                'name' => $var['name'],
                                'barcode' => $var['barcode'] ?? null,
                                'price' => $var['price'] ?? 0,
                                'stock_quantity' => $var['stock_quantity'] ?? 0,
                            ]);
                            $keptVariantIds[] = $variant->id;

                            // Cập nhật kho của biến thể
                            ProductStock::updateOrCreate(
                                ['product_id' => $product->id, 'variant_id' => $variant->id],
                                ['quantity' => $var['stock_quantity'] ?? 0]
                            );
                        }
                    } else {
                        // Tạo biến thể mới
                        $newVariant = $product->variants()->create([
                            'name' => $var['name'],
                            'barcode' => $var['barcode'] ?? null,
                            'price' => $var['price'] ?? 0,
                            'stock_quantity' => $var['stock_quantity'] ?? 0,
                            'status' => 1
                        ]);
                        $keptVariantIds[] = $newVariant->id;
                        
                        ProductStock::create([
                            'product_id' => $product->id,
                            'variant_id' => $newVariant->id,
                            'quantity' => $var['stock_quantity'] ?? 0
                        ]);
                    }
                }
            }
           
            $product->variants()->whereNotIn('id', $keptVariantIds)->update(['status' => 0]);
        } else {
            
            $product->variants()->update(['status' => 0]);
        }

        return redirect()->route('admin.products.index', ['page' => $request->page])
                     ->with('success', 'Cập nhật sản phẩm thành công!');
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
