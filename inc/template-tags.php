<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0.1
 */
if ( ! function_exists( 'dntheme_logo' ) ) :
function dntheme_logo(){
    $logo_img = get_field('logo', 'option');
    $taglogo = (is_home() || is_front_page()) ? 'h1' : 'p'; ?>

    <<?php echo $taglogo; ?> class="logo">
      <a href="<?php echo site_url(); ?>" class="" title="<?php bloginfo("name"); ?>">
        <?php echo wp_get_attachment_image( $logo_img, 'full' ); ?>
      </a>
    </<?php echo $taglogo; ?>>
<?php
}
endif;

if ( ! function_exists( 'get_post_status_text' ) ) :
function get_post_status_text($post_id){
    $post_status = get_post_status($post_id);
    if( $post_status == 'pending' ){
        return 'Đang chờ xử lý';
    }elseif ( $post_status == 'publish' ) {
        return 'Đã đăng';
    }
}
endif;


if ( ! function_exists( 'dntheme_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function dntheme_posted_on() {

    // Get the author name; wrap it in a link.
    $byline = sprintf(
        /* translators: %s: post author */
        __( 'by %s', 'dntheme' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
    );

    // Finally, let's write all of this to the page.
    echo '<span class="posted-on">' . dntheme_time_link() . '</span><span class="byline"> ' . $byline . '</span>';
}
endif;


if ( ! function_exists( 'dntheme_time_link' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function dntheme_time_link() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf( $time_string,
        get_the_date( DATE_W3C ),
        get_the_date(),
        get_the_modified_date( DATE_W3C ),
        get_the_modified_date()
    );

    // Wrap the time string in a link, and preface it with 'Posted on'.
    return sprintf(
        /* translators: %s: post date */
        __( '<span class="screen-reader-text">Posted on</span> %s', 'dntheme' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );
}
endif;


if ( ! function_exists( 'dntheme_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function dntheme_entry_footer() {

    /* translators: used between list items, there is a space after the comma */
    $separate_meta = __( ', ', 'dntheme' );

    // Get Categories for posts.
    $categories_list = get_the_category_list( $separate_meta );

    // Get Tags for posts.
    $tags_list = get_the_tag_list( '', $separate_meta );

    // We don't want to output .entry-footer if it will be empty, so make sure its not.
    if ( ( ( dntheme_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {

        echo '<footer class="entry-footer">';
            if ( 'post' === get_post_type() ) {
                if ( ( $categories_list && dntheme_categorized_blog() ) || $tags_list ) {
                    echo '<span class="cat-tags-links">';

                        // Make sure there's more than one category before displaying.
                        if ( $categories_list && dntheme_categorized_blog() ) {
                            echo '<span class="cat-links"><span class="screen-reader-text">' . __( 'Categories', 'dntheme' ) . '</span>' . $categories_list . '</span>';
                        }

                        if ( $tags_list ) {
                            echo '<span class="tags-links"><span class="tagged_as">' . __( 'Tags: ', 'dntheme' ) . '</span>' . $tags_list . '</span>';
                        }

                    echo '</span>';
                }
            }

            //dntheme_edit_link();

        echo '</footer> <!-- .entry-footer -->';
    }
}
endif;


if ( ! function_exists( 'dntheme_edit_link' ) ) :
/**
 * Returns an accessibility-friendly link to edit a post or page.
 *
 * This also gives us a little context about what exactly we're editing
 * (post or page?) so that users understand a bit more where they are in terms
 * of the template hierarchy and their content. Helpful when/if the single-page
 * layout with multiple posts/pages shown gets confusing.
 */
function dntheme_edit_link() {
    edit_post_link(
        sprintf(
            /* translators: %s: Name of current post */
            __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'dntheme' ),
            get_the_title()
        ),
        '<span class="edit-link">',
        '</span>'
    );
}
endif;

/**
 * Display a front page section.
 *
 * @param WP_Customize_Partial $partial Partial associated with a selective refresh request.
 * @param integer              $id Front page section to display.
 */
function dntheme_front_page_section( $partial = null, $id = 0 ) {
    if ( is_a( $partial, 'WP_Customize_Partial' ) ) {
        // Find out the id and set it up during a selective refresh.
        global $dnthemecounter;
        $id = str_replace( 'panel_', '', $partial->id );
        $dnthemecounter = $id;
    }

    global $post; // Modify the global post object before setting up post data.
    if ( get_theme_mod( 'panel_' . $id ) ) {
        $post = get_post( get_theme_mod( 'panel_' . $id ) );
        setup_postdata( $post );
        set_query_var( 'panel', $id );

        get_template_part( 'template-parts/page/content', 'front-page-panels' );

        wp_reset_postdata();
    } elseif ( is_customize_preview() ) {
        // The output placeholder anchor.
        echo '<article class="panel-placeholder panel dntheme-panel dntheme-panel' . $id . '" id="panel' . $id . '"><span class="dntheme-panel-title">' . sprintf( __( 'Front Page Section %1$s Placeholder', 'dntheme' ), $id ) . '</span></article>';
    }
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function dntheme_categorized_blog() {
    $category_count = get_transient( 'dntheme_categories' );

    if ( false === $category_count ) {
        // Create an array of all the categories that are attached to posts.
        $categories = get_categories( array(
            'fields'     => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number'     => 2,
        ) );

        // Count the number of categories that are attached to the posts.
        $category_count = count( $categories );

        set_transient( 'dntheme_categories', $category_count );
    }

    // Allow viewing case of 0 or 1 categories in post preview.
    if ( is_preview() ) {
        return true;
    }

    return $category_count > 1;
}


/**
 * Flush out the transients used in twentyseventeen_categorized_blog.
 */
function dntheme_category_transient_flusher() {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient( 'dntheme_categories' );
}
add_action( 'edit_category', 'dntheme_category_transient_flusher' );
add_action( 'save_post',     'dntheme_category_transient_flusher' );


if ( ! function_exists( 'dntheme_paging_nav' ) ) :
function dntheme_paging_nav() {
    global $wp_query, $wp_rewrite;

    // Don't print empty markup if there's only one page.
    if ( $wp_query->max_num_pages < 2 ) {
        return;
    }

    $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $query_args   = array();
    $url_parts    = explode( '?', $pagenum_link );

    if ( isset( $url_parts[1] ) ) {
        wp_parse_str( $url_parts[1], $query_args );
    }

    $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
    $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

    $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links( array(
        'base'     => $pagenum_link,
        'format'   => $format,
        'total'    => $wp_query->max_num_pages,
        'current'  => $paged,
        'mid_size' => 1,
        'add_args' => array_map( 'urlencode', $query_args ),
        'prev_text' => __( '<span class="icon-arrow-left"></span>', 'twentyfourteen' ),
        'next_text' => __( '<span class="icon-arrow-right"></span>', 'twentyfourteen' ),
    ) );

    if ( $links ) :

    ?>
    <nav class="navigation paging-navigation" role="navigation">
        <div class="pagination loop-pagination">
            <?php echo $links; ?>
        </div><!-- .pagination -->
    </nav><!-- .navigation -->
    <?php
    endif;
}
endif;

/** COMMENTS WALKER */
class dntheme_Walker_Comment extends Walker {

    /**
     * What the class handles.
     *
     * @since 2.7.0
     * @var string
     *
     * @see Walker::$tree_type
     */
    public $tree_type = 'comment';

    /**
     * Database fields to use.
     *
     * @since 2.7.0
     * @var array
     *
     * @see Walker::$db_fields
     * @todo Decouple this
     */
    public $db_fields = array ('parent' => 'comment_parent', 'id' => 'comment_ID');

    /**
     * Starts the list before the elements are added.
     *
     * @since 2.7.0
     *
     * @see Walker::start_lvl()
     * @global int $comment_depth
     *
     * @param string $output Used to append additional content (passed by reference).
     * @param int    $depth  Optional. Depth of the current comment. Default 0.
     * @param array  $args   Optional. Uses 'style' argument for type of HTML list. Default empty array.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1;

        switch ( $args['style'] ) {
            case 'div':
                break;
            case 'ol':
                $output .= '<ol class="children">' . "\n";
                break;
            case 'ul':
            default:
                $output .= '<ul class="children">' . "\n";
                break;
        }
    }

    /**
     * Ends the list of items after the elements are added.
     *
     * @since 2.7.0
     *
     * @see Walker::end_lvl()
     * @global int $comment_depth
     *
     * @param string $output Used to append additional content (passed by reference).
     * @param int    $depth  Optional. Depth of the current comment. Default 0.
     * @param array  $args   Optional. Will only append content if style argument value is 'ol' or 'ul'.
     *                       Default empty array.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1;

        switch ( $args['style'] ) {
            case 'div':
                break;
            case 'ol':
                $output .= "</ol><!-- .children -->\n";
                break;
            case 'ul':
            default:
                $output .= "</ul><!-- .children -->\n";
                break;
        }
    }

    /**
     * Traverses elements to create list from elements.
     *
     * This function is designed to enhance Walker::display_element() to
     * display children of higher nesting levels than selected inline on
     * the highest depth level displayed. This prevents them being orphaned
     * at the end of the comment list.
     *
     * Example: max_depth = 2, with 5 levels of nested content.
     *     1
     *      1.1
     *        1.1.1
     *        1.1.1.1
     *        1.1.1.1.1
     *        1.1.2
     *        1.1.2.1
     *     2
     *      2.2
     *
     * @since 2.7.0
     *
     * @see Walker::display_element()
     * @see wp_list_comments()
     *
     * @param WP_Comment $element           Comment data object.
     * @param array      $children_elements List of elements to continue traversing. Passed by reference.
     * @param int        $max_depth         Max depth to traverse.
     * @param int        $depth             Depth of the current element.
     * @param array      $args              An array of arguments.
     * @param string     $output            Used to append additional content. Passed by reference.
     */
    public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( !$element )
            return;

        $id_field = $this->db_fields['id'];
        $id = $element->$id_field;

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

        /*
         * If at the max depth, and the current element still has children, loop over those
         * and display them at this level. This is to prevent them being orphaned to the end
         * of the list.
         */
        if ( $max_depth <= $depth + 1 && isset( $children_elements[$id]) ) {
            foreach ( $children_elements[ $id ] as $child )
                $this->display_element( $child, $children_elements, $max_depth, $depth, $args, $output );

            unset( $children_elements[ $id ] );
        }

    }

    /**
     * Starts the element output.
     *
     * @since 2.7.0
     *
     * @see Walker::start_el()
     * @see wp_list_comments()
     * @global int        $comment_depth
     * @global WP_Comment $comment
     *
     * @param string     $output  Used to append additional content. Passed by reference.
     * @param WP_Comment $comment Comment data object.
     * @param int        $depth   Optional. Depth of the current comment in reference to parents. Default 0.
     * @param array      $args    Optional. An array of arguments. Default empty array.
     * @param int        $id      Optional. ID of the current comment. Default 0 (unused).
     */
    public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;

        if ( !empty( $args['callback'] ) ) {
            ob_start();
            call_user_func( $args['callback'], $comment, $args, $depth );
            $output .= ob_get_clean();
            return;
        }

        if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
            ob_start();
            $this->ping( $comment, $depth, $args );
            $output .= ob_get_clean();
        } elseif ( 'html5' === $args['format'] ) {
            ob_start();
            $this->html5_comment( $comment, $depth, $args );
            $output .= ob_get_clean();
        } else {
            ob_start();
            $this->comment( $comment, $depth, $args );
            $output .= ob_get_clean();
        }
    }

    /**
     * Ends the element output, if needed.
     *
     * @since 2.7.0
     *
     * @see Walker::end_el()
     * @see wp_list_comments()
     *
     * @param string     $output  Used to append additional content. Passed by reference.
     * @param WP_Comment $comment The current comment object. Default current comment.
     * @param int        $depth   Optional. Depth of the current comment. Default 0.
     * @param array      $args    Optional. An array of arguments. Default empty array.
     */
    public function end_el( &$output, $comment, $depth = 0, $args = array() ) {
        if ( !empty( $args['end-callback'] ) ) {
            ob_start();
            call_user_func( $args['end-callback'], $comment, $args, $depth );
            $output .= ob_get_clean();
            return;
        }
        if ( 'div' == $args['style'] )
            $output .= "</div><!-- #comment-## -->\n";
        else
            $output .= "</li><!-- #comment-## -->\n";
    }

    /**
     * Outputs a pingback comment.
     *
     * @since 3.6.0
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment The comment object.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function ping( $comment, $depth, $args ) {
        $tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( '', $comment ); ?>>
            <div class="comment-body">
                <?php _e( 'Pingback:' ); ?> <?php comment_author_link( $comment ); ?> <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
            </div>
<?php
    }

    /**
     * Outputs a single comment.
     *
     * @since 3.6.0
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function comment( $comment, $depth, $args ) {
        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
?>
        <<?php echo $tag; ?> <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>">
        <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php endif; ?>
        <div class="comment-author vcard">
            <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php
                /* translators: %s: comment author link */
                printf( __( '%s <span class="says">says:</span>' ),
                    sprintf( '<cite class="fn">%s</cite>', get_comment_author_link( $comment ) )
                );
            ?>
        </div>
        <?php if ( '0' == $comment->comment_approved ) : ?>
        <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
        <br />
        <?php endif; ?>

        <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
            <?php
                /* translators: 1: comment date, 2: comment time */
                printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' );
            ?>
        </div>

        <?php comment_text( $comment, array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

        <?php
        comment_reply_link( array_merge( $args, array(
            'add_below' => $add_below,
            'depth'     => $depth,
            'max_depth' => $args['max_depth'],
            'before'    => '<div class="comment-reply">',
            'after'     => '</div>'
        ) ) );
        ?>

        <?php if ( 'div' != $args['style'] ) : ?>
        </div>
        <?php endif; ?>
<?php
    }

    /**
     * Outputs a comment in the HTML5 format.
     *
     * @since 3.6.0
     *
     * @see wp_list_comments()
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment( $comment, $depth, $args ) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                <header class="comment-meta">
                    <div class="comment-author vcard">
                        <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
                        <?php
                            /* translators: %s: comment author link */
                            printf( __( '%s <span class="says">says:</span>' ),
                                sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) )
                            );
                        ?>
                    </div><!-- .comment-author -->

                    <div class="comment-metadata">
                        <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                            <time datetime="<?php comment_time( 'c' ); ?>">
                                <?php
                                    /* translators: 1: comment date, 2: comment time */
                                    printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
                                ?>
                            </time>
                        </a>
                        <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
                    </div><!-- .comment-metadata -->

                    <?php if ( '0' == $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
                    <?php endif; ?>
                </header><!-- .comment-meta -->

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->

                <?php
                comment_reply_link( array_merge( $args, array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<div class="comment-reply">',
                    'after'     => '</div>'
                ) ) );
                ?>
            </article><!-- .comment-body -->
<?php
    }
}


if ( ! function_exists( 'related_category_fix' ) ) :
/**
 * Hiển thị Bài viết liên quan category
 * @author Đoàn Nguyễn
 * @version 1.1.9
 */
function related_category_fix($value)
{
    $args =array();
    $category_ids =array();

    $post_type = (isset($value['post_type'])) ? $value['post_type'] : get_post_type(get_the_ID());

    $template   = 'template-parts/content-related-'.$post_type;
    $template_type = (isset($value['template_type'])) ?$value['template_type'] : 'default';
    $flickity_option = '{ "autoPlay": true ,"cellAlign": "left", "contain": true, "wrapAround": true, "groupCells": true, "pageDots": false,"prevNextButtons": true }';

    // Set template
    if(isset($value['template'])){
        $template   = 'template-parts/'.$value['template'];
    }

    // Set Show post
    $posts_per_page = 8;
    if(isset($value['posts_per_page'])){ $posts_per_page  = $value['posts_per_page'];}

    // Set Title
    $related_title = "Bài Viết";
    if(isset($value['related_title'])){ $related_title  = $value['related_title'];}

    // set_taxonomy
    if(isset($value['set_taxonomy'])){  $taxonomy  = $value['set_taxonomy'];}
    else{

        $taxonomies = get_object_taxonomies($post_type);
        // kiem tra xem phải post type product không, nếu không phải lấy taxnomy vị trí đầu tiên trong mảng
        if($post_type =='product'){
            $taxonomy = 'product_cat';
        }else{
            // $taxonomies_xxx = isset($taxonomies[0]) ? $taxonomies[0] : '';
            // if(taxonomy_exists($taxonomies_xxx)){
            //     $taxonomy   = $taxonomies_xxx;
            // }

            $taxonomy_current = current($taxonomies);
            if(taxonomy_exists($taxonomy_current)){
                $taxonomy   = $taxonomy_current;
            }

        }

    }

    if($post_type =='post'){
        $categories = get_the_category(get_the_ID());
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ):

            foreach($categories as $individual_category):
              $category_ids[] = $individual_category->term_id;
              $args=array(
                    'post_type'=>$post_type,
                    'posts_per_page'=>$posts_per_page,
                    'category__in' => $category_ids,
                    'post__not_in' => array(get_the_ID()),
                    'orderby'   => 'rand',
                );
            endforeach;

        endif;
    }else{
        // kiểm tra xem có taxonomy nếu hk hiển thị bài viết mới nhất
        if(isset($taxonomy)){
            $category = get_the_terms( get_the_ID(), $taxonomy );
            if ( ! empty( $category ) && ! is_wp_error( $category ) ){

                foreach ( $category as $cat){
                    $category_ids[] = $cat->slug;
                }

                $args=array(
                        'post_type'=>$post_type,
                        'posts_per_page'=>$posts_per_page,
                        'post__not_in' => array(get_the_ID()),
                        'tax_query' => array(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'field'    => 'slug',
                                    'terms'    => $category_ids,
                                ),
                            ),
                        'orderby'   => 'rand',
                        );
            }else{
                $args=array(
                    'post_type'=>$post_type,
                    'posts_per_page'=>$posts_per_page,
                    'post__not_in' => array(get_the_ID()),
                    'orderby'   => 'rand',
                );
            }
        }else{
            $args=array(
                'post_type'=>$post_type,
                'posts_per_page'=>$posts_per_page,
                'post__not_in' => array(get_the_ID()),
                'orderby'   => 'rand',
            );
        }

    }
    $query = new wp_query($args);
    if( $query->have_posts() ):?>
        <div class="related__post <?php echo $post_type; ?>">
            <div class="el__header">
                <h3 class="el__header__title"><?php echo $related_title ?></h3>
            </div>
            <?php  ?>

            <?php if($template_type == 'slider'): ?>
                <div class="related__slider slider flickity" data-flickity='<?php echo $flickity_option; ?>'>
                    <?php while ($query->have_posts()):$query->the_post();?>
                    <div class="<?php if($value['class_item']) echo $value['class_item']; else echo 'col-xs-12 col-sm-3 col-md-3'; ?>">
                        <?php
                        // Slug file in Template-parts , ex: content-page
                        get_template_part( $template);?>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php elseif($template_type == 'widget'): ?>
                <div class="widgetRelated">
                    <?php while ($query->have_posts()):$query->the_post();?>
                        <?php
                        // Slug file in Template-parts , ex: content-page
                        get_template_part( $template);?>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="related__list">
                    <div class="row">
                        <?php while ($query->have_posts()):$query->the_post();?>
                        <div class="<?php if($value['class_item']) echo $value['class_item']; else echo 'col-xs-12 col-sm-3 col-md-3'; ?>">
                            <?php
                            // Slug file in Template-parts , ex: content-page
                            get_template_part( $template);?>
                        </div>
                    <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php
    else:
        //echo get_template_part( 'template-parts/content', 'none' );
    endif;
  wp_reset_postdata();
}
endif;

