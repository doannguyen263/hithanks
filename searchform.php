<?php
/**
 * Template for displaying search forms in Twenty Seventeen
 *
 * @package    WordPress
 * @subpackage Dntheme
 * @version 1.0
 */

?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>
<form role="search" method="get" class="search-form search__form" action="<?php echo esc_url( home_url( ) ); ?>">

    <div class="input-group mb-0">
       <input type="search" id="<?php echo $unique_id; ?>" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Nhập từ khóa cần tìm &hellip;', 'placeholder', 'dntheme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />

      <button class="search-submit btn btn-outline-secondary input-group-text me-0" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>

    </div>
</form>

