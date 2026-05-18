<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhaSanXuat;
use Illuminate\Http\Request;

class AdminNhaSanXuatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = NhaSanXuat::query();

        if ($search) {
            $query->where('TenNXB', 'LIKE', "%{$search}%")
                  ->orWhere('SDT', 'LIKE', "%{$search}%")
                  ->orWhere('Email', 'LIKE', "%{$search}%");
        }

        $list = $query->paginate(10)->withQueryString();
        return view('admin.nhasanxuat.index', compact('list', 'search'));
    }

    public function create()
    {
        return view('admin.nhasanxuat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenNXB' => 'required',
        ]);

        NhaSanXuat::create($request->all());

        return redirect()->route('admin.nxb.index')->with('success', 'Thêm nhà sản xuất thành công!');
    }

    public function edit($id)
    {
        $nxb = NhaSanXuat::findOrFail($id);
        return view('admin.nhasanxuat.edit', compact('nxb'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenNXB' => 'required',
        ]);

        $nxb = NhaSanXuat::findOrFail($id);
        $nxb->update($request->all());

        return redirect()->route('admin.nxb.index')->with('success', 'Cập nhật nhà sản xuất thành công!');
    }

    public function destroy($id)
    {
        try {
            $nxb = NhaSanXuat::findOrFail($id);

            if ($nxb->sanphams()->exists()) {
                return redirect()->route('admin.nxb.index')->with('error', 'Không thể xóa nhà sản xuất này vì vẫn còn sản phẩm thuộc về họ!');
            }

            $nxb->delete();
            return redirect()->route('admin.nxb.index')->with('success', 'Xóa nhà sản xuất thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.nxb.index')->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}
