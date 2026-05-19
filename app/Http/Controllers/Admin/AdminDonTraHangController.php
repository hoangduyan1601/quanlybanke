<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonTraHang;
use Illuminate\Http\Request;

class AdminDonTraHangController extends Controller
{
    public function index(Request $request)
    {
        $query = DonTraHang::with(['donhang.khachHang']);

        if ($request->has('status') && $request->status != '') {
            $query->where('TrangThaiTra', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('MaTraHang', 'LIKE', "%$search%")
                  ->orWhere('MaDH', 'LIKE', "%$search%")
                  ->orWhereHas('donhang.khachHang', function($q2) use ($search) {
                      $q2->where('HoTen', 'LIKE', "%$search%");
                  });
            });
        }

        $list = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.dontrahang.index', compact('list'));
    }

    public function updateStatus(Request $request, $id)
    {
        $item = DonTraHang::with('donhang.chiTietDonHangs')->findOrFail($id);
        $oldStatus = $item->TrangThaiTra;
        $item->TrangThaiTra = $request->status;
        $item->save();

        if ($request->status === 'DaHoanTien' && $oldStatus !== 'DaHoanTien') {
            $order = $item->donhang;
            $order->TrangThaiThanhToan = 'DaHoanTien';
            $order->save();

            // Hoàn lại số lượng tồn kho do khách đã trả hàng
            foreach ($order->chiTietDonHangs as $ct) {
                if ($ct->sanPham) {
                    $ct->sanPham->increment('SoLuong', $ct->SoLuong);
                    if ($ct->sanPham->SoLuongDaBan >= $ct->SoLuong) {
                        $ct->sanPham->decrement('SoLuongDaBan', $ct->SoLuong);
                    }
                }
                if ($ct->MaVariant) {
                    $variant = \App\Models\SanPhamVariant::find($ct->MaVariant);
                    if ($variant) {
                        $variant->increment('SoLuongTon', $ct->SoLuong);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái đổi trả thành công!');
    }

    public function destroy($id)
    {
        $item = DonTraHang::findOrFail($id);
        $item->delete();
        return redirect()->route('admin.dontrahang.index')->with('success', 'Xóa yêu cầu đổi trả thành công!');
    }
}
