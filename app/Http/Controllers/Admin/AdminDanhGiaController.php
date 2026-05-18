<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use Illuminate\Http\Request;

class AdminDanhGiaController extends Controller
{
    public function index()
    {
        $list = DanhGia::with(['sanpham', 'khachhang'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.danhgia.index', compact('list'));
    }

    public function destroy($id)
    {
        $item = DanhGia::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.danhgia.index')->with('success', 'Xóa đánh giá thành công!');
    }
}
