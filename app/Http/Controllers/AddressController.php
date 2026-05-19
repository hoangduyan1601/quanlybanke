<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaChiKhachHang;
use App\Models\KhachHang;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        if (!$khachHang) return redirect('/')->with('error', 'Không tìm thấy thông tin khách hàng.');

        $addresses = DiaChiKhachHang::where('MaKH', $khachHang->MaKH)->get();
        return view('home.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'HoTenNguoiNhan' => 'required|string|max:255',
            'SDTNguoiNhan' => 'required|string|max:20',
            'DiaChiChiTiet' => 'required|string|max:255',
            'PhuongXa' => 'required|string|max:100',
            'QuanHuyen' => 'required|string|max:100',
            'TinhThanh' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();

        $isFirst = DiaChiKhachHang::where('MaKH', $khachHang->MaKH)->count() == 0;

        DiaChiKhachHang::create([
            'MaKH' => $khachHang->MaKH,
            'HoTenNguoiNhan' => $request->HoTenNguoiNhan,
            'SDTNguoiNhan' => $request->SDTNguoiNhan,
            'DiaChiChiTiet' => $request->DiaChiChiTiet,
            'PhuongXa' => $request->PhuongXa,
            'QuanHuyen' => $request->QuanHuyen,
            'TinhThanh' => $request->TinhThanh,
            'MacDinh' => $isFirst ? 1 : 0
        ]);

        return back()->with('success', 'Đã thêm địa chỉ mới.');
    }

    public function setDefault($id)
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        $address = DiaChiKhachHang::where('MaDC', $id)->where('MaKH', $khachHang->MaKH)->firstOrFail();
        
        DiaChiKhachHang::where('MaKH', $khachHang->MaKH)->update(['MacDinh' => 0]);
        $address->update(['MacDinh' => 1]);

        return back()->with('success', 'Đã đặt địa chỉ mặc định.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        $address = DiaChiKhachHang::where('MaDC', $id)->where('MaKH', $khachHang->MaKH)->firstOrFail();
        
        if ($address->MacDinh) {
            return back()->with('error', 'Không thể xóa địa chỉ mặc định.');
        }

        $address->delete();
        return back()->with('success', 'Đã xóa địa chỉ.');
    }
}
