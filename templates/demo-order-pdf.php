<?php

/**
 * Template Name: Page Demo PDF
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng #9498</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #d63638;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #d63638;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header .order-info {
            color: #666;
            font-size: 11px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background: #f0f0f0;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
            color: #d63638;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #f9f9f9;
            font-weight: bold;
        }

        table .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            font-size: 14px;
        }

        .grand-total {
            color: #d63638;
            font-size: 16px;
            font-weight: bold;
        }

        .contact-info {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ĐƠN HÀNG THIẾT KẾ</h1>
        <div class="order-info">Mã đơn hàng: #9498 | Ngày: 30/10/2025 16:49</div>
    </div>
    <?php
    $logo_img = get_field('logo', 'option');
    if ($logo_img):
    ?>
        <section class="section section-header">
            <div class="logo">
                <?php
                  $logo_src = wp_get_attachment_image_url($logo_img, 'full');
                  if ($logo_src) {
                    echo '<img src="' . esc_url($logo_src) . '" alt="Logo" style="max-height:70px; height:auto; width:auto;" />';
                  }
                ?>
            </div>
        </section>
    <?php endif; ?>
    <!-- Contact Information -->
    <div class="section">
        <div class="section-title">THÔNG TIN LIÊN HỆ</div>
        <table>
            <tr>
                <td style="width: 30%;"><strong>Tên khách hàng:</strong></td>
                <td>cccccccc</td>
            </tr>
            <tr>
                <td><strong>Số điện thoại:</strong></td>
                <td>0700000000</td>
            </tr>
            <tr>
                <td><strong>Địa chỉ:</strong></td>
                <td>ddddđ</td>
            </tr>
            <tr>
                <td><strong>Địa chỉ dự án:</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Diện tích:</strong></td>
                <td>100 m²</td>
            </tr>
        </table>
    </div>

    <!-- Order Detail -->
    <div class="section">
        <div class="section-title">CHI TIẾT THIẾT KẾ</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 60%;">Tên sản phẩm</th>
                    <th style="width: 40%;" class="text-right">Giá tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Thiết kế định hướng</td>
                    <td class="text-right">500.000 đ</td>
                </tr>
                <tr>
                    <td>Thiết kế Kỹ thuật </td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Thiết kế sản phẩm </td>
                    <td class="text-right">450.000 đ</td>
                </tr>
                <tr class="total-row">
                    <td><strong>TỔNG CỘNG CHI TIẾT THIẾT KẾ:</strong></td>
                    <td class="text-right"><strong>950.000 đ</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Order Overview -->
    <div class="section">
        <div class="section-title">TỔNG THỂ CĂN HỘ</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Loại phòng</th>
                    <th style="width: 20%;" class="text-right">Số lượng</th>
                    <th style="width: 40%;" class="text-right">Giá tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Phòng ngủ master</td>
                    <td class="text-right">2</td>
                    <td class="text-right">2.850.000 đ</td>
                </tr>
                <tr>
                    <td>Phòng ngủ</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Phòng tắm</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Phòng bếp</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Phòng khách</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Phòng thay đồ </td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Phòng làm việc</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Ban công</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr>
                    <td>Phòng giặt phơi</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0 đ</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2"><strong>TỔNG CỘNG TỔNG THỂ CĂN HỘ:</strong></td>
                    <td class="text-right"><strong>2.850.000 đ</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Grand Total -->
    <div class="section">
        <div class="section-title">TỔNG ĐỢN HÀNG</div>
        <table>
            <tr>
                <td style="width: 60%;"><strong>Chi tiết thiết kế:</strong></td>
                <td class="text-right"><strong>0 đ</strong></td>
            </tr>
            <tr>
                <td><strong>Tổng thể căn hộ:</strong></td>
                <td class="text-right"><strong>2.850.000 đ</strong></td>
            </tr>
            <tr class="grand-total">
                <td style="padding-top: 15px; border-top: 2px solid #d63638;">TỔNG CỘNG:</td>
                <td class="text-right" style="padding-top: 15px; border-top: 2px solid #d63638;">
                    <span style="font-size: 18px; color: #d63638;">2.850.000 đ</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 10px; color: #999; text-align: center;">
        <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
        <p>Báo giá này có hiệu lực trong 30 ngày kể từ ngày phát hành.</p>
    </div>
</body>

</html>