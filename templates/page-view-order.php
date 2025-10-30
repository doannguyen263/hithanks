<?php
/**
 * Template Name: View Order
 * 
 * This template displays order details for customers
 */

// Get order ID and phone from URL parameters
$order_id = isset($_GET['order']) ? intval($_GET['order']) : 0;
$phone = isset($_GET['phone']) ? sanitize_text_field($_GET['phone']) : '';

// Verify phone match
if ($order_id && $phone) {
  $order_phone = get_post_meta($order_id, 'order_phone', true);
  
  // If phone doesn't match, show 404
  if ($order_phone !== $phone) {
    status_header(404);
    nocache_headers();
    include(get_query_template('404'));
    exit;
  }
} else {
  // If no order ID or phone, redirect to order page
  wp_redirect(home_url('/apartment/dat-hang-thiet-ke'));
  exit;
}

// Get order data
$contact_info = get_post_meta($order_id, 'contact_info', true);
$order_detail = get_post_meta($order_id, 'order_detail', true);
$order_overview = get_post_meta($order_id, 'order_overview', true);
$totals = get_post_meta($order_id, 'totals', true);
$pdf_url = get_post_meta($order_id, 'order_pdf_url', true);

get_header(); ?>

<div class="wrap__page">
  <div class="container py-5">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <!-- Order Header -->
        <div class="card mb-4">
          <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Đơn hàng thiết kế #<?php echo $order_id; ?></h2>
          </div>
          <div class="card-body">
            <p class="text-muted mb-0">Ngày tạo: <?php echo get_the_date('d/m/Y H:i', $order_id); ?></p>
          </div>
        </div>

        <!-- Download PDF Button -->
        <?php if ($pdf_url): ?>
        <div class="mb-4">
          <a href="<?php echo esc_url($pdf_url); ?>" class="btn btn-success btn-lg" target="_blank" download>
            <i class="fa fa-download" aria-hidden="true"></i> Tải file PDF đơn hàng
          </a>
        </div>
        <?php endif; ?>

        <!-- Contact Information -->
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="mb-0">Thông tin liên hệ</h3>
          </div>
          <div class="card-body">
            <table class="table">
              <tbody>
                <tr>
                  <th width="30%">Tên khách hàng:</th>
                  <td><?php echo esc_html($contact_info['name'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                  <th>Số điện thoại:</th>
                  <td><?php echo esc_html($contact_info['phone'] ?? 'N/A'); ?></td>
                </tr>
                <?php if (!empty($contact_info['address'])): ?>
                <tr>
                  <th>Địa chỉ:</th>
                  <td><?php echo esc_html($contact_info['address']); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($contact_info['address_project'])): ?>
                <tr>
                  <th>Địa chỉ dự án:</th>
                  <td><?php echo esc_html($contact_info['address_project']); ?></td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($contact_info['area'])): ?>
                <tr>
                  <th>Diện tích:</th>
                  <td><?php echo esc_html($contact_info['area']); ?> m²</td>
                </tr>
                <?php endif; ?>
                <?php if (!empty($contact_info['note'])): ?>
                <tr>
                  <th>Ghi chú:</th>
                  <td><?php echo esc_html($contact_info['note']); ?></td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Order Overview -->
        <?php if (!empty($order_overview)): ?>
        <div class="card mb-4">
          <div class="card-header">
            <h3 class="mb-0">Tổng thể căn hộ</h3>
          </div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Loại phòng</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-end">Giá tiền</th>
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
                  <td class="text-center"><?php echo esc_html($item['quantity']); ?></td>
                  <td class="text-end"><?php echo number_format($price, 0, ',', '.'); ?> đ</td>
                </tr>
                <?php endforeach; ?>
                <tr class="table-info">
                  <td><strong>TỔNG CỘNG:</strong></td>
                  <td></td>
                  <td class="text-end"><strong><?php echo number_format($overview_total, 0, ',', '.'); ?> đ</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
