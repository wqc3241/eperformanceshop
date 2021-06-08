<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Azirspares Post
 *
 * Displays Post widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Azirspares/Widgets
 * @version  1.0.0
 * @extends  AZIRSPARES_Widget
 */
if ( !class_exists( 'Azirspares_Post_Widget' ) ) {
	class Azirspares_Post_Widget extends AZIRSPARES_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$array_settings           = apply_filters( 'azirspares_filter_settings_widget_post',
				array(
					'title'     => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'azirspares-toolkit' ),
					),
					'type_post' => array(
						'type'    => 'select',
						'options' => array(
							'popular' => esc_html__( 'Popular Post', 'azirspares-toolkit' ),
							'recent'  => esc_html__( 'Recent Post', 'azirspares-toolkit' ),
						),
						'title'   => esc_html__( 'Posts Type', 'azirspares-toolkit' ),
					),
					'category'  => array(
						'type'           => 'select',
						'title'          => esc_html__( 'Category', 'azirspares-toolkit' ),
						'options'        => 'categories',
						'query_args'     => array(
							'orderby' => 'name',
							'order'   => 'ASC',
						),
						'default_option' => esc_html__( 'Select a category', 'azirspares-toolkit' ),
					),
					'orderby'   => array(
						'type'    => 'select',
						'options' => array(
							'date'          => esc_html__( 'Date', 'azirspares-toolkit' ),
							'ID'            => esc_html__( 'ID', 'azirspares-toolkit' ),
							'author'        => esc_html__( 'Author', 'azirspares-toolkit' ),
							'title'         => esc_html__( 'Title', 'azirspares-toolkit' ),
							'modified'      => esc_html__( 'Modified', 'azirspares-toolkit' ),
							'rand'          => esc_html__( 'Random', 'azirspares-toolkit' ),
							'comment_count' => esc_html__( 'Comment count', 'azirspares-toolkit' ),
							'menu_order'    => esc_html__( 'Menu order', 'azirspares-toolkit' ),
						),
						'title'   => esc_html__( 'Orderby', 'azirspares-toolkit' ),
					),
					'order'     => array(
						'type'    => 'select',
						'options' => array(
							'DESC' => esc_html__( 'DESC', 'azirspares-toolkit' ),
							'ASC'  => esc_html__( 'ASC', 'azirspares-toolkit' ),
						),
						'title'   => esc_html__( 'Order', 'azirspares-toolkit' ),
					),
					'number'    => array(
						'type'    => 'number',
						'default' => 4,
						'title'   => esc_html__( 'Posts Per Page', 'azirspares-toolkit' ),
					),
				)
			);
			$this->widget_cssclass    = 'widget-azirspares-post';
			$this->widget_description = esc_html__( 'Display the customer Post.', 'azirspares-toolkit' );
			$this->widget_id          = 'widget_azirspares_post';
			$this->widget_name        = esc_html__( 'Azirspares: Post', 'azirspares-toolkit' );
			$this->settings           = $array_settings;
			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance )
		{
			$this->widget_start( $args, $instance );
			ob_start();
			$args_loop = array(
				'post_type'           => 'post',
				'showposts'           => $instance['number'],
				'nopaging'            => 0,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'order'               => $instance['order'],
				'orderby'             => $instance['orderby'],
				'cat'                 => $instance['category'],
			);
			if ( $instance['type_post'] == 'popular' ) {
				$args_loop['meta_key'] = 'azirspares_post_views_count';
				$args_loop['olderby']  = 'meta_value_num';
			}
			$loop_posts = new WP_Query( $args_loop );
			if ( $loop_posts->have_posts() ) : ?>
                <div class="azirspares-posts">
					<?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post() ?>
                        <article <?php post_class(); ?>>
                            <div class="post-item-inner">
                                <div class="post-thumb">
                                    <a href="<?php the_permalink(); ?>">
										<?php
										$image_thumb = apply_filters( 'azirspares_resize_image', get_post_thumbnail_id(), 83, 83, true, true );
										echo wp_specialchars_decode( $image_thumb['img'] );
										?>
                                    </a>
                                </div>
                                <div class="post-info">
                                    <div class="block-title">
										<?php azirspares_post_title(); ?>
                                    </div>
                                    <div class="date"><?php echo get_the_date(); ?></div>
                                </div>
                            </div>
                        </article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
                </div>
			<?php else :
				get_template_part( 'content', 'none' );
			endif;
			echo apply_filters( 'azirspares_filter_widget_post', ob_get_clean(), $instance );
			$this->widget_end( $args );
		}
	}
}
add_action( 'widgets_init', 'Azirspares_Post_Widget' );
if ( !function_exists( 'Azirspares_Post_Widget' ) ) {
	function Azirspares_Post_Widget()
	{
		register_widget( 'Azirspares_Post_Widget' );
	}
}