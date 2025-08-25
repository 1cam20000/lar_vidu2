<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CartController extends Controller
{
    use AuthorizesRequests;
    private function getCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = $this->getCart()->load('items.product');
        return view('user.cart.index', compact('cart'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request, Product $product)
    {
        $cart = $this->getCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $request->input('quantity', 1));
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $request->input('quantity', 1),
            ]);
        }

        return redirect()->route('user.cart.index')->with('success', 'Đã thêm vào giỏ hàng');
    }

    // Cập nhật số lượng
    public function update(Request $request, CartItem $item)
    {
        $this->authorize('update', $item->cart);

        $item->update(['quantity' => $request->input('quantity', 1)]);
        return back()->with('success', 'Cập nhật giỏ hàng thành công');
    }

    public function remove(CartItem $item)
    {
        $this->authorize('delete', $item->cart);

        $item->delete();
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    // Xóa toàn bộ giỏ
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        return back()->with('success', 'Đã làm trống giỏ hàng');
    }
}
