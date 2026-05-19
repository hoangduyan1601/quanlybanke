<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use App\Models\KhachHang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if (!$khachHang) {
            // Tự động tạo bản ghi khách hàng để có thể vào giỏ hàng
            $khachHang = KhachHang::create([
                'MaTK' => $user->id ?? $user->MaTK,
                'HoTen' => $user->name ?? 'Người dùng',
                'SDT'   => '0000000000',
                'Email' => $user->email ?? 'user@example.com',
                'DiaChi'=> 'Chưa cập nhật'
            ]);
        }

        $maKH = $khachHang->MaKH;
        $gioHang = GioHang::where('MaKH', $maKH)->first();
        
        $cart = [];
        $totalPrice = 0;

        if ($gioHang) {
            $items = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->with(['sanPham', 'variant'])->get();
            foreach ($items as $ct) {
                if ($ct->sanPham) {
                    $variant = $ct->variant;
                    $info = [];
                    if ($variant) {
                        if ($variant->MauSac) $info[] = $variant->MauSac;
                        if ($variant->KichThuoc) $info[] = $variant->KichThuoc;
                        if ($variant->SoTang) $info[] = $variant->SoTang . ' tầng';
                    }
                    $variant_info = !empty($info) ? implode(' - ', $info) : null;

                    // Ưu tiên giá của biến thể nếu có
                    $price = $ct->sanPham->gia_hien_tai;
                    $original_price = $ct->sanPham->DonGia;
                    if ($variant) {
                        $promoPercent = 0;
                        if ($ct->sanPham->khuyen_mai_active) {
                            $promoPercent = $ct->sanPham->khuyen_mai_active->PhanTramGiam;
                        }
                        
                        $original_price = $variant->GiaNiemYet;
                        
                        if ($promoPercent > 0) {
                            $price = $variant->GiaNiemYet * (1 - ($promoPercent / 100));
                        } elseif ($variant->GiaKhuyenMai && $variant->GiaKhuyenMai > 0) {
                            $price = $variant->GiaKhuyenMai;
                        } else {
                            $price = $variant->GiaNiemYet;
                        }
                    }

                    $cart[$ct->id] = [
                        'id'    => $ct->id,
                        'product_id' => $ct->MaSP,
                        'variant_id' => $ct->MaVariant,
                        'name'  => $ct->sanPham->TenSP,
                        'variant_info' => $variant_info,
                        'price' => $price,
                        'original_price' => $original_price,
                        'image' => $variant && $variant->HinhAnh ? $variant->HinhAnh : $ct->sanPham->HinhAnh,
                        'qty'   => $ct->SoLuong
                    ];
                    $totalPrice += $price * $ct->SoLuong;
                }
            }
        }

        return view('cart.index', compact('cart', 'totalPrice'));
    }

    public function add(Request $request)
    {
        $id = $request->input('id');
        $variantId = $request->input('variant_id');
        $qty = $request->input('qty', 1);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'login_required', 'message' => 'Bạn cần đăng nhập để mua hàng!']);
        }

        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        if (!$khachHang) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy thông tin khách hàng.']);
        }

        $product = SanPham::with('variants')->find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm không tồn tại']);
        }

        // Bắt buộc chọn biến thể nếu sản phẩm có biến thể
        if ($product->variants->isNotEmpty() && !$variantId) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng chọn một phiên bản cho sản phẩm này!']);
        }

        // Kiểm tra biến thể nếu có
        if ($variantId) {
            $variant = \App\Models\SanPhamVariant::where('MaSP', $id)->where('MaVariant', $variantId)->first();
            if (!$variant) {
                return response()->json(['status' => 'error', 'message' => 'Biến thể không hợp lệ']);
            }
            if ($qty > $variant->SoLuongTon) {
                return response()->json(['status' => 'error', 'message' => 'Biến thể này đã hết hàng hoặc không đủ số lượng!']);
            }
        } else {
            if ($qty > $product->SoLuong) {
                return response()->json(['status' => 'error', 'message' => 'Sản phẩm này đã hết hàng hoặc không đủ số lượng!']);
            }
        }

        $gioHang = GioHang::firstOrCreate(['MaKH' => $khachHang->MaKH], ['NgayTao' => now()]);

        $itemQuery = ChiTietGioHang::where('MaGH', $gioHang->MaGH)
            ->where('MaSP', $id);
        
        if ($variantId) {
            $itemQuery->where('MaVariant', $variantId);
        } else {
            $itemQuery->whereNull('MaVariant');
        }

        $item = $itemQuery->first();

        if ($item) {
            $newQty = $item->SoLuong + $qty;
            
            // Re-check stock with new quantity
            if ($variantId) {
                $variant = \App\Models\SanPhamVariant::find($variantId);
                if ($newQty > $variant->SoLuongTon) {
                    return response()->json(['status' => 'error', 'message' => 'Kho không đủ hàng!']);
                }
            } else {
                if ($newQty > $product->SoLuong) {
                    return response()->json(['status' => 'error', 'message' => 'Kho không đủ hàng!']);
                }
            }
            
            $item->update(['SoLuong' => $newQty]);
        } else {
            ChiTietGioHang::create([
                'MaGH' => $gioHang->MaGH,
                'MaSP' => $id,
                'MaVariant' => $variantId,
                'SoLuong' => $qty,
                'DonGiaTamTinh' => $product->DonGia
            ]);
        }

        $cartCount = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->sum('SoLuong');

        return response()->json([
            'status' => 'success', 
            'message' => 'Đã thêm vào giỏ!', 
            'cartCount' => (int)$cartCount
        ]);
    }

    public function update(Request $request)
    {
        $qtyArray = $request->input('qty', []); // This will now be ID-based
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if ($khachHang) {
            $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();
            if ($gioHang && !empty($qtyArray)) {
                foreach ($qtyArray as $id => $soLuong) {
                    $item = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->where('id', $id)->first();
                    if ($item) {
                        if ($soLuong <= 0) {
                            $item->delete();
                        } else {
                            $item->update(['SoLuong' => $soLuong]);
                        }
                    }
                }
            }
        }

        return redirect()->route('cart.index');
    }

    public function remove($id)
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if ($khachHang) {
            $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();
            if ($gioHang) {
                ChiTietGioHang::where('MaGH', $gioHang->MaGH)->where('id', $id)->delete();
            }
        }

        return redirect()->route('cart.index');
    }

    public function ajaxUpdate(Request $request)
    {
        $id = $request->input('id'); // This is the ID of ChiTietGioHang record
        $qty = $request->input('qty');
        
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if ($khachHang) {
            $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();
            if ($gioHang) {
                $item = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->where('id', $id)->first();
                if ($item) {
                    if ($qty <= 0) {
                        $item->delete();
                    } else {
                        $item->update(['SoLuong' => $qty]);
                    }
                }

                $items = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->with(['sanPham', 'variant'])->get();

                $totalPrice = 0;
                $cartCount = 0;
                $currentItemTotal = 0;
                $currentItemUnitPrice = 0;

                foreach ($items as $item) {
                    if ($item->sanPham) {
                        // Ưu tiên giá của biến thể
                        $price = $item->sanPham->gia_hien_tai;
                        if ($item->variant) {
                            $promoPercent = 0;
                            if ($item->sanPham->khuyen_mai_active) {
                                $promoPercent = $item->sanPham->khuyen_mai_active->PhanTramGiam;
                            }
                            
                            if ($promoPercent > 0) {
                                $price = $item->variant->GiaNiemYet * (1 - ($promoPercent / 100));
                            } elseif ($item->variant->GiaKhuyenMai && $item->variant->GiaKhuyenMai > 0) {
                                $price = $item->variant->GiaKhuyenMai;
                            } else {
                                $price = $item->variant->GiaNiemYet;
                            }
                        }

                        $totalPrice += $price * $item->SoLuong;
                        $cartCount += $item->SoLuong;
                        if ($item->id == $id) {
                            $currentItemTotal = $price * $item->SoLuong;
                            $currentItemUnitPrice = $price;
                        }
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'totalPrice' => number_format($totalPrice, 0, ',', '.') . '₫',
                    'cartCount' => (int)$cartCount,
                    'itemTotal' => number_format($currentItemTotal, 0, ',', '.') . '₫',
                    'unitPrice' => $currentItemUnitPrice
                ]);
            }
        }
        return response()->json(['status' => 'error']);
    }

    public function ajaxRemove(Request $request)
    {
        $id = $request->input('id');
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if ($khachHang) {
            $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();
            if ($gioHang) {
                ChiTietGioHang::where('MaGH', $gioHang->MaGH)->where('id', $id)->delete();

                $items = ChiTietGioHang::where('MaGH', $gioHang->MaGH)->with(['sanPham', 'variant'])->get();

                $totalPrice = 0;
                $cartCount = 0;
                foreach ($items as $item) {
                    if ($item->sanPham) {
                        $price = $item->sanPham->gia_hien_tai;
                        if ($item->variant) {
                            if ($item->variant->GiaKhuyenMai && $item->variant->GiaKhuyenMai > 0) {
                                $price = $item->variant->GiaKhuyenMai;
                            } elseif ($item->variant->GiaNiemYet && $item->variant->GiaNiemYet > 0) {
                                $price = $item->variant->GiaNiemYet;
                            }
                        }
                        $totalPrice += $price * $item->SoLuong;
                        $cartCount += $item->SoLuong;
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'totalPrice' => number_format($totalPrice, 0, ',', '.') . '₫',
                    'cartCount' => (int)$cartCount,
                    'isEmpty' => $items->isEmpty()
                ]);
            }
        }
        return response()->json(['status' => 'error']);
    }

    public function clear()
    {
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTK', $user->MaTK)->first();
        
        if ($khachHang) {
            $gioHang = GioHang::where('MaKH', $khachHang->MaKH)->first();
            if ($gioHang) {
                ChiTietGioHang::where('MaGH', $gioHang->MaGH)->delete();
            }
        }

        return redirect()->route('cart.index');
    }
}
