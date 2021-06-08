<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 *
 * HOOK AFTER BLOG CONTENT
 */
add_action( 'azirspares_after_blog_content', 'azirspares_paging_nav', 10 );
/**
 *
 * HOOK TEMPLATE
 */
if ( ! function_exists( 'azirspares_paging_nav' ) ) {
	function azirspares_paging_nav() {
		global $wp_query;
		$max = $wp_query->max_num_pages;
		// Don't print empty markup if there's only one page.
		if ( $max >= 2 ) {
			echo get_the_posts_pagination( array(
				                               'screen_reader_text' => '&nbsp;',
				                               'before_page_number' => '',
				                               'prev_text'          => '<i class="fa fa-angle-left"></i>',
				                               'next_text'          => '<i class="fa fa-angle-right"></i>',
			                               )
			);
		}
	}
}
if ( ! function_exists( 'azirspares_post_thumbnail' ) ) {
	function azirspares_post_thumbnail() {
		$blog_style = Azirspares_Functions::azirspares_get_option( 'azirspares_blog_style', 'standard' );
		$check      = apply_filters( 'azirspares_remove_resize_image', false );
		$thumb      = apply_filters( 'azirspares_resize_image', get_post_thumbnail_id(), 360, 297, true, true );
		if ( has_post_thumbnail() ) :
			?>
            <div class="post-thumb">
				<?php
				if ( is_single() ) {
					the_post_thumbnail( 'full' );
				} else {
					echo '<a href="' . get_the_permalink() . '">';
					if ( $blog_style == 'grid' && $check == false ) {
						echo wp_specialchars_decode( $thumb['img'] );
					} else {
						the_post_thumbnail( 'full' );
					}
					echo '</a>';
				}
				?>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'azirspares_post_format' ) ) {
	function azirspares_post_format() {
		$azirspares_blog_style = Azirspares_Functions::azirspares_get_option( 'azirspares_blog_style', 'standard' );
		$azirspares_post_meta  = get_post_meta( get_the_ID(), '_custom_metabox_post_options', true );
		$gallery_post          = isset( $azirspares_post_meta['gallery_post'] ) ? $azirspares_post_meta['gallery_post'] : '';
		$video_post            = isset( $azirspares_post_meta['video_post'] ) ? $azirspares_post_meta['video_post'] : '';
		$quote_post            = isset( $azirspares_post_meta['quote_post'] ) ? $azirspares_post_meta['quote_post'] : '';
		$post_format           = get_post_format();
		$class                 = 'post-thumb';
		$check                 = false;
		if ( $gallery_post != '' && $post_format == 'gallery' ) {
			$check = true;
		}
		if ( $video_post != '' && $post_format == 'video' ) {
			$check = true;
		}
		$width  = 1400;
		$height = 970;
		if ( has_post_thumbnail() ) :
			?>
            <div class="<?php echo esc_attr( $class ); ?>">
				<?php
				if ( $check == true && $azirspares_blog_style != 'grid' ) {
					if ( $post_format == 'gallery' ) :
						$gallery_post = explode( ',', $gallery_post );
						?>
                        <div class="owl-slick"
                             data-slick='{"arrows": true, "dots": false, "infinite": false, "slidesToShow": 1}'>
                            <figure>
								<?php
								$image_thumb = apply_filters( 'azirspares_resize_image', get_post_thumbnail_id(), $width, $height, true, true );
								echo wp_specialchars_decode( $image_thumb['img'] );
								?>
                            </figure>
							<?php foreach ( $gallery_post as $item ) : ?>
                                <figure>
									<?php
									$image_gallery = apply_filters( 'azirspares_resize_image', $item, $width, $height, true, true );
									echo wp_specialchars_decode( $image_gallery['img'] );
									?>
                                </figure>
							<?php endforeach; ?>
                        </div>
					<?php endif;
					if ( $post_format == 'video' ) {
						the_widget( 'WP_Widget_Media_Video', 'url=' . $video_post . '' );
					}
				} else {
					if ( is_single() ) {
						the_post_thumbnail( 'full' );
					} else {
						$image_thumb = apply_filters( 'azirspares_resize_image', get_post_thumbnail_id(), $width, $height, true, true );
						echo '<a href="' . get_permalink() . '">';
						echo wp_specialchars_decode( $image_thumb['img'] );
						echo '</a>';
					}
				}
				?>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'azirspares_post_author' ) ) {
	function azirspares_post_author() {
		?>
        <div class="post-author">
			<?php echo esc_html__( 'By:', 'azirspares' ); ?><a
                    href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
				<?php the_author(); ?>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_comment' ) ) {
	function azirspares_post_comment() {
		?>
        <div class="post-comment">
            <a href="<?php the_permalink(); ?>">
				<?php
				comments_number(
					esc_html__( '0 Comments', 'azirspares' ),
					esc_html__( '1 Comment', 'azirspares' ),
					esc_html__( '% Comments', 'azirspares' )
				);
				?>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_comment_icon' ) ) {
	function azirspares_post_comment_icon() {
		?>
        <div class="post-comment-icon">
            <a href="<?php the_permalink(); ?>">
				<?php
				comments_number(
					esc_html__( '0', 'azirspares' ),
					esc_html__( '1', 'azirspares' ),
					esc_html__( '%', 'azirspares' )
				);
				?>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_callback_comment' ) ) {
	/**
	 * Azirspares comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args    the comment args.
	 * @param int   $depth   the comment depth.
	 *
	 * @since 1.0.0
	 */
	function azirspares_callback_comment( $comment, $args, $depth ) {
		if ( 'div' == $args['style'] ) {
			$tag       = 'div ';
			$add_below = 'comment';
		} else {
			$tag       = 'li ';
			$add_below = 'div-comment';
		}
		?>
        <<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php echo get_comment_ID(); ?>">
        <div class="comment_container">
            <div class="comment-avatar">
				<?php echo get_avatar( $comment, 120 ); ?>
            </div>
            <div class="comment-text commentmetadata">
                <strong class="comment-author vcard">
					<?php printf( wp_kses_post( '%s', 'azirspares' ), get_comment_author_link() ); ?>
                </strong>
				<?php if ( '0' == $comment->comment_approved ) : ?>
                    <em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'azirspares' ); ?></em>
                    <br/>
				<?php endif; ?>
                <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( get_comment_ID() ) ) ); ?>"
                   class="comment-date">
					<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
                </a>
				<?php edit_comment_link( __( 'Edit', 'azirspares' ), '  ', '' ); ?>
				<?php comment_reply_link( array_merge( $args, array(
					'add_below' => $add_below,
					'depth'     => $depth,
					'max_depth' => $args['max_depth']
				) ) ); ?>
				<?php echo ( 'div' != $args['style'] ) ? '<div id="div-comment-' . get_comment_ID() . '" class="comment-content">' : '' ?>
				<?php comment_text(); ?>
				<?php echo 'div' != $args['style'] ? '</div>' : ''; ?>
            </div>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_title' ) ) {
	function azirspares_post_title() {
		?>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_readmore' ) ) {
	function azirspares_post_readmore() {
		?>
        <a href="<?php the_permalink(); ?>"
           class="readmore"><?php echo esc_html__( 'Read more', 'azirspares' ); ?></a>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_excerpt' ) ) {
	function azirspares_post_excerpt() {
		?>
        <div class="post-content">
			<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 50, esc_html__( '...', 'azirspares' ) ); ?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_full_content' ) ) {
	function azirspares_post_full_content() {
		?>
        <div class="post-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				             esc_html__( 'Continue reading %s', 'azirspares' ),
				             the_title( '<span class="screen-reader-text">', '</span>', false )
			             )
			);
			wp_link_pages( array(
				               'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'azirspares' ) . '</span>',
				               'after'       => '</div>',
				               'link_before' => '<span>',
				               'link_after'  => '</span>',
			               )
			);
			?>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_date' ) ) {
	function azirspares_post_date() {
		?>
        <div class="date">
            <a href="<?php the_permalink(); ?>">
				<?php echo get_the_date(); ?>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_post_datebox' ) ) {
	function azirspares_post_datebox() {
		$archive_year  = get_the_time( 'Y' );
		$archive_month = get_the_time( 'm' );
		$archive_day   = get_the_time( 'd' );
		?>
        <div class="datebox">
            <a href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day ); ?>">
				<?php echo get_the_date( 'd.M' ); ?>
                <span></span>
            </a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'azirspares_share_button' ) ) {
	function azirspares_share_button() {
		$enable_share_post     = Azirspares_Functions::azirspares_get_option( 'enable_share_post', '' );
		$share_image_url       = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
		$share_link_url        = get_permalink( get_the_ID() );
		$share_link_title      = get_the_title();
		$share_twitter_summary = get_the_excerpt();
		$twitter               = 'https://twitter.com/share?url=' . $share_link_url . '&text=' . $share_twitter_summary;
		$facebook              = 'https://www.facebook.com/sharer.php?s=100&title=' . $share_link_title . '&url=' . $share_link_url;
		$google                = 'https://plus.google.com/share?url=' . $share_link_url . '&title=' . $share_link_title;
		$pinterest             = 'http://pinterest.com/pin/create/button/?url=' . $share_link_url . '&description=' . $share_twitter_summary . '&media=' . $share_image_url[0];
		if ( $enable_share_post == 1 ):
			?>
            <div class="azirspares-share-socials">
                <h5 class="social-heading"><?php echo esc_html__( 'Share: ', 'azirspares' ) ?></h5>
                <a target="_blank" class="facebook"
                   href="<?php echo esc_url( $facebook ); ?>"
                   title="<?php echo esc_attr( 'Facebook' ) ?>"
                   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                    <i class="fa fa-facebook-f"></i>
                </a>
                <a target="_blank" class="twitter"
                   href="<?php echo esc_url( $twitter ); ?>"
                   title="<?php echo esc_attr( 'Twitter' ) ?>"
                   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                    <i class="fa fa-twitter"></i>
                </a>
                <a target="_blank" class="pinterest"
                   href="<?php echo esc_url( $pinterest ); ?>"
                   title="<?php echo esc_attr( 'Pinterest' ) ?>"
                   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                    <i class="fa fa-pinterest"></i>
                </a>
                <a target="_blank" class="googleplus"
                   href="<?php echo esc_url( $google ); ?>"
                   title="<?php echo esc_attr( 'Google+' ) ?>"
                   onclick='window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;'>
                    <i class="fa fa-google-plus"></i>
                </a>
            </div>
			<?php
		endif;
	}
}
if ( ! function_exists( 'azirspares_post_tags' ) ) {
	function azirspares_post_tags() {
		if ( ! empty( get_the_terms( get_the_ID(), 'post_tag' ) ) ) : ?>
            <div class="tags"><?php $tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ) {
					printf( esc_html__( '%1$s', 'azirspares' ), $tags_list );
				} ?></div>
		<?php endif;
	}
}
if ( ! function_exists( 'azirspares_post_category' ) ) {
	function azirspares_post_category() {
		$items          = array();
		$taxonomy_names = get_post_taxonomies();
		if ( isset( $taxonomy_names[0] ) && ! empty( $taxonomy_names ) ) {
			$get_terms = get_the_terms( get_the_ID(), $taxonomy_names[0] );
		} else {
			$get_terms = array();
		}
		if ( ! is_wp_error( $get_terms ) && ! empty( $get_terms ) ) : ?>
            <div class="categories">
                <span><?php echo esc_html__( 'Categories: ', 'azirspares' ) ?></span>
				<?php
				foreach ( $get_terms as $term ) {
					$link    = get_term_link( $term->term_id, $taxonomy_names[0] );
					$items[] = '<a href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a>';
				}
				echo join( ', ', $items );
				?>
            </div>
		<?php endif;
	}
}