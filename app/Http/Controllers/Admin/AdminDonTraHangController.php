<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonTraHang;
use Illuminate\Http\Request;

class AdminDonTraHangController extends Controller
{
    public function index()
    {
        $list = DonTraHang::with('donhang.khachHang')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.dontrahang.index', compact('list'));
    }

    public function updateStatus(Request $request, $id)
    {
        $item = DonTraHang::findOrFail($id);
        $item->TrangThaiDHTra = $request->status;
        $item->save();

        // Nếu hoàn tiền thành công, có thể cập nhật trạng thái thanh toán của đơn hàng gốc
        if ($request->status === 'DaHoanTien') {
            $order = $item->donhang;
            $order->TrangThaiThanhToan = 'DaHoanTien';
            $order->save();
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
