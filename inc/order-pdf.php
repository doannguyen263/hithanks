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
function generate_order_pdf($order_id, $order_detail, $order_overview, $contact_info, $totals)
{
  // Create uploads directory path
  $upload_dir = wp_upload_dir();
  $pdf_dir = $upload_dir['basedir'] . '/order-pdfs';

  // Create directory if not exists
  if (!file_exists($pdf_dir)) {
    wp_mkdir_p($pdf_dir);
  }

  // Base file names
  $base = 'order-' . $order_id . '-' . date('Y-m-d');
  $pdfPath = $pdf_dir . '/' . $base . '.pdf';
  $htmlPath = $pdf_dir . '/' . $base . '.html';

  // Try to autoload vendor libraries (to enable Dompdf, etc.) if available
  $autoloads = array(
    ABSPATH . 'vendor/autoload.php',
    get_template_directory() . '/vendor/autoload.php',
  );
  foreach ($autoloads as $autoload) {
    if (file_exists($autoload)) {
      try {
        require_once $autoload;
      } catch (\Throwable $e) {
      }
    }
  }

  // Generate and save PDF using mPDF or Dompdf
  $pdfGenerated = false;
  if (class_exists('Mpdf\\Mpdf')) {
    try {
      $mpdf = new \Mpdf\Mpdf(['tempDir' => $upload_dir['basedir'] . '/mpdf-temp']);
      $logo = get_field('logo', 'option');

      // Render header
      ob_start();
      render_order_pdf_header($order_id);
      $mpdf->WriteHTML(ob_get_clean());

      // Page 1: Logo page
      ob_start();
      render_order_pdf_logo_page($logo);
      $mpdf->WriteHTML(ob_get_clean());
      $mpdf->AddPage();

      // Page 2: Design Order Documents
      ob_start();
      render_order_pdf_design_order_page($order_id, $contact_info);
      $mpdf->WriteHTML(ob_get_clean());
      $mpdf->AddPage();

      // Page 3: Workflow
      ob_start();
      render_order_pdf_workflow_page($logo);
      $mpdf->WriteHTML(ob_get_clean());
      $mpdf->AddPage();

      // Page 4+: Content pages
      ob_start();
      render_order_pdf_header_section($order_id, $logo);
      render_order_pdf_contact_info($contact_info);
      render_order_pdf_uploaded_images($order_id);
      render_order_pdf_order_detail($order_detail);
      render_order_pdf_order_overview($order_overview);
      render_order_pdf_grand_total($totals);
      render_order_pdf_footer();
      $mpdf->WriteHTML(ob_get_clean());

      $mpdf->Output($pdfPath, \Mpdf\Output\Destination::FILE);
      $pdfGenerated = true;
    } catch (\Throwable $e) {
      error_log('[order-pdf] mPDF error: ' . $e->getMessage());
    }
  } elseif (class_exists('Dompdf\\Dompdf')) {
    // Fallback for Dompdf - use HTML method
    $html = generate_order_pdf_html($order_id, $order_detail, $order_overview, $contact_info, $totals);
    try {
      $dompdf = new \Dompdf\Dompdf();
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
      file_put_contents($pdfPath, $dompdf->output());
      $pdfGenerated = true;
    } catch (\Throwable $e) {
      error_log('[order-pdf] Dompdf error: ' . $e->getMessage());
    }
  }

  if ($pdfGenerated) {
    return $upload_dir['baseurl'] . '/order-pdfs/' . $base . '.pdf';
  }

  // Fallback: save HTML and return .html URL (so it opens correctly)
  file_put_contents($htmlPath, $html);
  return $upload_dir['baseurl'] . '/order-pdfs/' . $base . '.html';
}

/**
 * Generate HTML content for PDF
 */
