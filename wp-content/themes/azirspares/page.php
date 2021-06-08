<?php get_header(); ?>
<?php
$sidebar_isset = wp_get_sidebars_widgets();
/* Data MetaBox */
$data_meta = get_post_meta(get_the_ID(), '_custom_page_side_options', true);
/* Data MetaBox */
$data_meta_banner = get_post_meta(get_the_ID(), '_custom_metabox_theme_options', true);
/*Default page layout*/
$azirspares_page_extra_class = isset($data_meta['page_extra_class']) ? $data_meta['page_extra_class'] : '';
$azirspares_page_layout = isset($data_meta['sidebar_page_layout']) ? $data_meta['sidebar_page_layout'] : 'left';
$azirspares_page_sidebar = isset($data_meta['page_sidebar']) ? $data_meta['page_sidebar'] : 'widget-area';
if (isset($sidebar_isset[$azirspares_page_sidebar]) && empty($sidebar_isset[$azirspares_page_sidebar])) {
    $azirspares_page_layout = 'full';
}
/*Main container class*/
$azirspares_main_container_class = array();
$azirspares_main_container_class[] = $azirspares_page_extra_class;
$azirspares_main_container_class[] = 'main-container';
if ($azirspares_page_layout == 'full') {
    $azirspares_main_container_class[] = 'no-sidebar';
} else {
    $azirspares_main_container_class[] = $azirspares_page_layout . '-sidebar has-sidebar';
}
$azirspares_main_content_class = array();
$azirspares_main_content_class[] = 'main-content';
if ($azirspares_page_layout == 'full') {
    $azirspares_main_content_class[] = 'col-sm-12';
} else {
    $azirspares_main_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
}
$azirspares_slidebar_class = array();
$azirspares_slidebar_class[] = 'sidebar';
if ($azirspares_page_layout != 'full') {
    $azirspares_slidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
}
$css = '';
if (isset($data_meta_banner['bg_color_page']) && $data_meta_banner['bg_color_page'] != '') {
    $css = 'background-color:' . $data_meta_banner['bg_color_page'] . '';
}
?>
    <main style="<?php echo esc_attr($css); ?>"
          class="site-main <?php echo esc_attr(implode(' ', $azirspares_main_container_class)); ?>">
        <?php get_template_part('templates-part/page', 'banner'); ?>
        <div class="container">
            <?php if (isset($data_meta['bg_banner_page']) && $data_meta['bg_banner_page'] != '') :
                if (!is_front_page()) {
                    $args = array(
                        'container' => 'div',
                        'before' => '',
                        'after' => '',
                        'show_on_front' => true,
                        'network' => false,
                        'show_title' => true,
                        'show_browse' => false,
                        'post_taxonomy' => array(),
                        'labels' => array(),
                        'echo' => true,
                    );
                    do_action('azirspares_breadcrumb', $args);
                }
            endif; ?>
            <div class="row">
                <div class="<?php echo esc_attr(implode(' ', $azirspares_main_content_class)); ?>">
                    <?php if (isset($data_meta['bg_banner_page']) && $data_meta['bg_banner_page'] != '') : ?>
                        <h2 class="page-title">
                            <span><?php single_post_title(); ?></span>
                        </h2>
                    <?php endif;
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
                            ?>
                            <div class="page-main-content">
                                <?php
                                the_content();
                                wp_link_pages(array(
                                        'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'azirspares') . '</span>',
                                        'after' => '</div>',
                                        'link_before' => '<span>',
                                        'link_after' => '</span>',
                                        'pagelink' => '<span class="screen-reader-text">' . esc_html__('Page', 'azirspares') . ' </span>%',
                                        'separator' => '<span class="screen-reader-text">, </span>',
                                    )
                                );
                                ?>
                            </div>
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;
                        }
                    }
                    ?>
                </div>
                <?php if ($azirspares_page_layout != "full"):
                    if (is_active_sidebar($azirspares_page_sidebar)) : ?>
                        <div id="widget-area"
                             class="widget-area <?php echo esc_attr(implode(' ', $azirspares_slidebar_class)); ?>">
                            <?php dynamic_sidebar($azirspares_page_sidebar); ?>
                        </div><!-- .widget-area -->
                    <?php endif;
                endif; ?>
            </div>
        </div>
    </main>
<?php get_footer();