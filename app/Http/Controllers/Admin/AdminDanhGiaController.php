<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use Illuminate\Http\Request;

class AdminDanhGiaController extends Controller
{
    public function index(Request $request)
    {
        $query = DanhGia::with(['sanpham', 'khachhang']);

        if ($request->has('rating') && $request->rating != '') {
            $query->where('SoSao', $request->rating);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('sanpham', function($q2) use ($search) {
                    $q2->where('TenSP', 'LIKE', "%$search%");
                })->orWhereHas('khachhang', function($q2) use ($search) {
                    $q2->where('HoTen', 'LIKE', "%$search%");
                })->orWhere('NoiDung', 'LIKE', "%$search%");
            });
        }

        $list = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.danhgia.index', compact('list'));
    }

    public function destroy($id)
    {
        $item = DanhGia::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.danhgia.index')->with('success', 'Xóa đánh giá thành công!');
    }
}