function generate_order_pdf_html($order_id, $order_detail, $order_overview, $contact_info, $totals)
{
  ob_start();
  $logo = get_field('logo', 'option');

  render_order_pdf_header($order_id);
  render_order_pdf_logo_page($logo);
  render_order_pdf_design_order_page($order_id, $contact_info);
  render_order_pdf_workflow_page($logo);
  render_order_pdf_header_section($order_id, $logo);
  render_order_pdf_contact_info($contact_info);
  render_order_pdf_uploaded_images($order_id);
  render_order_pdf_order_detail($order_detail);
  render_order_pdf_order_overview($order_overview);
  render_order_pdf_grand_total($totals);
  render_order_pdf_footer();

  return ob_get_clean();
}

/**
 * Render PDF HTML header
 */
function render_order_pdf_header($order_id)
{
?>
  <!DOCTYPE html>
  <html lang="vi">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng #<?php echo $order_id; ?></title>
    <link href="<?php echo get_template_directory_uri(); ?>/assets/css/order-pdf.css" rel="stylesheet">
    <style>
    </style>
  </head>

  <body>
  <?php
}

/**
 * Render logo page
 */
function render_order_pdf_logo_page($logo)
{
  ?>
    <!-- First page: Logo only -->
    <div class="logo-page">
      <div class="logo-container">
        <?php echo wp_get_attachment_image($logo, 'full', false, array('class' => 'logo-image')); ?>
      </div>
    </div>
  <?php
}

/**
 * Render design order documents page
 */
function render_order_pdf_design_order_page($order_id, $contact_info)
{
  ?>
    <!-- Second page: Design Order Documents -->
    <div class="design-order-page">
      <!-- Title Section - Top Right -->
      <div class="design-order-title">
        <h2 class="design-order-title-en">DESIGN ORDER DOCUMENTS</h2>
        <h3 class="design-order-title-vi">HỒ SƠ ĐẶT HÀNG THIẾT KẾ</h3>
      </div>
    </div>
    <!-- Project Details Section - Bottom Right -->
    <div class="design-order-details">
      <div class="detail-item">
        <strong>User <?php echo esc_html($contact_info['name'] ?? 'N/A'); ?></strong>
      </div>
      <div class="detail-item">
        PROJECT: <?php echo esc_html($contact_info['address_project'] ?? 'N/A'); ?>
      </div>
      <div class="detail-item">
        LOCATION: <?php echo esc_html($contact_info['address'] ?? 'N/A'); ?>
      </div>
      <div class="detail-item">
        SCHEDULE: <?php echo date('m/Y'); ?>
      </div>
      <div class="detail-item">
        DATE: <?php echo date('d/m/y h:i:s A'); ?>
      </div>
    </div>
  <?php
}

/**
 * Render footer for design order page
 */
function render_order_pdf_design_order_footer()
{
  // Footer content - can be customized
  // You can add company info, copyright, contact info, etc.
  ?>
    <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
    <p>Báo giá này có hiệu lực trong 30 ngày kể từ ngày phát hành.</p>
  <?php
}

/**
 * Render workflow page
 */