if ( ! function_exists( 'dn_time_ago' ) ) :
    /**
     * Hiển thị thời gian
     * @author Đoàn Nguyễn
     * @version 1.0
     */
    function dn_time_ago($returnz = false) {

        global $post;

        $date = get_post_time('G', true, $post);
            /**
             * Where you see 'themeblvd' below, you'd
             * want to replace those with whatever term
             * you're using in your theme to provide
             * support for localization.
             */

            // Array of time period chunks
            $chunks = array(
                array( 60 * 60 * 24 * 365 , __( 'year', 'themeblvd' ), __( 'năm', 'themeblvd' ) ),
                array( 60 * 60 * 24 * 30 , __( 'month', 'themeblvd' ), __( 'tháng', 'themeblvd' ) ),
                array( 60 * 60 * 24 * 7, __( 'week', 'themeblvd' ), __( 'tuần', 'themeblvd' ) ),
                array( 60 * 60 * 24 , __( 'day', 'themeblvd' ), __( 'ngày', 'themeblvd' ) ),
                array( 60 * 60 , __( 'hour', 'themeblvd' ), __( 'giờ', 'themeblvd' ) ),
                array( 60 , __( 'minute', 'themeblvd' ), __( 'phút', 'themeblvd' ) ),
                array( 1, __( 'second', 'themeblvd' ), __( 'giây', 'themeblvd' ) )
            );

            if ( !is_numeric( $date ) ) {
                $time_chunks = explode( ':', str_replace( ' ', ':', $date ) );
                $date_chunks = explode( '-', str_replace( ' ', '-', $date ) );
                $date = gmmktime( (int)$time_chunks[1], (int)$time_chunks[2], (int)$time_chunks[3], (int)$date_chunks[1], (int)$date_chunks[2], (int)$date_chunks[0] );
            }

            $current_time = current_time( 'mysql' );
            $newer_date = strtotime( $current_time );

            // Difference in seconds
            $since = $newer_date - $date;


            // Something went wrong with date calculation and we ended up with a negative date.
            if ( $since < 0 )
                return __( 'sometime', 'themeblvd' );

            $t1=new DateTime(date('Y-m-d',$newer_date));
            $t2=new DateTime(date('Y-m-d',$date));
            $s=$t1->diff($t2);
            $dayz = $s->days; // Khoảng cách giữa 2 ngày

            if($dayz == 0){
                /**
                 * We only want to output one chunks of time here, eg:
                 * x years
                 * xx months
                 * so there's only one bit of calculation below:
                 */

                //Step one: the first chunk
                for ( $i = 0, $j = count($chunks); $i < $j; $i++) {
                    $seconds = $chunks[$i][0];

                    // Finding the biggest chunk (if the chunk fits, break)
                    if ( ( $count = floor($since / $seconds) ) != 0 )
                        break;
                }

                // Set output var
                $output = ( 1 == $count ) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];


                if ( !(int)trim($output) ){
                    $output = '0 ' . __( 'giây', 'themeblvd' );
                }

                $output .= __(' trước', 'themeblvd');

            }else{
               $output = get_the_time('d/m/Y');
            }

            if($returnz)
                return $output;
            else
                echo $output;

    }
