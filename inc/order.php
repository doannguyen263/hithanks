<?php
/**
 * Order AJAX Handler
 * Handle order form submissions
 */

// Add AJAX action hooks
add_action('wp_ajax_submit_order_form', 'handle_submit_order_form');
add_action('wp_ajax_nopriv_submit_order_form', 'handle_submit_order_form');

function handle_submit_order_form() {
  // Verify nonce
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dntheme_nonce')) {
    wp_send_json_error('Invalid security token');
    return;
  }

  // Get and sanitize form data
  $order_detail = isset($_POST['order_detail']) ? $_POST['order_detail'] : [];
  $order_overview = isset($_POST['order_overview']) ? $_POST['order_overview'] : [];
  $contact_info = isset($_POST['contact_info']) ? $_POST['contact_info'] : [];
  $totals = isset($_POST['totals']) ? $_POST['totals'] : [];

  // Validate required fields
  if (empty($contact_info['name']) || empty($contact_info['phone'])) {
    wp_send_json_error('Vui lòng điền đầy đủ thông tin liên hệ');
    return;
  }

  // Prepare post data
  $post_title = 'Đơn hàng từ ' . sanitize_text_field($contact_info['name']) . ' - ' . date('d/m/Y H:i');
  
  $post_data = array(
    'post_title'   => $post_title,
    'post_content' => '', // Will be populated with formatted content
    'post_status'  => 'draft', // Save as draft initially
    'post_type'    => 'order',
    'post_author'  => 1
  );

  // Insert new order
  $order_id = wp_insert_post($post_data);

  if (is_wp_error($order_id)) {
    wp_send_json_error('Không thể tạo đơn hàng');
    return;
  }

  // Save all data to post meta
  if (!empty($order_detail)) {
    update_post_meta($order_id, 'order_detail', $order_detail);
  }

  if (!empty($order_overview)) {
    update_post_meta($order_id, 'order_overview', $order_overview);
  }

  if (!empty($contact_info)) {
    update_post_meta($order_id, 'contact_info', $contact_info);
  }

  if (!empty($totals)) {
    update_post_meta($order_id, 'totals', $totals);
  }

  // Format and save content
  $formatted_content = format_order_content($order_detail, $order_overview, $contact_info, $totals);
  
  // Update post with formatted content
  wp_update_post(array(
    'ID' => $order_id,
    'post_content' => $formatted_content
  ));

  // Set response
  $response = array(
    'order_id' => $order_id,
    'redirect_url' => admin_url('post.php?post=' . $order_id . '&action=edit')
  );

  wp_send_json_success($response);
}

/**
 * Format order content for display in admin
 */
function format_order_content($order_detail, $order_overview, $contact_info, $totals) {
  $content = '<div class="order-summary">';
  
  // Contact Information
  $content .= '<h3>Thông tin liên hệ</h3>';
  $content .= '<table class="widefat">';
  $content .= '<tr><td><strong>Tên:</strong></td><td>' . esc_html($contact_info['name']) . '</td></tr>';
  $content .= '<tr><td><strong>Số điện thoại:</strong></td><td>' . esc_html($contact_info['phone']) . '</td></tr>';
  $content .= '<tr><td><strong>Địa chỉ:</strong></td><td>' . esc_html($contact_info['address']) . '</td></tr>';
  $content .= '<tr><td><strong>Địa chỉ dự án:</strong></td><td>' . esc_html($contact_info['address_project']) . '</td></tr>';
  $content .= '<tr><td><strong>Diện tích:</strong></td><td>' . esc_html($contact_info['area']) . ' m²</td></tr>';
  if (!empty($contact_info['note'])) {
    $content .= '<tr><td><strong>Ghi chú:</strong></td><td>' . esc_html($contact_info['note']) . '</td></tr>';
  }
  $content .= '</table>';

  // Order Detail
  if (!empty($order_detail)) {
    $content .= '<h3>Chi tiết thiết kế</h3>';
    $content .= '<table class="widefat">';
    $order_total = 0;
    foreach ($order_detail as $item) {
      $price = isset($item['price']) ? (int)$item['price'] : 0;
      $order_total += $price;
      $content .= '<tr>';
      $content .= '<td>' . esc_html($item['id']) . '</td>';
      $content .= '<td class="text-right">' . number_format($price, 0, ',', '.') . ' đ</td>';
      $content .= '</tr>';
    }
    $content .= '<tr><td><strong>Tổng cộng:</strong></td><td class="text-right"><strong>' . number_format($order_total, 0, ',', '.') . ' đ</strong></td></tr>';
    $content .= '</table>';
  }

  // Overview
  if (!empty($order_overview)) {
    $content .= '<h3>Tổng thể căn hộ</h3>';
    $content .= '<table class="widefat">';
    $overview_total = 0;
    foreach ($order_overview as $item) {
      $overview_total += isset($item['price']) ? (int)$item['price'] : 0;
      $content .= '<tr>';
      $content .= '<td>' . esc_html($item['name']) . '</td>';
      $content .= '<td>Số lượng: ' . esc_html($item['quantity']) . '</td>';
      $content .= '<td class="text-right">' . number_format($item['price'], 0, ',', '.') . ' đ</td>';
      $content .= '</tr>';
    }
    $content .= '<tr><td colspan="2"><strong>Tổng cộng:</strong></td><td class="text-right"><strong>' . number_format($overview_total, 0, ',', '.') . ' đ</strong></td></tr>';
    $content .= '</table>';
  }

  // Grand Total
  if (!empty($totals)) {
    $content .= '<h3>Tổng đơn hàng</h3>';
    $content .= '<table class="widefat">';
    $grand_total = isset($totals['grand_total']) ? (int)$totals['grand_total'] : 0;
    $content .= '<tr><td><strong>Chi tiết thiết kế:</strong></td><td class="text-right">' . number_format($totals['order_detail_total'], 0, ',', '.') . ' đ</td></tr>';
    $content .= '<tr><td><strong>Tổng thể căn hộ:</strong></td><td class="text-right">' . number_format($totals['overview_total'], 0, ',', '.') . ' đ</td></tr>';
    $content .= '<tr><td><strong>TỔNG CỘNG:</strong></td><td class="text-right"><strong style="color: #d63638; font-size: 18px;">' . number_format($grand_total, 0, ',', '.') . ' đ</strong></td></tr>';
    $content .= '</table>';
  }

  $content .= '</div>';

  return $content;
}
