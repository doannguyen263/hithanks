<?php

/**
 * Template Name: Page Making Apartment
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
$shop_page_url = get_permalink(wc_get_page_id('shop'));
get_header();

while (have_posts()) : the_post();
  $get_the_ID = get_the_ID();
  if ($post->post_parent) {
    $title_parent = get_the_title($post->post_parent);

    $fix_title = get_field('title', $post->post_parent);
    if ($fix_title) {
      $title_parent = $fix_title;
    }
  } else {
    $title_parent = get_the_title();

    $fix_title = get_field('title', $post->post_parent);
    if ($fix_title) {
      $title_parent = $fix_title;
    }
  }

  $subpage_title = get_field('subpage_title');

?>
  <div class="nav-dieuhuong" data-toggle="sticky-onscroll">
    <div class="container">
      <div class="d-md-flex align-items-center">
        <div class="nav-dieuhuong__title back-to-top"><?= $subpage_title ?? $title_parent ?></div>

        <?php
        if (have_rows('main_links')):
          $items = [];
          $foundActive = false;
          while (have_rows('main_links')) : the_row();
            $title = get_sub_field('title');
            $link = get_sub_field('link');
            $classActive = (untrailingslashit(get_permalink()) === untrailingslashit($link)) ? 'current_page_item' : '';
            if ($classActive) $foundActive = true;
            $items[] = [
              'title' => $title,
              'link' => $link,
              'class' => $classActive
            ];
          endwhile;
          // Nếu không có classActive nào thì gán cho phần tử đầu tiên
          if (!$foundActive && count($items) > 0) {
            $items[0]['class'] = 'current_page_item';
          }
        ?>
          <ul class="nav-list d-flex justify-content-md-end ms-md-auto">
            <?php foreach ($items as $item): ?>
              <li class="<?= $item['class'] ?>"><a href="<?= $item['link'] ?>"><?= $item['title'] ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <ul class="nav-list d-flex justify-content-md-end ms-md-auto">
            <?php echo rt_list_child_pagesv2(); ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="container">
    <hr class="mt-0">
  </div>

  <div class="page__content pb-5">
    <div class="container">
      <?php $get_color       = get_field('color'); ?>
      <div class="about-heading" style="background: <?= $get_color ?>">
        <h1 class="about-heading__title"><?= get_field('sub') ?></h1>
        <?php
        $get_field_sub2 = get_field('sub2');
        if ($get_field_sub2): ?>
          <div class="about-heading__sub h2"><?= $get_field_sub2 ?></div>
        <?php endif ?>
      </div>
      <hr>


      <div class="row">
        <div class="col-md-3">
          <h3 class="about-list__title">Quá trình thực hiện</h3>
        </div>
        <div class="col-md-9">
          <div class="ctf7-making pb-5">
            <div class="entry-content">
              <p>Để đơn giản trong khi thực hiện dự án, chúng tôi đưa ra một số gợi ý, điều này mang lại sự thuận tiện trong quá trình cùng bạn tạo nên một dự án về không gian sống, trọn vẹn và ý nghĩa.</p>
              <p><strong>1. LISTENNING:</strong> Lắng nghe, thấu hiểu các mong muốn, nhu cầu và kỳ vọng theo quan điểm của bạn. Tìm hiểu, làm rõ các hạng mục và chính sách của công ty có thể mang đến cho bạn, nhận thông tin, bản vẽ, hình ảnh tham khảo..., trao đổi về cách thức tổ chức không gian. Thiết lập các tiêu chí thiết kế... </p>
              <p><strong>2. MAKING</strong>Khảo sát và phân tích hiện trạng, Đề xuất giải pháp, Thống nhất phương án, Hiệu chỉnh bản vẽ, Phát hành hồ sơ, Đồng bộ hiện trạng, Xác nhận sản xuất, Vận chuyển và lắp đặt sản phẩm tại công trình... </p>
              <p><strong>3. FINISHING</strong>Giám sát thiết kế, Lưu trữ dữ liệu, Bảo trì sản phẩm</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <h3 class="about-list__title">Đặt hàng thiết kế</h3>
        </div>
        <div class="col-md-9">
          <div class="ctf7-making pb-5">
            <div class="entry-content">
              <?php //echo do_shortcode('[contact-form-7 id="4d93a31" title="Form đặt hàng - APARTMENT"]'); 
              ?>
              <form id="order-apartment-form" class="order-form">
                <p><strong>1/3. Hình ảnh căn hộ của bạn</strong></p>
                <div class="el-box">
                  <p><strong>Tải hình ảnh</strong></p>
                  <p>Bất kỳ bức ảnh, bản phác thảo hoặc bản vẽ nào cũng sẽ hữu ích, ngay cả khi nó là một nét vẽ nguệch ngoạc trên mặt sau của một phong bì.</p>
                  <?= do_shortcode('[mfile upload-file-377 min-file:0 max-file:5]') ?>
                </div>
                <p><strong>2/3. Ghi chú về căn hộ của bạn</strong></p>
                <div id="order-detail-design" class="el-box js-order-detail-design js-page-order">
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <strong class="fm-bold">1. CHI TIẾT THIẾT KẾ</strong>
                    </div>
                    <div class="col-md-9">
                    </div>
                  </div>

                  <div class="mb-3">
                    <?php
                    $id = 0;
                    if (have_rows('order_detail')):
                      while (have_rows('order_detail')) : the_row();
                        $id++;
                        $name = get_sub_field('name');
                        $title = get_sub_field('title');
                        $excerpt = get_sub_field('excerpt');
                    ?>
                        <div class="row mb-3">
                          <div class="col-md-3">
                            <strong class="fw-bold font-roboto ms-2"><?= $name ?></strong>
                          </div>
                          <div class="col-md-9">
                            <?php
                            if (have_rows('menu')): $id_menu = 0;
                              while (have_rows('menu')) : the_row();
                                $id_menu++;
                                $name = get_sub_field('name');
                                $list = get_sub_field('list');
                                $price = get_sub_field('price');
                                $default_checked = get_sub_field('default_checked');
                            ?>
                                <div class="row mb-3">
                                  <div class="col-md-9">
                                    <label class="d-flex align-items-center gap-1">
                                      <div class="checkbox-wrapper-30">
                                        <span class="checkbox">
                                          <input type="checkbox" name="order_detail_items[]" value="order_detail_item_<?= $id . '-' . $id_menu ?>" data-name="<?= esc_attr($name) ?>" <?= $default_checked ? 'checked' : '' ?> />
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
                                      <span class="fw-bold font-roboto cursor-pointer"><?= $name ?></span>
                                    </label>
                                    <div>
                                      <?= $list ?>
                                    </div>
                                  </div>
                                  <div class="col-md-3 text-end">
                                    <div class="js-order-detail-item-price-<?= $id . '-' . $id_menu ?> fw-medium font-roboto"><?= dntheme_number_format($price) ?></div>
                                  </div>
                                </div>
                              <?php endwhile; ?>
                            <?php endif; ?>

                          </div>
                        </div>
                      <?php endwhile; ?>
                      <hr>
                      <div class="row mb-3">
                        <div class="col-md-3">
                          <strong class="fw-bold font-roboto ms-2">Tổng cộng</strong>
                        </div>
                        <div class="col-md-9 text-end">
                          <div class="js-order-detail-total-price page-item-price">0</div>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

                <div id="order-detail-overview" class="el-box js-order-detail-overview js-page-order">
                  <div class="row mb-3">
                    <div class="col-md-3">
                      <strong class="fm-bold">2. TỔNG THỂ CĂN HỘ</strong>
                    </div>
                    <div class="col-md-9">

                      <div class="row mb-3">
                        <div class="col-md-3">
                          <span>Diện tích</span>
                        </div>
                        <div class="col-md-5">
                          <div class="form-group">
                            <input type="number" name="your-dientich" class="form-control" placeholder="Diện tích (m²)" value="100" min="1" step="0.01">
                          </div>
                        </div>
                        <div class="col-md-4 text-end">
                          <div>m2</div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-9">
                      <?php
                      $id = 0;
                      if (have_rows('order_overview')):
                        while (have_rows('order_overview')) : the_row();
                          $id++;
                          $name = get_sub_field('name');
                          $coefficient = get_sub_field('coefficient');
                      ?>
                          <div class="row mb-3">
                            <div class="col-md-3">
                              <span><?= $name ?></span>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <input type="number" name="order_overview_<?= $id ?>" class="form-control" placeholder="<?= $name ?>" data-coefficient="<?= $coefficient ?>" value="">
                              </div>
                            </div>
                            <div class="col-md-4 text-end">
                              <div class="js-order-overview-<?= $id ?> fw-medium font-roboto">---</div>
                            </div>
                          </div>
                      <?php endwhile;
                      endif;
                      ?>
                      <hr>
                      <div class="row mb-3">
                        <div class="col-md-3">
                          <strong class="fw-bold font-roboto ms-2">Tổng cộng</strong>
                        </div>
                        <div class="col-md-9 text-end">
                          <div class="js-order-detail-total-price page-item-price">0</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <p><strong>3/3. Thông tin liên hệ của bạn</strong></p>
                <div class="el-box">
                  <div class="ctf7_groupinfo">
                    <div class="mb-3 row">
                      <div class="col-md-4">Tên:</div>
                      <div class="col-md-8">
                        <div class="form-group">
                          <input type="text" name="your-name" class="form-control" placeholder="Tên">
                        </div>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <div class="col-md-4">Số điện thoại:</div>
                      <div class="col-md-8">
                        <div class="form-group">
                          <input type="tel" name="your-phone" class="form-control" placeholder="Ví dụ: 0901234567 hoặc 0123456789" maxlength="11">
                        </div>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <div class="col-md-4">Địa chỉ:</div>
                      <div class="col-md-8">
                        <div class="form-group">
                          <input type="text" name="your-address" class="form-control" placeholder="Địa chỉ">
                        </div>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <div class="col-md-4">Địa chỉ dự án:</div>
                      <div class="col-md-8">
                        <div class="form-group">
                          <input type="text" name="your-address-project" class="form-control" placeholder="Địa chỉ dự án">
                        </div>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <div class="col-md-4">Ghi chú thêm:</div>
                      <div class="col-md-8">
                        <div class="form-group">
                          <textarea rows="3" cols="30" name="your-thongtinthem" class="form-control" placeholder="vd: Thói quen sử dụng, Ngân sách, Thời gian thực hiện dự án, ..."></textarea>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="position-relative"><button type="submit" class="btn btn-primary">Gửi yêu cầu và xem thông tin</button></div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <h3 class="about-list__title">Xem và gửi yêu cầu</h3>
        </div>
        <div class="col-md-9">
          <p>Sau khi bạn điền và gửi thông tin đầy đủ phía trên, bạn sẽ nhận được danh mục các nội dung hồ sơ thiết kế, chi tiết trong Hồ sơ được thể hiện đầy đủ các hạng mục thiết kế và giá cả đi kèm. Đây chính là phần nội dung thiết kế bạn sẽ nhận được sau khi đặt hàng, Conceptor (Người hướng dẫn thiết kế) sẽ trực tiếp hướng dẫn để dự án của bạn được tiến hành thiết kế nhanh chóng.</p>
        </div>
      </div>

    </div>
  </div>
<?php endwhile; ?>
<?php get_footer();
