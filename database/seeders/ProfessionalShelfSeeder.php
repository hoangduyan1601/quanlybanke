<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\ChiTietSanPham;
use Illuminate\Support\Facades\DB;

class ProfessionalShelfSeeder extends Seeder
{
    public function run()
    {
        $products = SanPham::all();

        foreach ($products as $sp) {
            $name = $sp->TenSP;
            $category = $sp->danhmuc->TenDM ?? 'Kệ Đa Năng';
            
            $content = $this->generateShelfContent($name, $category);
            
            // Cập nhật mô tả ngắn
            $sp->update([
                'MoTa' => $content['short_desc']
            ]);

            // Cập nhật chi tiết
            ChiTietSanPham::updateOrCreate(
                ['MaSP' => $sp->MaSP],
                [
                    'NoiDungChiTiet' => $content['long_desc']
                ]
            );
        }
    }

    private function generateShelfContent($name, $category) {
        $short_desc = "Sản phẩm $name cao cấp, thiết kế hiện đại, tối ưu không gian và đảm bảo độ bền vượt trội cho mọi nhu cầu lưu trữ.";
        
        $long_desc = '
            <div class="product-description-container">
                <section class="overview mb-5">
                    <h2 class="fw-bold mb-3" style="color: #2c3e50; border-left: 5px solid #e67e22; padding-left: 15px;">Tổng Quan Sản Phẩm</h2>
                    <p class="fs-5 text-muted"><strong>' . $name . '</strong> là giải pháp lưu trữ hoàn hảo thuộc dòng <em>' . $category . '</em>. Được chế tác từ vật liệu chất lượng cao với quy trình kiểm định nghiêm ngặt, sản phẩm không chỉ mang lại sự gọn gàng mà còn nâng tầm thẩm mỹ cho không gian của bạn.</p>
                </section>

                <section class="features mb-5">
                    <h3 class="fw-bold mb-4" style="color: #2c3e50;"><i class="fa-solid fa-star me-2 text-warning"></i>Đặc Điểm Nổi Bật</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded shadow-sm h-100">
                                <h5 class="fw-bold"><i class="fa-solid fa-shield-halved me-2 text-primary"></i>Độ Bền Vượt Trội</h5>
                                <p>Sử dụng thép cán nguội/gỗ cao cấp được xử lý bề mặt bằng công nghệ sơn tĩnh điện AkzoNobel, chống rỉ sét, trầy xước và oxy hóa hiệu quả trong mọi điều kiện môi trường.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded shadow-sm h-100">
                                <h5 class="fw-bold"><i class="fa-solid fa-arrows-up-down-left-right me-2 text-success"></i>Thiết Kế Linh Hoạt</h5>
                                <p>Kết cấu lắp ghép thông minh cho phép người dùng dễ dàng điều chỉnh khoảng cách giữa các tầng kệ, phù hợp với nhiều kích thước hàng hóa và vật dụng khác nhau.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded shadow-sm h-100">
                                <h5 class="fw-bold"><i class="fa-solid fa-weight-hanging me-2 text-danger"></i>Khả Năng Chịu Tải Cao</h5>
                                <p>Hệ thống dầm chịu lực vững chắc giúp kệ có thể chịu tải trọng lớn mà không bị cong vênh, đảm bảo an toàn tuyệt đối cho tài sản của bạn.</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 border rounded shadow-sm h-100">
                                <h5 class="fw-bold"><i class="fa-solid fa-wand-magic-sparkles me-2 text-info"></i>Thẩm Mỹ Hiện Đại</h5>
                                <p>Kiểu dáng tối giản nhưng tinh tế, màu sắc trang nhã, dễ dàng phối hợp với nhiều phong cách nội thất từ cửa hàng đến văn phòng hay hộ gia đình.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="usage-scenarios mb-5">
                    <h3 class="fw-bold mb-4" style="color: #2c3e50;"><i class="fa-solid fa-lightbulb me-2 text-warning"></i>Ứng Dụng Thực Tế</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fa-solid fa-check me-2 text-success"></i><strong>Lưu trữ chuyên nghiệp:</strong> Phù hợp cho các kho hàng trung tải, siêu thị, và cửa hàng bán lẻ.</li>
                        <li class="list-group-item"><i class="fa-solid fa-check me-2 text-success"></i><strong>Tối ưu không gian:</strong> Sử dụng hiệu quả diện tích sàn, giúp quản lý hàng hóa khoa học hơn.</li>
                        <li class="list-group-item"><i class="fa-solid fa-check me-2 text-success"></i><strong>Trưng bày đẳng cấp:</strong> Nâng cao giá trị sản phẩm khi được sắp xếp trên hệ thống kệ chuyên nghiệp.</li>
                    </ul>
                </section>

                <section class="spec-highlights mb-5 p-4 bg-light rounded shadow-sm">
                    <h3 class="fw-bold mb-3" style="color: #2c3e50;">Thông Số Kỹ Thuật Chính</h3>
                    <table class="table table-bordered bg-white">
                        <tbody>
                            <tr>
                                <th class="w-25">Thương hiệu</th>
                                <td>Tiêu chuẩn quốc tế / Việt Nam</td>
                            </tr>
                            <tr>
                                <th>Công nghệ sơn</th>
                                <td>Sơn tĩnh điện 3 lớp (Powder Coating)</td>
                            </tr>
                            <tr>
                                <th>Chế độ bảo hành</th>
                                <td>24 tháng lỗi nhà sản xuất</td>
                            </tr>
                            <tr>
                                <th>Dịch vụ đi kèm</th>
                                <td>Hỗ trợ lắp đặt tận nơi & Tư vấn thiết kế không gian</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="footer-note mt-5 pt-4 border-top">
                    <p class="fst-italic text-center text-muted">Lưu ý: Hình ảnh sản phẩm có thể có sự khác biệt nhỏ về màu sắc do ánh sáng khi chụp hoặc cài đặt màn hình. Vui lòng liên hệ hotline để được tư vấn kích thước tùy chỉnh.</p>
                </section>
            </div>
        ';

        return [
            'short_desc' => $short_desc,
            'long_desc' => $long_desc
        ];
    }
}
