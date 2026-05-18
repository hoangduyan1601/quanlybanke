<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThuongHieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminThuongHieuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = ThuongHieu::query();

        if ($search) {
            $query->where('Tenthuonghieu', 'LIKE', "%{$search}%")
                  ->orWhere('QuocTich', 'LIKE', "%{$search}%");
        }

        $list = $query->paginate(10)->withQueryString();
        return view('admin.thuonghieu.index', compact('list', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|max:255',
            'anh' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'Tenthuonghieu' => $request->ten,
            'NgaySinh' => $request->ngaysinh,
            'QuocTich' => $request->quoctich,
            'MoTa' => $request->mota,
        ];

        if ($request->hasFile('anh')) {
            $file = $request->file('anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/images/thuonghieu');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $data['AnhDaiDien'] = $filename;
        }

        ThuongHieu::create($data);

        return redirect()->route('admin.thuonghieu.index')->with('success', 'Thêm thương hiệu thành công!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ten' => 'required|max:255',
            'anh' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $thuongHieu = ThuongHieu::findOrFail($id);
        $data = [
            'Tenthuonghieu' => $request->ten,
            'NgaySinh' => $request->ngaysinh,
            'QuocTich' => $request->quoctich,
            'MoTa' => $request->mota,
        ];

        if ($request->hasFile('anh')) {
            // Xóa ảnh cũ nếu có
            if ($thuongHieu->AnhDaiDien && File::exists(public_path('assets/images/thuonghieu/' . $thuongHieu->AnhDaiDien))) {
                File::delete(public_path('assets/images/thuonghieu/' . $thuongHieu->AnhDaiDien));
            }

            $file = $request->file('anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/images/thuonghieu');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $data['AnhDaiDien'] = $filename;
        }

        $thuongHieu->update($data);

        return redirect()->route('admin.thuonghieu.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy($id)
    {
        try {
            $thuongHieu = ThuongHieu::findOrFail($id);
            
            // Kiểm tra xem thương hiệu có sản phẩm nào không
            if ($thuongHieu->sanphams()->exists()) {
                return redirect()->route('admin.thuonghieu.index')->with('error', 'Không thể xóa thương hiệu này vì vẫn còn sản phẩm liên kết!');
            }
            
            // Xóa ảnh
            if ($thuongHieu->AnhDaiDien && File::exists(public_path('assets/images/thuonghieu/' . $thuongHieu->AnhDaiDien))) {
                File::delete(public_path('assets/images/thuonghieu/' . $thuongHieu->AnhDaiDien));
            }

            $thuongHieu->delete();
            return redirect()->route('admin.thuonghieu.index')->with('success', 'Xóa thương hiệu thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.thuonghieu.index')->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
}
