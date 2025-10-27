<?php
/**
 * Generate PDF for order
 * Requires: MPDF library or similar PDF library
 */

/**
 * Generate PDF file for order
 * @param int $order_id Order post ID
 * @param array $order_detail Order detail items
 * @param array $order_overview Order overview items
 * @param array $contact_info Contact information
 * @param array $totals Totals information
 * @return string PDF file path
 */
function generate_order_pdf($order_id, $order_detail, $order_overview, $contact_info, $totals) {
  // Create uploads directory path
  $upload_dir = wp_upload_dir();
  $pdf_dir = $upload_dir['basedir'] . '/order-pdfs';
  
  // Create directory if not exists
  if (!file_exists($pdf_dir)) {
    wp_mkdir_p($pdf_dir);
  }
  
  // Generate PDF filename
  $filename = 'order-' . $order_id . '-' . date('Y-m-d') . '.pdf';
  $filepath = $pdf_dir . '/' . $filename;
  
  // Generate HTML content for PDF
  $html = generate_order_pdf_html($order_id, $order_detail, $order_overview, $contact_info, $totals);
  
  // Save PDF content
  // Note: You can use mPDF, TCPDF, or any PDF library here
  // For now, we'll save the HTML and return the path
  // You'll need to convert HTML to PDF using a library
  
  file_put_contents($filepath . '.html', $html);
  
  // Return the URL path
  return $upload_dir['baseurl'] . '/order-pdfs/' . $filename . '.html';
}

/**
 * Generate HTML content for PDF
 */
