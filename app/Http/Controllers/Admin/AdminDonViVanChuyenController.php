<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonViVanChuyen;
use Illuminate\Http\Request;

class AdminDonViVanChuyenController extends Controller
{
    public function index()
    {
        $list = DonViVanChuyen::all();
        return view('admin.donvivanchuyen.index', compact('list'));
    }

    public function create()
    {
        return view('admin.donvivanchuyen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenDVVC' => 'required|max:255',
            'SDT' => 'required|max:20',
        ]);

        DonViVanChuyen::create($request->all());

        return redirect()->route('admin.donvivanchuyen.index')->with('success', 'Thêm đơn vị vận chuyển thành công!');
    }

    public function edit($id)
    {
        $item = DonViVanChuyen::findOrFail($id);
        return view('admin.donvivanchuyen.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = DonViVanChuyen::findOrFail($id);
        
        $request->validate([
            'TenDVVC' => 'required|max:255',
            'SDT' => 'required|max:20',
        ]);

        $item->update($request->all());

        return redirect()->route('admin.donvivanchuyen.index')->with('success', 'Cập nhật đơn vị vận chuyển thành công!');
    }

    public function destroy($id)
    {
        $item = DonViVanChuyen::findOrFail($id);
        if ($item->donhangs()->exists()) {
            return redirect()->route('admin.donvivanchuyen.index')->with('error', 'Không thể xóa đơn vị này vì đã có đơn hàng sử dụng!');
        }
        $item->delete();
        return redirect()->route('admin.donvivanchuyen.index')->with('success', 'Xóa đơn vị vận chuyển thành công!');
    }
}