function render_order_pdf_workflow_page($logo)
{
  ?>
    <!-- Workflow Page -->
    <table class="workflow-page" cellpadding="0" cellspacing="0" border="0" width="100%">
      <!-- Header -->
      <tr>
        <td class="workflow-header-left" width="50%" valign="top">
          <h1 class="workflow-title">WORKFLOW</h1>
          <p class="workflow-subtitle">Tiến trình thực hiện</p>
        </td>
        <td class="workflow-header-right" width="50%" valign="top" align="right">
          <?php if ($logo): ?>
            <?php echo wp_get_attachment_image($logo, 'medium', false, array('class' => 'logo-image')); ?>
          <?php endif; ?>
        </td>
      </tr>
      <!-- Divider -->
    </table>
    <div class="workflow-divider"></div>
    <!-- Introduction -->
    <div class="workflow-intro-cell">
      <p class="workflow-intro-text">* Tiến trình làm việc được tạo ra nhằm mục đích truyền đạt thông tin một cách ngắn gọn, rõ ràng và thống nhất, để chuỗi công việc được thực hiện một cách thông suốt và hiệu quả.</p>
    </div>
    <div class="table">
    </div>

    <!-- DESIGN Section -->
    <table class="workflow-page" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 0;">
      <tr>
        <td class="workflow-section-title-cell" valign="top">
          <h2 class="workflow-section-title">DESIGN</h2>
        </td>
        <td class="workflow-section-content-cell" valign="top" style="padding-bottom: 0;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 0;">
            <tr style="padding-bottom: 10px;">
              <td class="workflow-subsection-cell" style="padding: 0;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr style="border:0;">
                    <td class="workflow-subsection-title" style="padding: 0;">CONCEPT DESIGN:</td>
                  </tr>
                  <tr style="border:0">
                    <td class="workflow-subsection-text" style="padding: 6px 0 0 0;">
                      Là quá trình tạo ra ý tưởng và thiết kế ban đầu cho một dự án trước khi bước vào giai đoạn thiết kế chi tiết. Đây là bước quan trọng để xác định tổng thể các hạng mục và tính năng cơ bản của dự án
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td class="workflow-subsection-cell" style="padding: 10px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr style="border:0">
                    <td class="workflow-subsection-title" style="padding: 0;">PRODUCT DESIGN:</td>
                  </tr>
                  <tr style="border:0">
                    <td class="workflow-subsection-text" style="padding: 6px 0 0 0;">
                      Là bản thiết kế chính thức và chi tiết của các sản phẩm có trong dự án, nó bao gồm các thông số kỹ thuật, chi tiết cụ thể và hướng dẫn chính xác để thực hiện sản phẩm theo đúng yêu cầu của bản thiết kế
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr style="border:0;">
              <td class="workflow-subsection-cell" style="padding: 10px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 0;">
                  <tr style="border:0">
                    <td class="workflow-subsection-title" style="padding: 0;">TECHNICAL DESIGN:</td>
                  </tr>
                  <tr style="border:0">
                    <td class="workflow-subsection-text" style="padding: 6px 0 0 0;">
                      Là bản vẽ chi tiết kỹ thuật được xác định rõ ràng theo hiện trạng bao gồm các chi tiết thi công cấu trúc và các khía cạnh kỹ thuật bao gồm sàn, tường, trần, điện nước, điều hòa... để hỗ trợ triển khai phần xây dựng và hoàn thiện công trình
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <div class="workflow-divider"></div>
    <table class="workflow-page" width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="workflow-section-title-cell" valign="top">
          <h2 class="workflow-section-title">MADE</h2>
        </td>
        <td class="workflow-section-content-cell" valign="top">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 0;">
            <tr>
              <td class="workflow-subsection-cell" style="padding: 0;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 0;">
                  <tr style="border:0">
                    <td class="workflow-subsection-title" style="padding: 0;">BUILD:</td>
                  </tr>
                  <tr style="border:0">
                    <td class="workflow-subsection-text" style="padding: 6px 0 0 0;">Tổ chức thi công tại công trình các hạng mục xây dựng theo Hồ sơ thiết kế kỹ thuật</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr style="border: 0;">
              <td class="workflow-subsection-cell" style="padding: 10px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 0;">
                  <tr style="border:0">
                    <td class="workflow-subsection-title" style="padding: 0;">PRODUCT SUPPLY:</td>
                  </tr>
                  <tr style="border:0">
                    <td class="workflow-subsection-text" style="padding: 6px 0 0 0;">Quản lý, cung cấp và lắp đặt sản phẩm theo Hồ sơ thiết kế sản phẩm</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <div class="workflow-divider"></div>
    <table class="workflow-page" cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td class="workflow-section-title-cell" valign="top">
          <h2 class="workflow-section-title">USE</h2>
        </td>
        <td class="workflow-section-content-cell" valign="top">
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="workflow-subsection-cell" style="padding: 0;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr style="border:0">
                    <td class="workflow-subsection-title" style="padding: 0;">TAKE CARE:</td>
                  </tr>
                  <tr style="border:0">
                    <td class="workflow-subsection-text" style="padding: 6px 0 0 0;">Hướng dẫn sử dụng, bảo trì, bảo dưỡng sản phẩm</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr style="border: 0;">
              <td class="workflow-subsection-cell" style="padding: 10px 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 0;">
                  <tr style="border:0">
                    <td class="workflow-subsection-title" style="padding: 0;">MAINTENANCE:</td>
                  </tr>
                  <tr style="border:0">
                    <td class="workflow-subsection-text" style="padding: 6px 0 0 0;">Kiểm tra định kỳ, thay thế và sửa chữa sản phẩm hư hỏng trong quá trình sử dụng</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  <?php
}