endif;

// qtranslate-xt
function dntheme_get_qtranxf($value='')
{
    if(in_array('qtranslate-xt/qtranslate.php', apply_filters('active_plugins', get_option('active_plugins')))){
    //plugin is activated
        $url = is_404() ? get_option('home') : '';
        global $q_config;
        foreach ( qtranxf_getSortedLanguages() as $language ) {
        $alt = $q_config['language_name'][ $language ] . ' (' . $language . ')';
        echo '<div class="item__flag';
        if ( $language == $q_config['language'] ) {
        echo ' active';
        }
        echo '"><a href="' . qtranxf_convertURL( $url, $language, false, true ) . '"';
        echo ' class="flag_' . $language . ' img" title="' . $alt . '">';
        echo "<img src='".get_theme_file_uri('assets/flag/'.$language.'.png')."'>";
        echo '</a></div>';
      }
    }else{
        echo 'Plugin qtranslate-xt not active';
    }
}

// icl_get_languages()
// Array
// (
//     [en] => Array
//         (
//             [code] => en
//             [id] => 1
//             [native_name] => English
//             [major] => 1
//             [active] => 1
//             [default_locale] => en_US
//             [encode_url] => 0
//             [tag] => en
//             [translated_name] => English
//             [url] => http://localhost/wp/vt-ventures
//             [country_flag_url] => http://localhost/wp/vt-ventures/wp-content/uploads/flags/en.png
//             [language_code] => en
//         )

