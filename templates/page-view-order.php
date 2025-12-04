<?php

/**
 * Template Name: View Order
 *
 * This template displays order details for customers
 */

// Get order ID and phone from URL parameters
$order_id = isset($_GET['order']) ? intval($_GET['order']) : 0;
$phone = isset($_GET['phone']) ? sanitize_text_field($_GET['phone']) : '';
$preview = isset($_GET['preview']) ? sanitize_text_field($_GET['preview']) : '';

// If preview mode, show PDF preview
if ($preview === 'pdf' && $order_id) {
  // Get order data
  $contact_info = get_post_meta($order_id, 'contact_info', true);
  $order_detail = get_post_meta($order_id, 'order_detail', true);
  $order_overview = get_post_meta($order_id, 'order_overview', true);
  $totals = get_post_meta($order_id, 'totals', true);

  // Include PDF HTML generator function
  require_once get_template_directory() . '/inc/order-pdf.php';

  // Generate HTML content
  $html = generate_order_pdf_html($order_id, $order_detail, $order_overview, $contact_info, $totals);

  // Output HTML directly (no WordPress header/footer)
  echo $html;
  exit;
}

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

$request_debug = isset($_GET['debug']) ? sanitize_text_field($_GET['debug']) : '';
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
        <div class="mb-4">
          <?php if ($pdf_url): ?>
            <a href="<?php echo esc_url($pdf_url); ?>" class="btn btn-success btn-lg" target="_blank" download>
              <i class="fa fa-download" aria-hidden="true"></i> Tải file PDF đơn hàng
            </a>
            <?php if (current_user_can('administrator') && $request_debug): ?>
              <a href="<?php echo esc_url(add_query_arg(array('order' => $order_id, 'phone' => $phone, 'preview' => 'pdf'), home_url('/apartment/dat-hang-thiet-ke'))); ?>" class="btn btn-info btn-lg" target="_blank">
                <i class="fa fa-eye" aria-hidden="true"></i> Xem trước PDF
              </a>
            <?php endif; ?>
          <?php endif; ?>
          <?php if (current_user_can('administrator') && $request_debug): ?>
            <button type="button" id="regenerate-pdf-btn" class="btn btn-warning btn-lg" data-order-id="<?php echo $order_id; ?>" data-phone="<?php echo esc_attr($phone); ?>">
              <i class="fa fa-refresh" aria-hidden="true"></i> Tạo lại PDF
            </button>
          <?php endif; ?>
        </div>

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

        <!-- Order Detail -->
        <?php if (!empty($order_detail)): ?>
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="mb-0">Chi tiết thiết kế</h3>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Tên sản phẩm</th>
                    <th class="text-end">Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($order_detail as $index => $item):
                    $price = isset($item['price']) ? (int)$item['price'] : 0;
                    $checked = isset($item['checked']) ? (bool)$item['checked'] : true; // Default to checked for backward compatibility
                    $name = isset($item['name']) ? $item['name'] : (isset($item['id']) ? $item['id'] : 'N/A');
                  ?>
                    <tr>
                      <td><?php echo esc_html($name); ?></td>
                      <td class="text-end">
                          <div class="checkbox-wrapper-30 pointer-events-none">
                            <span class="checkbox">
                              <input type="checkbox" name="order_detail_items[]" <?= $checked ? 'checked' : '' ?> disabled />
                              <svg>
                                <use xlink:href="#checkbox-30" class="checkbox"></use>
                              </svg>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" style="display:none">
                              <symbol id="checkbox-30" viewBox="0 0 22 22">
                                <path fill="none" stroke="currentColor" d="M5.5,11.3L9,14.8L20.2,3.3l0,0c-0.5-1-1.5-1.8-2.7-1.8h-13c-1.7,0-3,1.3-3,3v13c0,1.7,1.3,3,3,3h13 c1.7,0,3-1.3,3-3v-13c0-0.4-0.1-0.8-0.3-1.2" />
                              </symbol>
                            </svg>
                          </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>

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

<script>
(function($) {
  $('#regenerate-pdf-btn').on('click', function() {
    const $button = $(this);
    const orderId = $button.data('order-id');
    const phone = $button.data('phone');
    const originalText = $button.html();

    // Disable button and show loading
    $button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Đang tạo lại PDF...');

    $.ajax({
      url: dntheme_params.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'regenerate_order_pdf',
        nonce: dntheme_params.dntheme_nonce,
        order_id: orderId,
        phone: phone
      },
      success: function(response) {
        if (response.success) {
          // Show success message
          if (window.Swal) {
            Swal.fire({
              icon: 'success',
              title: 'Thành công',
              text: response.data.message || 'PDF đã được tạo lại thành công!',
              confirmButtonText: 'OK'
            }).then(function() {
              // Reload page to show new PDF URL
              location.reload();
            });
          } else {
            alert(response.data.message || 'PDF đã được tạo lại thành công!');
            location.reload();
          }
        } else {
          // Show error
          if (window.Swal) {
            Swal.fire({
              icon: 'error',
              title: 'Có lỗi xảy ra',
              text: response.data || 'Không thể tạo lại PDF'
            });
          } else {
            alert('Có lỗi xảy ra: ' + (response.data || 'Không thể tạo lại PDF'));
          }
          $button.prop('disabled', false).html(originalText);
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', error);
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'Có lỗi xảy ra',
            text: 'Không thể kết nối đến server. Vui lòng thử lại.'
          });
        } else {
          alert('Có lỗi xảy ra: Không thể kết nối đến server. Vui lòng thử lại.');
        }
        $button.prop('disabled', false).html(originalText);
      }
    });
  });
})(jQuery);
</script>

<?php get_footer(); ?>