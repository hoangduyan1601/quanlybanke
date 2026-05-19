<?php

namespace App\Http\Controllers;

use App\Models\DanhMuc;
use App\Models\SanPham;
use Illuminate\Http\Request;

class SanPhamController extends Controller
{
    public function index(Request $request, $id = 0)
    {
        $categoryId = $id > 0 ? $id : $request->query('id', 0);
        $sort = $request->query('sort', 'latest');
        $categories = DanhMuc::all();
        
        $query = SanPham::with(['danhmuc', 'ThuongHieus']);

        if ($categoryId > 0) {
            $query->where('MaDM', $categoryId);
            $category = DanhMuc::find($categoryId);
            $pageTitle = $category ? "Danh mục: " . $category->TenDM : "Kệ gia dụng theo danh mục";
        } else {
            $pageTitle = "Tất cả sản phẩm";
        }

        // Logic sắp xếp
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('DonGia', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('DonGia', 'desc');
                break;
            case 'name':
                $query->orderBy('TenSP', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('NgayCapNhat', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $totalRecords = $products->total();

        return view('sanpham.list', compact('products', 'categories', 'pageTitle', 'totalRecords', 'categoryId', 'sort'));
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword', '');
        $categories = DanhMuc::all();

        if (!empty($keyword)) {
            $products = SanPham::with(['danhmuc', 'ThuongHieus'])
                ->where('TenSP', 'like', "%{$keyword}%")
                ->orWhereHas('ThuongHieus', function ($query) use ($keyword) {
                    $query->where('TenThuongHieu', 'like', "%{$keyword}%");
                })
                ->orderBy('NgayCapNhat', 'desc')
                ->paginate(12);
            $pageTitle = "Kết quả tìm kiếm: '" . htmlspecialchars($keyword) . "'";
        } else {
            $products = SanPham::whereRaw('1=0')->paginate(12); // Empty result
            $pageTitle = "Vui lòng nhập từ khóa";
        }

        $totalRecords = $products->total();

        return view('sanpham.list', compact('products', 'categories', 'pageTitle', 'totalRecords', 'keyword'));
    }

    public function suggest(Request $request)
    {
        $keyword = $request->query('keyword', '');
        if (empty($keyword)) {
            return response()->json([]);
        }

        $suggestions = SanPham::where('TenSP', 'like', "%{$keyword}%")
            ->select('MaSP', 'TenSP', 'DonGia', 'HinhAnh')
            ->take(8)
            ->get();

        return response()->json($suggestions);
    }

    public function detail(Request $request, $id)
    {
        $product = SanPham::with(['danhmuc', 'NhaSanXuat', 'ThuongHieus', 'hinhanhsanpham', 'variants'])->findOrFail($id);
        
        $reviews = \App\Models\DanhGia::with('khachhang')
            ->where('MaSP', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $relatedProducts = SanPham::where('MaDM', $product->MaDM)
            ->where('MaSP', '!=', $id)
            ->take(4)
            ->get();

        $categories = DanhMuc::all();

        $canReview = false;
        if (auth()->check()) {
            $user = auth()->user();
            $khachHang = \App\Models\KhachHang::where('MaTK', $user->MaTK)->first();
            if ($khachHang) {
                // Kiểm tra đã mua chưa
                $hasPurchased = \App\Models\ChiTietDonHang::whereHas('donHang', function ($query) use ($khachHang) {
                    $query->where('MaKH', $khachHang->MaKH)->where('TrangThaiDH', 'DaGiao');
                })->where('MaSP', $id)->exists();

                // Kiểm tra đã đánh giá chưa
                $alreadyReviewed = \App\Models\DanhGia::where('MaSP', $id)
                    ->where('MaKH', $khachHang->MaKH)
                    ->exists();

                $canReview = $hasPurchased && !$alreadyReviewed;
            }
        }

        return view('sanpham.detail', compact('product', 'reviews', 'relatedProducts', 'categories', 'canReview'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'SoSao' => 'required|integer|min:1|max:5',
            'NoiDung' => 'required|string|max:1000',
            'HinhAnhDG' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!auth()->check()) {
            return back()->with('error', 'Bạn cần đăng nhập để đánh giá.');
        }

        $user = auth()->user();
        $khachHang = \App\Models\KhachHang::where('MaTK', $user->MaTK)->first();

        if (!$khachHang) {
            return back()->with('error', 'Thông tin khách hàng không hợp lệ.');
        }

        // Kiểm tra xem khách đã mua sản phẩm này chưa
        $hasPurchased = \App\Models\ChiTietDonHang::whereHas('donHang', function ($query) use ($khachHang) {
            $query->where('MaKH', $khachHang->MaKH)->where('TrangThaiDH', 'DaGiao');
        })->where('MaSP', $id)->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua và nhận hàng thành công.');
        }

        // Kiểm tra xem khách đã đánh giá chưa
        $alreadyReviewed = \App\Models\DanhGia::where('MaSP', $id)
            ->where('MaKH', $khachHang->MaKH)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        $imagePath = null;
        if ($request->hasFile('HinhAnhDG')) {
            $image = $request->file('HinhAnhDG');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/images/reviews'), $imageName);
            $imagePath = 'assets/images/reviews/' . $imageName;
        }

        \App\Models\DanhGia::create([
            'MaSP' => $id,
            'MaKH' => $khachHang->MaKH,
            'SoSao' => $request->SoSao,
            'NoiDung' => $request->NoiDung,
            'HinhAnhDG' => $imagePath,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}



