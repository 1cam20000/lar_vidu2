<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity'    => 'required|integer|min:0',
            'price'       => 'required', // chuẩn hoá bên dưới
            'features'    => 'nullable', // nhận chuỗi, mảng, ... rồi chuẩn hoá
            'image'       => 'nullable|image|max:2048',
        ]);

        // Chuẩn hoá price: chấp nhận "1.000.000" hoặc "1,000,000"
        $data['price'] = (int) preg_replace('/[^\d]/', '', (string) $data['price']);

        // Chuẩn hoá features → JSON hợp lệ (mảng string) để không vướng CHECK JSON_VALID
        $data['features'] = $this->normalizeFeatures($request->input('features'));

        // Ảnh
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public'); // storage/app/public/products/...
            $data['image'] = 'storage/' . $path; // để asset() dùng được
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity'    => 'required|integer|min:0',
            'price'       => 'required',
            'features'    => 'nullable',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data['price']    = (int) preg_replace('/[^\d]/', '', (string) $data['price']);
        $data['features'] = $this->normalizeFeatures($request->input('features'));

        // Ảnh mới (nếu có)
        if ($request->hasFile('image')) {
            if ($product->image && str_starts_with($product->image, 'storage/')) {
                $old = Str::replaceFirst('storage/', '', $product->image);
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = 'storage/' . $path;
        }

        // cập nhật trực tiếp model để events/casts hoạt động
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && str_starts_with($product->image, 'storage/')) {
            $old = Str::replaceFirst('storage/', '', $product->image);
            Storage::disk('public')->delete($old);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công.');
    }

    /**
     * Chuyển mọi input features về JSON hợp lệ (mảng string).
     * - Nếu người dùng nhập "lụa, cotton, freesize" → ["lụa","cotton","freesize"]
     * - Nếu input đã là mảng → dùng luôn (trim từng phần tử)
     * - Nếu trống → null (để DB nhận null)
     */
    private function normalizeFeatures($raw)
    {
        if (is_array($raw)) {
            $arr = array_values(array_filter(array_map(fn($v) => trim((string)$v), $raw), fn($v) => $v !== ''));
            return $arr ? json_encode($arr, JSON_UNESCAPED_UNICODE) : null;
        }

        $str = trim((string) $raw);
        if ($str === '') {
            return null;
        }

        // tách theo dấu phẩy hoặc xuống dòng
        $parts = preg_split('/[\n,]+/u', $str);
        $parts = array_values(array_filter(array_map(fn($v) => trim($v), $parts), fn($v) => $v !== ''));

        return $parts ? json_encode($parts, JSON_UNESCAPED_UNICODE) : null;
    }
}
