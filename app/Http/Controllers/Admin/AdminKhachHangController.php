<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminKhachHangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = KhachHang::with('taiKhoan');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('HoTen', 'LIKE', "%{$search}%")
                  ->orWhere('Email', 'LIKE', "%{$search}%")
                  ->orWhere('SDT', 'LIKE', "%{$search}%");
            });
        }

        if ($fromDate) {
            $query->whereDate('NgayDangKy', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('NgayDangKy', '<=', $toDate);
        }

        $customers = $query->paginate(10)->withQueryString();
        $taiKhoans = TaiKhoan::where('VaiTro', 'KhachHang')->get();

        return view('admin.khachhang.index', compact('customers', 'taiKhoans', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'HoTen' => 'required',
            'Email' => 'nullable|email|unique:khachhang,Email',
            'TenDangNhap' => 'required|unique:taikhoan,TenDangNhap',
            'MatKhau' => 'required|min:6',
        ]);

        DB::beginTransaction();
        try {
            $taikhoan = TaiKhoan::create([
                'TenDangNhap' => $request->TenDangNhap,
                'MatKhau' => \Illuminate\Support\Facades\Hash::make($request->MatKhau),
                'VaiTro' => 'KhachHang',
                'TrangThai' => 1,
            ]);

            KhachHang::create([
                'HoTen' => $request->HoTen,
                'Email' => $request->Email,
                'SDT' => $request->SDT,
                'DiaChi' => $request->DiaChi,
                'MaTK' => $taikhoan->MaTK,
                'NgayDangKy' => now(),
            ]);

            DB::commit();
            return redirect()->route('admin.khachhang.index')->with('success', 'Thêm khách hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $customer = KhachHang::findOrFail($id);
        $request->validate([
            'HoTen' => 'required',
            'Email' => 'nullable|email|unique:khachhang,Email,' . $id . ',MaKH',
        ]);

        $customer->update($request->only(['HoTen', 'Email', 'SDT', 'DiaChi']));

        return redirect()->route('admin.khachhang.index')->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function destroy($id)
    {
        try {
            $customer = KhachHang::findOrFail($id);
            
            // Kiểm tra lịch sử mua hàng
            if ($customer->donHangs()->exists()) {
                return redirect()->route('admin.khachhang.index')->with('error', 'Không thể xóa khách hàng này vì đã có dữ liệu lịch sử đơn hàng!');
            }

            \Illuminate\Support\Facades\DB::beginTransaction();
            if ($customer->MaTK) {
                TaiKhoan::where('MaTK', $customer->MaTK)->delete();
            }
            $customer->delete();
            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.khachhang.index')->with('success', 'Xóa khách hàng thành công!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('admin.khachhang.index')->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    public function addresses($id)
    {
        $customer = KhachHang::with('diaChis')->findOrFail($id);
        return view('admin.khachhang.addresses', compact('customer'));
    }

    public function storeAddress(Request $request, $id)
    {
        $request->validate([
            'HoTenNguoiNhan' => 'required',
            'SDTNguoiNhan' => 'required',
            'DiaChiChiTiet' => 'required',
            'TinhThanh' => 'required',
            'QuanHuyen' => 'required',
            'PhuongXa' => 'required',
        ]);

        $kh = KhachHang::findOrFail($id);
        
        // Nếu chọn mặc định, bỏ mặc định các địa chỉ khác
        if ($request->MacDinh) {
            \App\Models\DiaChiKhachHang::where('MaKH', $id)->update(['MacDinh' => 0]);
        }

        \App\Models\DiaChiKhachHang::create([
            'MaKH' => $id,
            'HoTenNguoiNhan' => $request->HoTenNguoiNhan,
            'SDTNguoiNhan' => $request->SDTNguoiNhan,
            'DiaChiChiTiet' => $request->DiaChiChiTiet,
            'PhuongXa' => $request->PhuongXa,
            'QuanHuyen' => $request->QuanHuyen,
            'TinhThanh' => $request->TinhThanh,
            'MacDinh' => $request->MacDinh ? 1 : 0
        ]);

        return redirect()->back()->with('success', 'Thêm địa chỉ thành công!');
    }

    public function deleteAddress($kh_id, $dc_id)
    {
        $address = \App\Models\DiaChiKhachHang::where('MaKH', $kh_id)->where('MaDC', $dc_id)->firstOrFail();
        $address->delete();
        return redirect()->back()->with('success', 'Xóa địa chỉ thành công!');
    }
}



