<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LichSuNhapHang;
use App\Models\ChiTietNhapHang;
use App\Models\NhaCungCap;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNhapHangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = LichSuNhapHang::with('nhacungcap');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('MaNhap', 'LIKE', "%{$search}%")
                  ->orWhereHas('nhacungcap', function($q2) use ($search) {
                      $q2->where('TenNCC', 'LIKE', "%{$search}%");
                  });
            });
        }

        $list = $query->orderBy('NgayNhap', 'desc')->paginate(10)->withQueryString();
        $totalPhieu = LichSuNhapHang::count();
        $tongTienNhap = LichSuNhapHang::sum('TongTienNhap');
        
        return view('admin.nhaphang.index', compact('list', 'totalPhieu', 'tongTienNhap', 'search'));
    }

    public function create()
    {
        $suppliers = NhaCungCap::all();
        $products = SanPham::all();
        return view('admin.nhaphang.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NgayNhap' => 'required|date',
            'MaNCC' => 'required',
            'products' => 'required|array|min:1',
            'products.*.MaSP' => 'required',
            'products.*.SoLuong' => 'required|numeric|min:1',
            'products.*.GiaNhap' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $tongTien = 0;
            foreach ($request->products as $p) {
                $tongTien += $p['SoLuong'] * $p['GiaNhap'];
            }

            $phieuNhap = LichSuNhapHang::create([
                'NgayNhap' => $request->NgayNhap,
                'MaNCC' => $request->MaNCC,
                'TongTienNhap' => $tongTien
            ]);

            foreach ($request->products as $p) {
                ChiTietNhapHang::create([
                    'MaNhap' => $phieuNhap->MaNhap,
                    'MaSP' => $p['MaSP'],
                    'SoLuongNhap' => $p['SoLuong'],
                    'DonGiaNhap' => $p['GiaNhap']
                ]);

                // Cập nhật số lượng tồn kho
                $sp = SanPham::findOrFail($p['MaSP']);
                $sp->increment('SoLuong', $p['SoLuong']);

                // Nếu có variant_id (cần bổ sung vào form sau), cập nhật cả variant
                if (isset($p['MaVariant']) && $p['MaVariant']) {
                    $variant = \App\Models\SanPhamVariant::find($p['MaVariant']);
                    if ($variant) {
                        $variant->increment('SoLuongTon', $p['SoLuong']);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.nhaphang.index')->with('success', 'Tạo phiếu nhập hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $nhapHang = LichSuNhapHang::with(['nhacungcap', 'chiTietNhapHangs.sanPham'])->findOrFail($id);
        return view('admin.nhaphang.detail', compact('nhapHang'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $nhapHang = LichSuNhapHang::with('chiTietNhapHangs')->findOrFail($id);
            
            // Khi xóa phiếu nhập, trừ lại tồn kho đã cộng trước đó
            foreach ($nhapHang->chiTietNhapHangs as $ct) {
                // Trừ kho sản phẩm chính
                $sp = SanPham::find($ct->MaSP);
                if ($sp) {
                    $sp->decrement('SoLuong', $ct->SoLuongNhap);
                }

                // Trừ kho biến thể (nếu có)
                if (isset($ct->MaVariant) && $ct->MaVariant) {
                    $variant = \App\Models\SanPhamVariant::find($ct->MaVariant);
                    if ($variant) {
                        $variant->decrement('SoLuongTon', $ct->SoLuongNhap);
                    }
                }
            }

            $nhapHang->delete();
            DB::commit();

            return redirect()->route('admin.nhaphang.index')->with('success', 'Xóa phiếu nhập và cập nhật lại tồn kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.nhaphang.index')->with('error', 'Lỗi hệ thống khi xóa phiếu nhập: ' . $e->getMessage());
        }
    }
}



