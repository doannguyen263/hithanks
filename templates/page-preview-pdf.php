<?php
/**
 * Template Name: Preview PDF
 *
 * This template displays PDF preview for order
 */

// Get order ID from URL parameter
$order_id = isset($_GET['order']) ? intval($_GET['order']) : 0;

if (!$order_id) {
  wp_redirect(home_url('/apartment/dat-hang-thiet-ke'));
  exit;
}

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

