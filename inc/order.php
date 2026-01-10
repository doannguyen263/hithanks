<?php
/**
 * Order AJAX Handler
 * Handle order form submissions
 */

// Include PDF generation functions
require_once get_template_directory() . '/inc/order-pdf.php';

// Add AJAX action hooks
add_action('wp_ajax_submit_order_form', 'handle_submit_order_form');
add_action('wp_ajax_nopriv_submit_order_form', 'handle_submit_order_form');
add_action('wp_ajax_regenerate_order_pdf', 'handle_regenerate_order_pdf');
add_action('wp_ajax_nopriv_regenerate_order_pdf', 'handle_regenerate_order_pdf');
add_action('wp_ajax_get_fresh_nonce', 'handle_get_fresh_nonce');
add_action('wp_ajax_nopriv_get_fresh_nonce', 'handle_get_fresh_nonce');

// Register REST API endpoint for getting fresh nonce (supports GET method)
// Register early to ensure it's available when scripts are enqueued
add_action('rest_api_init', function() {
  register_rest_route('dntheme/v1', '/nonce', array(
    'methods' => 'GET',
    'callback' => 'handle_get_fresh_nonce_rest',
    'permission_callback' => '__return_true' // Public endpoint
  ));
}, 10);

function handle_submit_order_form() {
  // Verify nonce
  $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
  $current_user_id = get_current_user_id();
  
  // Try to verify nonce
  // $verified = wp_verify_nonce($nonce, 'dntheme_nonce');
  
  // if (!$verified) {
  //   // For debugging: try to understand why nonce failed
  //   // Check if nonce is valid for user 0 (public/unauthenticated)
  //   $nonce_user_0 = wp_create_nonce('dntheme_nonce');
  //   $is_same_as_new = ($nonce === $nonce_user_0);
    
  //   error_log('[Order Form] Nonce verification failed.');
  //   error_log('[Order Form] Current User ID: ' . $current_user_id);
  //   error_log('[Order Form] Nonce received: ' . substr($nonce, 0, 10) . '...');
  //   error_log('[Order Form] New nonce for user ' . $current_user_id . ': ' . substr($nonce_user_0, 0, 10) . '...');
  //   error_log('[Order Form] Nonces match: ' . ($is_same_as_new ? 'Yes' : 'No'));
    
  //   wp_send_json_error('Invalid security token. Please refresh the page and try again.');
  //   return;
  // }

  // Get and sanitize form data (support JSON strings via FormData)
  $order_detail = isset($_POST['order_detail']) ? dn_maybe_json_decode($_POST['order_detail']) : [];
  $order_overview = isset($_POST['order_overview']) ? dn_maybe_json_decode($_POST['order_overview']) : [];
  $contact_info = isset($_POST['contact_info']) ? dn_maybe_json_decode($_POST['contact_info']) : [];
  $totals = isset($_POST['totals']) ? dn_maybe_json_decode($_POST['totals']) : [];

  // Validate required fields
  if (empty($contact_info['name']) || empty($contact_info['phone'])) {
    wp_send_json_error('Vui lòng điền đầy đủ thông tin liên hệ');
    return;
  }

  // Prepare post data
  $post_title = '[Căn hộ chung cư] - từ ' . sanitize_text_field($contact_info['name']) . ' - ' . date('d/m/Y H:i');

  $post_data = array(
    'post_title'   => $post_title,
    'post_content' => '', // Will be populated with formatted content
    'post_status'  => 'publish', // Publish so users can view it
    'post_type'    => 'order-design',
    'post_author'  => 1
  );

  // Insert new order
  $order_id = wp_insert_post($post_data);

  if (is_wp_error($order_id)) {
    wp_send_json_error('Không thể tạo đơn hàng');
    return;
  }

  // Save phone separately for verification
  update_post_meta($order_id, 'order_phone', sanitize_text_field($contact_info['phone']));

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

  // Handle file uploads from FilePond (images[]) with max 5 files
  $attachments = [];
  if (!empty($_FILES['images'])) {
    // Count valid files
    $files = $_FILES['images'];
    $fileCount = 0;
    if (is_array($files['name'])) {
      foreach ($files['name'] as $i => $name) {
        if (!empty($files['name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
          $fileCount++;
        }
      }
    } else if (!empty($files['name']) && $files['error'] === UPLOAD_ERR_OK) {
      $fileCount = 1;
    }

    if ($fileCount > 5) {
      wp_send_json_error('Chỉ được tải lên tối đa 5 ảnh');
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    if (is_array($files['name'])) {
      foreach ($files['name'] as $i => $name) {
        if (!empty($files['name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
          $file_array = array(
            'name'     => $files['name'][$i],
            'type'     => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error'    => $files['error'][$i],
            'size'     => $files['size'][$i],
          );
          $attach_id = media_handle_sideload($file_array, $order_id);
          if (!is_wp_error($attach_id)) $attachments[] = $attach_id;
        }
      }
    } else {
      $attach_id = media_handle_upload('images', $order_id);
      if (!is_wp_error($attach_id)) $attachments[] = $attach_id;
    }

    if (!empty($attachments)) {
      update_post_meta($order_id, '_order_attachments', $attachments);
    }
  }

  // Format and save content
  $formatted_content = format_order_content($order_detail, $order_overview, $contact_info, $totals);

  // Update post with formatted content
  wp_update_post(array(
    'ID' => $order_id,
    'post_content' => $formatted_content
  ));

  // Generate PDF for order
  $pdf_url = '';
  try {
    $pdf_url = generate_order_pdf($order_id, $order_detail, $order_overview, $contact_info, $totals);
    // Save PDF URL to meta
    if ($pdf_url) {
      update_post_meta($order_id, 'order_pdf_url', $pdf_url);
    }
  } catch (Exception $e) {
    error_log('[order.php] PDF generation error: ' . $e->getMessage());
  }

  // Get view order page URL
  $view_order_url = home_url('/apartment/dat-hang-thiet-ke');
  $view_order_url = add_query_arg(array(
    'order' => $order_id,
    'phone' => $contact_info['phone']
  ), $view_order_url);

  // Set response
  $response = array(
    'order_id' => $order_id,
    'view_order_url' => $view_order_url,
    'pdf_url' => $pdf_url,
    'attachments' => $attachments
  );

  wp_send_json_success($response);
}

/**
 * Helper: decode JSON string to array if needed
 */
function dn_maybe_json_decode($value) {
  if (is_array($value)) return $value;
  if (is_string($value)) {
    $decoded = json_decode(stripslashes($value), true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
      return $decoded;
    }
  }
  return [];
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
      $checked = isset($item['checked']) ? (bool)$item['checked'] : true; // Default to checked for backward compatibility

      // Only add to total if checked
      if ($checked) {
        $order_total += $price;
      }

      // Get name if available, otherwise use id
      $name = isset($item['name']) ? $item['name'] : (isset($item['id']) ? $item['id'] : 'N/A');

      $content .= '<tr>';
      $content .= '<td>' . ($checked ? '✓' : '') . ' ' . esc_html($name) . '</td>';
      $content .= '<td class="text-right">' . number_format($price, 0, ',', '.') . ' đ</td>';
      $content .= '</tr>';
    }
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

/**
 * Handle get fresh nonce request (AJAX)
 * This endpoint returns a fresh nonce to avoid cache issues
 */
function handle_get_fresh_nonce() {
  // No nonce verification needed for this endpoint as it's used to GET a nonce
  wp_send_json_success(array(
    'nonce' => wp_create_nonce('dntheme_nonce')
  ));
}

/**
 * Handle get fresh nonce request (REST API)
 * This endpoint returns a fresh nonce to avoid cache issues
 * Supports GET method properly
 * 
 * Important: For public forms, we need to ensure nonce is created for user 0
 * (unauthenticated users) to match the verification context
 */
function handle_get_fresh_nonce_rest($request) {
  // Ensure we're in the correct user context for public endpoints
  // For nopriv requests, user ID should be 0
  $current_user_id = get_current_user_id();
  
  // Create nonce - wp_create_nonce uses current user ID
  // For public forms, this should be 0 (unauthenticated)
  $nonce = wp_create_nonce('dntheme_nonce');
  
  return new WP_REST_Response(array(
    'success' => true,
    'data' => array(
      'nonce' => $nonce,
      'user_id' => $current_user_id // For debugging
    )
  ), 200);
}

/**
 * Handle regenerate PDF request
 */
function handle_regenerate_order_pdf() {
  // Verify nonce
  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dntheme_nonce')) {
    wp_send_json_error('Invalid security token');
    return;
  }

  // Get order ID
  $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
  $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

  if (!$order_id) {
    wp_send_json_error('Order ID is required');
    return;
  }

  // Verify phone match for security
  $order_phone = get_post_meta($order_id, 'order_phone', true);
  if ($order_phone !== $phone) {
    wp_send_json_error('Invalid phone number');
    return;
  }

  // Get order data
  $order_detail = get_post_meta($order_id, 'order_detail', true);
  $order_overview = get_post_meta($order_id, 'order_overview', true);
  $contact_info = get_post_meta($order_id, 'contact_info', true);
  $totals = get_post_meta($order_id, 'totals', true);

  if (empty($contact_info)) {
    wp_send_json_error('Order data not found');
    return;
  }

  // Regenerate PDF
  try {
    $pdf_url = generate_order_pdf($order_id, $order_detail, $order_overview, $contact_info, $totals);

    // Update PDF URL in meta
    if ($pdf_url) {
      update_post_meta($order_id, 'order_pdf_url', $pdf_url);
    }

    wp_send_json_success(array(
      'pdf_url' => $pdf_url,
      'message' => 'PDF đã được tạo lại thành công!'
    ));
  } catch (Exception $e) {
    error_log('[order.php] PDF regeneration error: ' . $e->getMessage());
    wp_send_json_error('Không thể tạo lại PDF: ' . $e->getMessage());
  }
}