// )
function dntheme_get_wpml($style='list')
{
    if(in_array('sitepress-multilingual-cms/sitepress.php', apply_filters('active_plugins', get_option('active_plugins')))){
        $flag_width = 36;
        $flag_height = 24;
        $icl_get_languages = icl_get_languages();
        $ICL_LANGUAGE_CODE = ICL_LANGUAGE_CODE;
        $currentLang = $icl_get_languages[ $ICL_LANGUAGE_CODE ];

        if( $style == 'list'){
            echo '<div class="dnselect">';
            echo '<div class="dnselect__current">';
            echo '<a href="'. $currentLang['url'] .'">'. $currentLang['native_name'] .'</a>';
            echo '</div>';

            echo '<ul class="dnselect__content ul-list">';
            foreach ( $icl_get_languages as $key => $value) {
                $class_active = ($value['active']) ? 'active' : '';
                ?>
                <li class="li-item <?php echo $class_active ?>">
                    <a href="<?php echo $value['url'] ?>"><?= $value['native_name'] ?></a>
                </li>
                <?php
            }
            echo '</ul>';
            echo '</div>';

        }else{
            echo '<div class="languages-item">';
            echo "<img src='".get_theme_file_uri('assets/flag/'.$currentLang.'.png')."' width='".$flag_width."' height='".$flag_height."' >";
            echo '</div>';
            echo '<ul class="">';
            foreach ( $icl_get_languages as $key => $value) {
                $class_active = ($value['active']) ? 'active' : '';
                ?>
                <li class="<?php echo $class_active ?>">
                    <a href="<?php echo $value['url'] ?>">
                    <img src="<?php echo get_theme_file_uri('assets/flag/'.$value['code'].'.png') ?>" width="<?php echo $flag_width ?>" height="<?php echo $flag_height; ?>" >
                    </a>
                </li>
                <?php
            }
            echo '</ul>';
        }

    }else{
        echo 'Plugin wpml-translation-management not active';
    }
}

function dntheme_archive_description($before, $after)
{
    $before = '<div class="taxonomy-description">';
    $after = '</div>';
    $description = get_the_archive_description();

    $tag_more = "<p><!--more--></p>";
    $offset = strpos($description, $tag_more);

    if($offset){
        $result = str_replace($tag_more, "<div class='readmore__btn js-readmore__btn'>".__('Xem thêm','dntheme')."</div><div class='readmore__content js-readmore__content'>", $description);
        $description = $result."</div><div class='readmore__btn js-readmore__btn2'>".__('Rút gọn','dntheme')."</div>";
    }
    if ( $description ) {
        echo $before . $description . $after;
    }
}
