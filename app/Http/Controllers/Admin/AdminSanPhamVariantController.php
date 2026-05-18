<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\SanPhamVariant;
use Illuminate\Http\Request;

class AdminSanPhamVariantController extends Controller
{
    public function index($productId)
    {
        $product = SanPham::with('variants')->findOrFail($productId);
        return view('admin.sanpham.variants.index', compact('product'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'SKU' => 'required|unique:sanpham_variants,SKU',
            'SoLuongTon' => 'required|integer|min:0',
            'GiaNiemYet' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['MaSP'] = $productId;
        
        SanPhamVariant::create($data);

        return redirect()->back()->with('success', 'Thêm biến thể thành công!');
    }

    public function update(Request $request, $id)
    {
        $variant = SanPhamVariant::findOrFail($id);
        
        $request->validate([
            'SKU' => 'required|unique:sanpham_variants,SKU,' . $id . ',MaVariant',
            'SoLuongTon' => 'required|integer|min:0',
        ]);

        $variant->update($request->all());

        return redirect()->back()->with('success', 'Cập nhật biến thể thành công!');
    }

    public function destroy($id)
    {
        $variant = SanPhamVariant::findOrFail($id);
        $variant->delete();
        return redirect()->back()->with('success', 'Xóa biến thể thành công!');
    }
}
