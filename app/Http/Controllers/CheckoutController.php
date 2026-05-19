<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use App\Models\KhachHang;
use App\Models\KhuyenMai;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderStatusNotification;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if (!$khachHang) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        $selectedIds = $request->query('ids') ? explode(',', $request->query('ids')) : [];

        $promotions = KhuyenMai::where('NgayKetThuc', '>=', now())
            ->where('NgayBatDau', '<=', now())
            ->whereNull('MaDM') // Không theo danh mục cụ thể
            ->whereNotNull('MaGiamGia') // Có mã giảm giá để người dùng áp dụng
            ->get();

        $addresses = \App\Models\DiaChiKhachHang::where('MaKH', $khachHang->MaKH)->get();

        session()->forget('cart_promotion');

        $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();
        
        $cart = [];
        $totalPrice = 0;
        if ($gioHang) {
            $query = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->with(['sanPham', 'variant']);
            if (!empty($selectedIds)) {
                $query->whereIn('id', $selectedIds);
            }
            $items = $query->get();
            
            foreach ($items as $item) {
                if ($item->sanPham) {
                    $variant = $item->variant;
                    $info = [];
                    if ($variant) {
                        if ($variant->MauSac) $info[] = $variant->MauSac;
                        if ($variant->KichThuoc) $info[] = $variant->KichThuoc;
                        if ($variant->SoTang) $info[] = $variant->SoTang . ' tầng';
                    }
                    $variant_info = !empty($info) ? implode(' - ', $info) : null;

                    // Ưu tiên giá của biến thể nếu có
                    $price = $item->sanPham->gia_hien_tai;
                    $original_price = $item->sanPham->DonGia;
                    if ($variant) {
                        if ($variant->GiaKhuyenMai && $variant->GiaKhuyenMai > 0) {
                            $price = $variant->GiaKhuyenMai;
                            $original_price = $variant->GiaNiemYet;
                        } elseif ($variant->GiaNiemYet && $variant->GiaNiemYet > 0) {
                            $price = $variant->GiaNiemYet;
                            $original_price = $variant->GiaNiemYet;
                        }
                    }

                    $cart[$item->id] = [
                        'id'    => $item->id,
                        'product_id' => $item->MaSP,
                        'variant_id' => $item->MaVariant,
                        'name'  => $item->sanPham->TenSP,
                        'variant_info' => $variant_info,
                        'price' => $price,
                        'original_price' => $original_price,
                        'qty'   => $item->SoLuong,
                        'image' => $variant && $variant->HinhAnh ? $variant->HinhAnh : $item->sanPham->HinhAnh,
                        'ma_dm' => $item->sanPham->MaDM
                    ];
                    $totalPrice += $price * $item->SoLuong;
                }
            }
        }

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        return view('cart.checkout', compact('cart', 'totalPrice', 'promotions', 'khachHang', 'selectedIds', 'addresses'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if (!$khachHang) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin khách hàng.');
        }

        $hoTen   = $request->input('fullname');
        $sdt     = $request->input('phone');
        $diaChi  = $request->input('address');
        $pttt    = $request->input('payment_method', 'TienMat');
        $selectedIds = $request->input('selected_ids') ? explode(',', $request->input('selected_ids')) : [];

        if (empty($diaChi) || empty($hoTen) || empty($sdt)) {
            return back()->with('error', 'Vui lòng nhập đầy đủ họ tên, SĐT và địa chỉ!');
        }

        $khachHang->update([
            'HoTen' => $hoTen,
            'SDT' => $sdt,
            'DiaChi' => $diaChi
        ]);

        $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();
        if (!$gioHang) {
            return redirect()->route('cart.index');
        }

        $query = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->with(['sanPham', 'variant']);
        if (!empty($selectedIds)) {
            $query->whereIn('id', $selectedIds);
        }
        $items = $query->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng hoặc các sản phẩm được chọn trống!');
        }

        $TongThanhToan = 0;
        foreach($items as $item) {
            if ($item->sanPham) {
                $TongThanhToan += $item->sanPham->gia_hien_tai * $item->SoLuong;
            }
        }

        $maKM = null;
        $soTienGiam = 0;

        if (session()->has('cart_promotion')) {
            $promo = session('cart_promotion');
            // Kiểm tra lại điều kiện tối thiểu một lần nữa trước khi lưu
            if ($TongThanhToan >= $promo['DieuKienToiThieu']) {
                $maKM = $promo['MaKM'];
                $soTienGiam = $promo['SoTienGiam'];
            }
        }

        DB::beginTransaction();
        try {
            $initialStatus = ($pttt === 'ChuyenKhoan' || $pttt === 'VNPay') ? 'ChoThanhToan' : 'ChoXacNhan';

            $donHang = DonHang::create([
                'NgayDat' => now(),
                'TongThanhToan' => $TongThanhToan - $soTienGiam,
                'TrangThaiDH' => $initialStatus,
                'PhuongThucThanhToan' => $pttt,
                'MaKH' => $khachHang->MaKH,
                'DiaChiGiao' => $diaChi,
                'MaKM' => $maKM,
                'SoTienGiam' => $soTienGiam
            ]);

            foreach ($items as $item) {
                if ($item->sanPham) {
                    $thanhTien = $item->sanPham->gia_hien_tai * $item->SoLuong;
                    ChiTietDonHang::create([
                        'MaDH' => $donHang->MaDH,
                        'MaSP' => $item->MaSP,
                        'MaVariant' => $item->MaVariant,
                        'SoLuong' => $item->SoLuong,
                        'DonGia' => $item->sanPham->gia_hien_tai,
                        'ThanhTien' => $thanhTien
                    ]);

                    // Giảm tồn kho ở cả sản phẩm và biến thể
                    $item->sanPham->decrement('SoLuong', $item->SoLuong);
                    $item->sanPham->increment('SoLuongDaBan', $item->SoLuong);
                    
                    if ($item->MaVariant) {
                        $variant = \App\Models\SanPhamVariant::find($item->MaVariant);
                        if ($variant) {
                            $variant->decrement('SoLuongTon', $item->SoLuong);
                        }
                    }
                }
            }

            // Chỉ xóa các sản phẩm đã thanh toán khỏi giỏ hàng
            if (!empty($selectedIds)) {
                ChiTietGioHang::where('MaGH', $gioHang->MaGH)->whereIn('id', $selectedIds)->delete();
            } else {
                ChiTietGioHang::where('MaGH', $gioHang->MaGH)->delete();
            }

            session()->forget('cart_promotion');
            DB::commit();

            // Nếu chọn VNPay, chuyển hướng trực tiếp đến trang thanh toán
            if ($pttt === 'VNPay') {
                return app(VNPayController::class)->createPayment($request, $donHang->MaDH);
            }

            // Gửi thông báo email CHỈ khi thanh toán tiền mặt (COD)
            // Với Chuyển khoản, email sẽ được gửi sau khi Webhook xác nhận tiền về
            if ($pttt === 'TienMat') {
                try {
                    // Cho Admin
                    Notification::route('mail', config('mail.from.address'))
                        ->notify(new NewOrderNotification($donHang->load('khachHang')));
                    
                    // Cho Khách hàng
                    Notification::route('mail', $khachHang->Email)
                        ->notify(new OrderStatusNotification($donHang));
                } catch (\Exception $e) {
                    \Log::error('Lỗi gửi email thông báo đơn hàng: ' . $e->getMessage());
                }
            }

            return redirect()->route('checkout.success', $donHang->MaDH);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function changePaymentMethod(Request $request, $id)
    {
        $order = DonHang::findOrFail($id);
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();

        if ($order->MaKH !== $khachHang->MaKH || $order->TrangThaiDH !== 'ChoThanhToan') {
            return back()->with('error', 'Yêu cầu không hợp lệ.');
        }

        $newMethod = $request->input('method', 'TienMat');
        
        DB::beginTransaction();
        try {
            $order->update([
                'PhuongThucThanhToan' => $newMethod,
                'TrangThaiDH' => 'ChoXacNhan'
            ]);

            // Bây giờ mới gửi email vì đã chuyển sang COD (Thanh toán thành công/Xác nhận đặt hàng)
            try {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new NewOrderNotification($order->load('khachHang')));
                Notification::route('mail', $khachHang->Email)
                    ->notify(new OrderStatusNotification($order));
            } catch (\Exception $e) {
                \Log::error('Lỗi gửi email khi đổi phương thức: ' . $e->getMessage());
            }

            DB::commit();
            return redirect()->route('checkout.success', $order->MaDH)->with('success', 'Đã chuyển sang thanh toán khi nhận hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function success($id)
    {
        $order = DonHang::with(['chiTietDonHangs.sanPham'])->findOrFail($id);
        
        // Bảo mật: Đảm bảo khách hàng chỉ xem được đơn hàng của chính mình
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        if ($order->MaKH !== $khachHang->MaKH) {
            return redirect('/')->with('error', 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('cart.success', compact('order'));
    }

    public function checkStatus($id)
    {
        $order = DonHang::find($id);
        if (!$order) return response()->json(['status' => 'error'], 404);
        
        // Đã thanh toán nếu trạng thái không phải là ChoThanhToan
        return response()->json([
            'order_id' => $order->MaDH,
            'status' => $order->TrangThaiDH,
            'is_paid' => !in_array($order->TrangThaiDH, ['ChoThanhToan'])
        ]);
    }

    public function confirmBankTransfer($id)
    {
        $order = DonHang::findOrFail($id);
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();

        // Kiểm tra quyền sở hữu đơn hàng (dùng so sánh không nghiêm ngặt để tránh lỗi kiểu dữ liệu)
        if (!$khachHang || $order->MaKH != $khachHang->MaKH) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền xác nhận đơn hàng này.'], 403);
        }

        // Nếu đã xác nhận rồi hoặc đã thanh toán rồi thì trả về thành công luôn
        if (in_array($order->TrangThaiDH, ['ChoXacNhan', 'DaXacNhan', 'DaGiao'])) {
            return response()->json(['status' => 'success']);
        }

        if ($order->TrangThaiDH !== 'ChoThanhToan') {
            return response()->json(['status' => 'error', 'message' => 'Trạng thái đơn hàng không hợp lệ để xác nhận.'], 400);
        }

        DB::beginTransaction();
        try {
            $order->update([
                'TrangThaiDH' => 'ChoXacNhan',
                'SoTienDaThanhToan' => $order->TongThanhToan 
            ]);

            // Gửi thông báo cho Admin
            try {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new NewOrderNotification($order->load('khachHang')));
            } catch (\Exception $e) {
                \Log::error('Lỗi gửi email xác nhận chuyển khoản: ' . $e->getMessage());
            }

            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function applyPromotion(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
        }

        $promoCode = $request->input('promo_code');
        if (empty($promoCode)) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng nhập mã khuyến mãi.']);
        }

        $promotion = KhuyenMai::where('MaGiamGia', $promoCode)
            ->where('NgayKetThuc', '>=', now())
            ->where('NgayBatDau', '<=', now())
            ->first();

        if (!$promotion) {
            return response()->json(['status' => 'error', 'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn.']);
        }

        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();

        $totalPrice = 0;
        if ($gioHang) {
            $items = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->with('sanPham')->get();
            foreach ($items as $item) {
                if ($item->sanPham) {
                    $totalPrice += $item->sanPham->gia_hien_tai * $item->SoLuong;
                }
            }
        }

        if ($totalPrice < $promotion->DieuKienToiThieu) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Đơn hàng chưa đủ ' . number_format($promotion->DieuKienToiThieu, 0, ',', '.') . 'đ để áp dụng mã này.'
            ]);
        }

        $discountAmount = ($totalPrice * $promotion->PhanTramGiam) / 100;
        $newTotal = $totalPrice - $discountAmount;

        session(['cart_promotion' => [
            'MaKM' => $promotion->MaKM,
            'TenKM' => $promotion->TenKM,
            'PhanTramGiam' => $promotion->PhanTramGiam,
            'DieuKienToiThieu' => $promotion->DieuKienToiThieu,
            'SoTienGiam' => $discountAmount
        ]]);

        return response()->json([
            'status' => 'success',
            'message' => 'Áp dụng mã thành công!',
            'discount_amount' => $discountAmount,
            'new_total' => $newTotal
        ]);
    }
}