/**
 * Render header section with order info
 */
function render_order_pdf_header_section($order_id, $logo)
{
  ?>
    <!-- Header with order info -->
    <div class="header">
      <table class="header-table">
        <tr>
          <td style="width: 70%; padding-right: 16px; vertical-align: top;">
            <h1>ĐƠN ĐẶT HÀNG</h1>
            <div class="order-info">Mã đơn hàng: #<?php echo $order_id; ?> | Ngày: <?php echo date('d/m/Y H:i'); ?></div>
          </td>
          <td style="width: 30%; text-align: right; vertical-align: top;">
            <?php echo wp_get_attachment_image($logo, 'medium', false, array('class' => 'logo-image')); ?>
          </td>
        </tr>
      </table>
    </div>
  <?php
}

/**
 * Render contact information section
 */
function render_order_pdf_contact_info($contact_info)
{
  ?>
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
  <?php
}

/**
 * Render uploaded images section
 */
function render_order_pdf_uploaded_images($order_id)
{
  $attachments = get_post_meta($order_id, '_order_attachments', true);
  if (empty($attachments) || !is_array($attachments)) {
    return;
  }

  $valid_images = array();
  // First, collect all valid images
  foreach ($attachments as $attach_id):
    if (!empty($attach_id)):
      $image_url = wp_get_attachment_image_url($attach_id, 'full');
      if ($image_url):
        $image_alt = get_post_meta($attach_id, '_wp_attachment_image_alt', true);
        if (empty($image_alt)) {
          $image_alt = 'Hình ảnh đơn hàng #' . $order_id;
        }
        $valid_images[] = array('url' => $image_url, 'alt' => $image_alt);
      endif;
    endif;
  endforeach;

  if (empty($valid_images)) {
    return;
  }
  ?>
    <!-- Uploaded Images -->
    <div class="section">
      <div class="section-title">HÌNH ẢNH ĐÃ TẢI LÊN</div>
      <table class="uploaded-images-table">
        <tbody>
          <?php
          // Display images - one image per row
          foreach ($valid_images as $image):
          ?>
            <tr>
              <td class="uploaded-image-cell">
                <div class="uploaded-image-item">
                  <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                </div>
              </td>
            </tr>
          <?php
          endforeach;
          ?>
        </tbody>
      </table>
    </div>
  <?php
}

/**
 * Get default subitems for an order detail item
 * @param string $item_name The name of the item
 * @param string $item_id The ID of the item (optional, for ACF lookup)
 * @return array Array of subitems
 */