function generate_order_pdf_html($order_id, $order_detail, $order_overview, $contact_info, $totals) {
  ob_start();
  ?>
  <!DOCTYPE html>
  <html lang="vi">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng #<?php echo $order_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font-family: 'Roboto', sans-serif; font-size: 12px; color: #333; padding: 20px; }
      .header { border-bottom: 3px solid #d63638; padding-bottom: 15px; margin-bottom: 20px; }
      .header h1 { color: #d63638; font-size: 24px; margin-bottom: 5px; }
      .header .order-info { color: #666; font-size: 11px; }
      .section { margin-bottom: 25px; }
      .section-title { background: #f0f0f0; padding: 8px 12px; font-weight: bold; font-size: 14px; color: #d63638; margin-bottom: 10px; }
      table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
      table th, table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
      table th { background: #f9f9f9; font-weight: bold; }
      table .text-right { text-align: right; }
      .total-row { font-weight: bold; font-size: 14px; }
      .grand-total { color: #d63638; font-size: 16px; font-weight: bold; }
      .contact-info { background: #f9f9f9; padding: 10px; border-radius: 4px; }
    </style>
  </head>
  <body>
    <div class="header">
      <h1>ĐƠN HÀNG THIẾT KẾ</h1>
      <div class="order-info">Mã đơn hàng: #<?php echo $order_id; ?> | Ngày: <?php echo date('d/m/Y H:i'); ?></div>
    </div>

    <!-- Contact Information -->
    <div class="section">
      <div class="section-title">THÔNG TIN LIÊN HỆ</div>
      <table>
        <tr>
          <td style="width: 30%;"><strong>Tên khách hàng:</strong></td>
          <td><?php echo esc_html($contact_info['name']); ?></td>
        </tr>
        <tr>
          <td><strong>Số điện thoại:</strong></td>
          <td><?php echo esc_html($contact_info['phone']); ?></td>
        </tr>
        <tr>
          <td><strong>Địa chỉ:</strong></td>
          <td><?php echo esc_html($contact_info['address']); ?></td>
        </tr>
        <tr>
          <td><strong>Địa chỉ dự án:</strong></td>
          <td><?php echo esc_html($contact_info['address_project']); ?></td>
        </tr>
        <tr>
          <td><strong>Diện tích:</strong></td>
          <td><?php echo esc_html($contact_info['area']); ?> m²</td>
        </tr>
        <?php if (!empty($contact_info['note'])): ?>
        <tr>
          <td><strong>Ghi chú:</strong></td>
          <td><?php echo esc_html($contact_info['note']); ?></td>
        </tr>
        <?php endif; ?>
      </table>
    </div>

    <!-- Order Detail -->
    <?php if (!empty($order_detail)): ?>
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
          <?php 
          $detail_total = 0;
          foreach ($order_detail as $item): 
            $price = isset($item['price']) ? (int)$item['price'] : 0;
            $detail_total += $price;
            $name = isset($item['name']) ? $item['name'] : (isset($item['id']) ? $item['id'] : 'N/A');
          ?>
          <tr>
            <td><?php echo esc_html($name); ?></td>
            <td class="text-right"><?php echo number_format($price, 0, ',', '.'); ?> đ</td>
          </tr>
          <?php endforeach; ?>
          <tr class="total-row">
            <td><strong>TỔNG CỘNG CHI TIẾT THIẾT KẾ:</strong></td>
            <td class="text-right"><strong><?php echo number_format($detail_total, 0, ',', '.'); ?> đ</strong></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php endif; ?>

    <!-- Order Overview -->
    <?php if (!empty($order_overview)): ?>
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
          <?php 
          $overview_total = 0;
          foreach ($order_overview as $item): 
            $price = isset($item['price']) ? (int)$item['price'] : 0;
            $overview_total += $price;
          ?>
          <tr>
            <td><?php echo esc_html($item['name']); ?></td>
            <td class="text-right"><?php echo esc_html($item['quantity']); ?></td>
            <td class="text-right"><?php echo number_format($price, 0, ',', '.'); ?> đ</td>
          </tr>
          <?php endforeach; ?>
          <tr class="total-row">
            <td colspan="2"><strong>TỔNG CỘNG TỔNG THỂ CĂN HỘ:</strong></td>
            <td class="text-right"><strong><?php echo number_format($overview_total, 0, ',', '.'); ?> đ</strong></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php endif; ?>

    <!-- Grand Total -->
    <?php if (!empty($totals)): ?>
    <div class="section">
      <div class="section-title">TỔNG ĐỢN HÀNG</div>
      <table>
        <tr>
          <td style="width: 60%;"><strong>Chi tiết thiết kế:</strong></td>
          <td class="text-right"><strong><?php echo number_format($totals['order_detail_total'], 0, ',', '.'); ?> đ</strong></td>
        </tr>
        <tr>
          <td><strong>Tổng thể căn hộ:</strong></td>
          <td class="text-right"><strong><?php echo number_format($totals['overview_total'], 0, ',', '.'); ?> đ</strong></td>
        </tr>
        <tr class="grand-total">
          <td style="padding-top: 15px; border-top: 2px solid #d63638;">TỔNG CỘNG:</td>
          <td class="text-right" style="padding-top: 15px; border-top: 2px solid #d63638;">
            <span style="font-size: 18px; color: #d63638;"><?php echo number_format($totals['grand_total'], 0, ',', '.'); ?> đ</span>
          </td>
        </tr>
      </table>
    </div>
    <?php endif; ?>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 10px; color: #999; text-align: center;">
      <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
      <p>Báo giá này có hiệu lực trong 30 ngày kể từ ngày phát hành.</p>
    </div>
  </body>
  </html>
  <?php
  return ob_get_clean();
}

/**
 * Get PDF download URL for an order
 */
function get_order_pdf_url($order_id) {
  $order_detail = get_post_meta($order_id, 'order_detail', true);
  $order_overview = get_post_meta($order_id, 'order_overview', true);
  $contact_info = get_post_meta($order_id, 'contact_info', true);
  $totals = get_post_meta($order_id, 'totals', true);
  
  if (empty($order_id) || empty($contact_info)) {
    return '';
  }
  
  return generate_order_pdf($order_id, $order_detail, $order_overview, $contact_info, $totals);
}
