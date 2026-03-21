<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant; 

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại!']);
        }

        //  Nhận diện Biến thể và Tính Giá
        $variant_id = $request->variant_id ?? null;
        $price = $product->sell_price; 
        $variant_name = null;

        if ($variant_id) {
            $variant = ProductVariant::find($variant_id);
            if ($variant) {
                $price = $variant->price; 
                $variant_name = $variant->name; 
            } else {
                $variant_id = null; 
            }
        }

        
        $cartKey = $product->id . '_' . ($variant_id ? $variant_id : '0');

        $cart = session()->get('cart', []);
        $qty = $request->quantity ? (int)$request->quantity : 1;
        //  Xử lý Thêm vào giỏ
        if (isset($cart[$cartKey])) {
            // Cộng dồn số lượng khách vừa chọn vào số lượng đã có
            $cart[$cartKey]['quantity'] += $qty;
        } else {
            // Thêm mới với số lượng khách chọn
            $cart[$cartKey] = [
                'product_id' => $product->id, 
                'name' => $product->name,
                'price' => $price,
                'quantity' => $qty, 
                'image' => $product->thumbnail,
                'slug' => $product->slug,
                'variant_id' => $variant_id,
                'variant_name' => $variant_name 
            ];
        }

        session()->put('cart', $cart);
        $totalItems = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'cart_count' => $totalItems,
            'message' => 'Đã thêm ' . $product->name . ($variant_name ? ' (' . $variant_name . ')' : '') . ' vào giỏ!'
        ]);
    }

    //  Hiển thị trang chi tiết giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }


    public function update(Request $request)
    {
       
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
                session()->flash('success', 'Đã cập nhật số lượng!');
            }
        }
        return redirect()->back();
    }

    // 3. Xóa sản phẩm khỏi giỏ
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
        }
        return redirect()->back();
    }
}