function get_order_detail_subitems($item_name, $item_id = '') {
  // Default subitems mapping - có thể mở rộng thêm các items khác
  $default_subitems = array(
    'Thiết kế định hướng' => array(
      'Site Plan - Mặt bằng hiện trạng',
      'Floor Plan - Mặt bằng bố trí vật dụng',
      'Floor Plan - Mặt bằng định vị tường và cửa',
      '3D view - Phối cảnh tổng thể công trình',
      '3D view - Phối cảnh không gian chức năng'
    ),
    // Có thể thêm các items khác ở đây
    // 'Thiết kế sản phẩm' => array(
    //   'Subitem 1',
    //   'Subitem 2',
    // ),
  );

  // Try to get from ACF if item_id is provided
  if (!empty($item_id)) {
    // Parse item_id format: order_detail_item_{id}-{menu_id}
    if (preg_match('/order_detail_item_(\d+)-(\d+)/', $item_id, $matches)) {
      $order_detail_id = intval($matches[1]);
      $menu_id = intval($matches[2]);

      // Try to get ACF field data from order-apartment page template
      $order_page = get_posts(array(
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'templates/page-order-apartment.php',
        'posts_per_page' => 1,
        'post_status' => 'publish'
      ));

      if (!empty($order_page)) {
        $order_page_id = $order_page[0]->ID;
        $order_detail_page = get_field('order_detail', $order_page_id);

        if ($order_detail_page && isset($order_detail_page[$order_detail_id - 1])) {
          $detail_section = $order_detail_page[$order_detail_id - 1];
          if (isset($detail_section['menu'][$menu_id - 1])) {
            $menu_item = $detail_section['menu'][$menu_id - 1];
            if (!empty($menu_item['list'])) {
              // Parse HTML list to array
              return parse_list_to_array($menu_item['list']);
            }
          }
        }
      }
    }
  }

  // Check if item name matches any key (case-insensitive, partial match)
  foreach ($default_subitems as $key => $subitems) {
    if (stripos($item_name, $key) !== false || stripos($key, $item_name) !== false) {
      return $subitems;
    }
  }

  return array();
}

/**
 * Parse HTML list (ul/ol) or plain text list to array
 * @param string $list_content HTML or text content
 * @return array Array of subitems
 */
function parse_list_to_array($list_content) {
  if (empty($list_content)) {
    return array();
  }

  $subitems = array();

  // First, normalize line breaks - replace <br />, <br>, <br/> with newlines
  $list_content = preg_replace('/<br\s*\/?>/i', "\n", $list_content);

  // Try to parse as HTML list (ul/ol)
  if (stripos($list_content, '<ul') !== false || stripos($list_content, '<ol') !== false) {
    // Extract list items from HTML
    preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $list_content, $matches);
    if (!empty($matches[1])) {
      foreach ($matches[1] as $match) {
        // Strip all HTML tags
        $text = strip_tags($match);
        // Replace multiple spaces/newlines with single space
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        if (!empty($text)) {
          $subitems[] = $text;
        }
      }
      return $subitems;
    }
  }

  // Parse as plain text - split by newlines
  $lines = preg_split('/[\r\n]+/', $list_content);
  foreach ($lines as $line) {
    // Strip HTML tags first
    $line = strip_tags($line);
    $line = trim($line);

    // Skip empty lines
    if (empty($line)) {
      continue;
    }

    // Remove leading dashes, bullets, or other markers (but keep numbers that are part of text like "3D")
    // Only remove if it's a standalone marker followed by space
    $line = preg_replace('/^[\-\•\*]\s+/', '', $line); // Remove "- ", "• ", "* " at start
    $line = preg_replace('/^\d+[\.\)]\s+/', '', $line); // Remove "1. ", "1) " at start
    $line = trim($line);

    // Replace multiple spaces with single space
    $line = preg_replace('/\s+/', ' ', $line);

    if (!empty($line)) {
      $subitems[] = $line;
    }
  }

  return $subitems;
}

/**
 * Render order detail section
 */
