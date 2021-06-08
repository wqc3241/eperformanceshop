<?php
/**
 * The Template for displaying automotive archives
 *
 */

defined( 'ABSPATH' ) || exit;

global $famiau;

get_header( 'famiau' );

$enable_filter_mobile = $famiau['_famiau_enable_filter_mobile'] == 'yes';
$show_mobile_filter   = wp_is_mobile() && $enable_filter_mobile;

$content_layout = isset( $_REQUEST['layout'] ) ? $_REQUEST['layout'] : 'list';
if ( $content_layout != 'grid' ) {
	$content_layout = '';
}

// Force grid on mobile
if (wp_is_mobile()) {
	$content_layout = 'grid';
}

$has_sidebar        = is_active_sidebar( 'famiau-sidebar' );
$content_wrap_class = 'famiau-content-wrap famiau-listings-wrap col-xs-12';
$sidebar_wrap_class = 'famiau-sidebar-wrap famiau-listings-wrap col-xs-12';
if ( $has_sidebar ) {
	$content_wrap_class .= ' col-sm-8 col-md-9';
	$sidebar_wrap_class .= ' col-sm-4 col-md-3';
}

?>
    <div class="famiau-main-wrap">
        <div class="famiau-archive-wrap">
            <div class="container famiau-container <?php echo $has_sidebar ? 'has-sidebar' : 'no-sidebar'; ?>">
				<?php if ( is_tax( 'famiau_cat' ) ) { ?>
                    <h1 class="archive-title famiau-archive-title"><?php the_archive_title( '', '' ); ?></h1>
					<?php
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				<?php } ?>

                <div class="row">

                    <div class="<?php echo esc_attr( $content_wrap_class ); ?>">
						<?php if ( ! $show_mobile_filter ) { ?>
                            <div class="famiau-filter-frontend-wrap">
								<?php famiau_mega_filter_frontend_html(); ?>
                            </div>
						<?php } ?>
						
						<?php if ( have_posts() ) { ?>
                        <div class="famiau-row row">
                            <div class="famiau-list-shorting-wrap col-xs-12">
                                <div class="famiau-listing-directory-title-wrap">
                                    <h3 class="famiau-listing-directory-title"><?php esc_html_e( 'Car For Sale', 'famiau' ); ?></h3>
                                </div>
								<?php famiau_get_template_part( 'listing-shorting', '' ); ?>
								<?php if ( $show_mobile_filter ) { ?>
                                    <a href="#" class="famiau-show-mobile-filter-popup"><i class="fa fa-search"></i></a>
                                    <div class="famiau-mobile-filter-frontend-wrap famiau-popup-window famiau-animated">
										<?php famiau_mobile_mega_filter_html(); ?>
                                    </div>
								<?php } ?>
                            </div>

                            <div class="famiau-listings auto-clear famiau-auto-clear">
								<?php while ( have_posts() ) { ?>
									<?php
									the_post();
									famiau_get_template_part( 'content-famiau', $content_layout );
									?>
								<?php } ?>
                                <div class="col-xs-12"><?php the_famiau_pagination(); ?></div>
								<?php } ?>
                            </div>
                        </div>
                    </div>
					
					<?php if ( $has_sidebar ) { ?>
                        <div class="<?php echo esc_attr( $sidebar_wrap_class ); ?>">
							<?php dynamic_sidebar( 'famiau-sidebar' ); ?>
                        </div>
					<?php } ?>

                </div>

            </div>
        </div>
    </div>
<?php
get_footer( 'famiau' );
