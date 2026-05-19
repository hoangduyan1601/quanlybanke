<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\BaiViet;
use App\Models\KhachHang;
use App\Models\DonHang;
use App\Models\ThongBao;
use App\Models\ChiTietDonHang;
use App\Models\TaiKhoan;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        $sanphams = SanPham::orderBy('NgayCapNhat', 'desc')->paginate(10, ['*'], 'p');
        $danhmucs = DanhMuc::all();
        $bestSellers = SanPham::orderBy('SoLuongDaBan', 'desc')->take(8)->get();
        $latestArticles = BaiViet::where('TrangThai', true)
            ->orderBy('NgayDang', 'desc')
            ->take(3)
            ->get();

        return view('home.index', compact('sanphams', 'danhmucs', 'bestSellers', 'latestArticles'));
    }

    public function profile()
    {
        /** @var TaiKhoan $user */
        $user = Auth::user();
        
        // Lấy khách hàng liên kết với tài khoản này
        $customer = KhachHang::where('MaTK', $user->MaTK)->first();

        if (!$customer) {
            // Tự động tạo bản ghi khách hàng nếu thiếu để tránh lỗi điều hướng
            $customer = KhachHang::create([
                'MaTK' => $user->MaTK,
                'HoTen' => $user->TenDangNhap ?? 'Người dùng mới',
                'Email' => 'user' . $user->MaTK . '@example.com',
                'SDT' => '0000000000',
                'DiaChi' => 'Chưa cập nhật'
            ]);
        }

        // Phân loại đơn hàng
        $ordersInProgress = DonHang::where('MaKH', $customer->MaKH)
            ->whereIn('TrangThaiDH', ['ChoThanhToan', 'ChoXacNhan', 'DaXacNhan', 'DangGiao'])
            ->orderBy('NgayDat', 'desc')
            ->get();

        $ordersCompleted = DonHang::where('MaKH', $customer->MaKH)
            ->whereIn('TrangThaiDH', ['DaGiao', 'DaHuy'])
            ->orderBy('NgayDat', 'desc')
            ->get();

        $reviewsCount = \App\Models\DanhGia::where('MaKH', $customer->MaKH)->count();
        
        $reviews = [];
        if (request('tab') == 'reviews') {
            $reviews = \App\Models\DanhGia::with('sanpham')
                ->where('MaKH', $customer->MaKH)
                ->orderBy('created_at', 'desc')
                ->get();
        }
            
        return view('home.profile', compact('customer', 'ordersInProgress', 'ordersCompleted', 'reviewsCount', 'reviews'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();

        if (!$khachHang) {
            return redirect()->route('customer.profile')->with('error', 'Cần cập nhật thông tin trước.');
        }

        $notifications = ThongBao::where('MaKH', $khachHang->MaKH)
            ->orderBy('NgayGui', 'desc')
            ->paginate(10);

        return view('home.notifications', compact('notifications', 'khachHang'));
    }

    public function updateProfile(Request $request)
    {
        /** @var TaiKhoan $user */
        $user = Auth::user();
        $customer = KhachHang::where('MaTK', $user->MaTK)->first();

        if (!$customer) {
            return back()->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        $request->validate([
            'HoTen' => 'required|string|max:255',
            'SDT' => 'required|string|max:20',
            'DiaChi' => 'required|string|max:500',
        ]);

        $customer->update([
            'HoTen' => $request->HoTen,
            'SDT' => $request->SDT,
            'DiaChi' => $request->DiaChi,
        ]);

        return back()->with('success', 'Cập nhật hồ sơ thành công.');
    }

    public function markNotificationRead($id)
    {
        $tb = ThongBao::findOrFail($id);
        $tb->update(['TrangThaiDoc' => true]);
        return response()->json(['status' => 'success']);
    }

    public function markAllRead()
    {
        /** @var TaiKhoan $user */
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        if ($khachHang) {
            ThongBao::where('MaKH', $khachHang->MaKH)
                ->where('TrangThaiDoc', false)
                ->update(['TrangThaiDoc' => true]);
        }
        return response()->json(['status' => 'success']);
    }

    public function orderDetail($id)
    {
        $order = DonHang::with(['khachHang', 'chiTietDonHangs.sanPham', 'chiTietDonHangs.variant', 'statusLogs.user'])->findOrFail($id);
        
        // Kiểm tra quyền (chỉ chủ đơn hàng hoặc admin mới được xem)
        /** @var TaiKhoan $user */
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        $isAdmin = in_array($user->VaiTro, ['Admin', 'quanly', 'QuanLy']);
        
        if (!$isAdmin && (!$khachHang || $order->MaKH !== $khachHang->MaKH)) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền xem đơn hàng này.'], 403);
        }

        return response()->json($order);
    }

    public function cancelOrder($id)
    {
        $order = DonHang::findOrFail($id);
        /** @var TaiKhoan $user */
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();

        // Kiểm tra quyền sở hữu
        if (!$khachHang || $order->MaKH !== $khachHang->MaKH) {
            return back()->with('error', 'Yêu cầu không hợp lệ.');
        }

        // Kiểm tra trạng thái
        if (!in_array($order->TrangThaiDH, ['ChoThanhToan', 'ChoXacNhan'])) {
            return back()->with('error', 'Đơn hàng này không thể hủy ở trạng thái hiện tại.');
        }

        DB::beginTransaction();
        try {
            // 1. Cập nhật trạng thái
            $order->update(['TrangThaiDH' => 'DaHuy']);

            // 2. Hoàn trả số lượng vào kho
            $details = ChiTietDonHang::where('MaDH', $id)->get();
            foreach ($details as $item) {
                $product = SanPham::find($item->MaSP);
                if ($product) {
                    $product->increment('SoLuong', $item->SoLuong);
                    $product->decrement('SoLuongDaBan', $item->SoLuong);
                }
            }

            DB::commit();

            // Gửi thông báo email cho Admin về việc hủy đơn
            try {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new OrderStatusNotification($order->load('khachHang')));
            } catch (Exception $e) {
                Log::error('Lỗi gửi email thông báo hủy đơn hàng: ' . $e->getMessage());
            }

            return back()->with('success', 'Đã hủy đơn hàng #' . $id . ' thành công.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi hủy đơn hàng: ' . $e->getMessage());
        }
    }

    public function requestReturn(Request $request, $id)
    {
        $request->validate([
            'LyDo' => 'required|string|max:1000',
            'HinhAnhMinhChung' => 'nullable|image|max:2048',
        ]);

        $order = DonHang::findOrFail($id);
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();

        if (!$khachHang || $order->MaKH !== $khachHang->MaKH) {
            return back()->with('error', 'Yêu cầu không hợp lệ.');
        }

        if ($order->TrangThaiDH !== 'DaGiao') {
            return back()->with('error', 'Chỉ có thể yêu cầu trả hàng cho đơn hàng đã giao thành công.');
        }

        // Kiểm tra xem đã có yêu cầu trả hàng cho đơn này chưa
        $exists = \App\Models\DonTraHang::where('MaDH', $id)->exists();
        if ($exists) {
            return back()->with('error', 'Yêu cầu trả hàng cho đơn này đã tồn tại.');
        }

        $hinhAnh = null;
        if ($request->hasFile('HinhAnhMinhChung')) {
            $file = $request->file('HinhAnhMinhChung');
            $hinhAnh = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/returns'), $hinhAnh);
        }

        \App\Models\DonTraHang::create([
            'MaDH' => $id,
            'LyDo' => $request->LyDo,
            'HinhAnhMinhChung' => $hinhAnh,
            'TrangThaiTra' => 'ChoDuyet',
            'SoTienHoan' => $order->TongThanhToan
        ]);

        // Cập nhật trạng thái vận chuyển để biết đang có yêu cầu trả
        $order->update(['TrangThaiVanChuyen' => 'TraHang']);

        return back()->with('success', 'Đã gửi yêu cầu trả hàng. Vui lòng chờ quản trị viên duyệt.');
    }

    public function changePassword()
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        if (!$khachHang) return redirect('/')->with('error', 'Không tìm thấy thông tin khách hàng.');
        
        return view('auth.change-password', compact('khachHang'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        /** @var TaiKhoan $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->MatKhau)) {
            return back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        $user->update([
            'MatKhau' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }
}