function render_order_pdf_order_detail($order_detail)
{
  if (empty($order_detail)) {
    return;
  }
  ?>
    <!-- Order Detail -->
    <div class="section">
      <div class="section-title">CHI TIẾT THIẾT KẾ</div>
      <table>
        <thead>
          <tr>
            <th style="width: 60%;">Tên sản phẩm</th>
            <th style="width: 40%;" class="text-right"></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $detail_total = 0;
          foreach ($order_detail as $item):
            $price = isset($item['price']) ? (int)$item['price'] : 0;
            $checked = isset($item['checked']) ? (bool)$item['checked'] : true; // Default to checked for backward compatibility
            $name = isset($item['name']) ? $item['name'] : (isset($item['id']) ? $item['id'] : 'N/A');
            $item_id = isset($item['id']) ? $item['id'] : '';

            // Get subitems: priority: 1) from item data, 2) from ACF/ID lookup, 3) from default mapping
            $subitems = array();
            if (isset($item['subitems']) && is_array($item['subitems']) && !empty($item['subitems'])) {
              // Use subitems from order data (highest priority)
              $subitems = $item['subitems'];
            } else {
              // Try to get from ACF or default mapping
              $subitems = get_order_detail_subitems($name, $item_id);
            }

            // Only add to total if checked
            if ($checked) {
              $detail_total += $price;
            }
          ?>
            <!-- https://www.flaticon.com/free-icon/check-box_9258207?term=check&related_id=9222555&origin=tag -->
            <tr>
              <td>
                <div style="font-weight: bold;"><?php echo esc_html($name); ?></div>
                <?php if (!empty($subitems)): ?>
                  <div style="padding-left: 20px; padding-top: 8px;">
                    <?php foreach ($subitems as $subitem): ?>
                      <div style="padding-bottom: 4px; font-size: 11px; color: #666;">
                        - <?php echo esc_html($subitem); ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </td>
              <td class="text-right" style="vertical-align: top;">
                <?php if ($checked): ?>
                  <img src="<?php echo get_theme_file_uri('assets/img/checked.png'); ?>" alt="Checked" style="width: 20px; height: 20px;">
                <?php else: ?>
                  <img src="<?php echo get_theme_file_uri('assets/img/unchecked.png'); ?>" alt="Unchecked" style="width: 20px; height: 20px;">
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <!-- <tr class="total-row">
        <td><strong>TỔNG CỘNG CHI TIẾT THIẾT KẾ:</strong></td>
        <td class="text-right"><strong><?php echo number_format($detail_total, 0, ',', '.'); ?> đ</strong></td>
      </tr> -->
        </tbody>
      </table>
    </div>
  <?php
}

/**
 * Render order overview section
 */
function render_order_pdf_order_overview($order_overview)
{
  if (empty($order_overview)) {
    return;
  }
  ?>
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
  <?php
}

/**
 * Render grand total section
 */
function render_order_pdf_grand_total($totals)
{
  if (empty($totals)) {
    return;
  }
  ?>
    <!-- Grand Total -->
    <div class="section">
      <div class="section-title">TỔNG ĐƠN HÀNG</div>
      <table>
        <!-- <tr>
        <td style="width: 60%;"><strong>Chi tiết thiết kế:</strong></td>
        <td class="text-right"><strong><?php echo number_format($totals['order_detail_total'], 0, ',', '.'); ?> đ</strong></td>
      </tr>
      <tr>
        <td><strong>Tổng thể căn hộ:</strong></td>
        <td class="text-right"><strong><?php echo number_format($totals['overview_total'], 0, ',', '.'); ?> đ</strong></td>
      </tr> -->
        <tr class="grand-total">
          <td style="padding-top: 15px; border-top: 2px solid #d63638;">TỔNG CỘNG:</td>
          <td class="text-right" style="padding-top: 15px; border-top: 2px solid #d63638;">
            <span style="font-size: 18px; color: #d63638;"><?php echo number_format($totals['grand_total'], 0, ',', '.'); ?> đ</span>
          </td>
        </tr>
      </table>
    </div>
  <?php
}

/**
 * Render PDF footer
 */
function render_order_pdf_footer()
{
  ?>
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 10px; color: #999; text-align: center;position: fixed;bottom: 0;right: 0;left: 0;">
      <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
      <p>Báo giá này có hiệu lực trong 30 ngày kể từ ngày phát hành.</p>
    </div>
  </body>

  </html>
<?php
}

/**
 * Get PDF download URL for an order
 */
function get_order_pdf_url($order_id)
{
  $order_detail = get_post_meta($order_id, 'order_detail', true);
  $order_overview = get_post_meta($order_id, 'order_overview', true);
  $contact_info = get_post_meta($order_id, 'contact_info', true);
  $totals = get_post_meta($order_id, 'totals', true);

  if (empty($order_id) || empty($contact_info)) {
    return '';
  }

  return generate_order_pdf($order_id, $order_detail, $order_overview, $contact_info, $totals);
}
