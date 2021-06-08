<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Azirspares instagram
 *
 * Displays instagram widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Azirspares/Widgets
 * @version  1.0.0
 * @extends  AZIRSPARES_Widget
 */
if ( !class_exists( 'Azirspares_Instagram_Widget' ) ) {
	class Azirspares_Instagram_Widget extends AZIRSPARES_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$array_settings           = apply_filters( 'azirspares_filter_settings_instagram_contact',
				array(
					'title'            => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'azirspares-toolkit' ),
					),
					'image_resolution' => array(
						'type'    => 'select',
						'title'   => esc_html__( 'Image Resolution', 'azirspares-toolkit' ),
						'options' => array(
							'thumbnail'           => esc_html__( 'Thumbnail', 'azirspares-toolkit' ),
							'low_resolution'      => esc_html__( 'Low Resolution', 'azirspares-toolkit' ),
							'standard_resolution' => esc_html__( 'Standard Resolution', 'azirspares-toolkit' ),
						),
						'default' => 'thumbnail',
					),
					'id_instagram'     => array(
						'type'  => 'text',
						'title' => esc_html__( 'ID Instagram', 'azirspares-toolkit' ),
					),
					'token'            => array(
						'type'  => 'text',
						'title' => esc_html__( 'Token Instagram', 'azirspares-toolkit' ),
						'desc'  => wp_kses( sprintf( '<a href="%s" target="_blank">' . esc_html__( 'Get Token Instagram Here!', 'azirspares-toolkit' ) . '</a>', 'https://instagram.pixelunion.net' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ),
					),
					'items_limit'      => array(
						'type'    => 'number',
						'default' => '5',
						'title'   => esc_html__( 'Items Instagram', 'azirspares-toolkit' ),
					),
				)
			);
			$this->widget_cssclass    = 'widget-azirspares-instagram';
			$this->widget_description = esc_html__( 'Display the customer Instagram.', 'azirspares-toolkit' );
			$this->widget_id          = 'widget_azirspares_instagram';
			$this->widget_name        = esc_html__( 'Azirspares: Instagram', 'azirspares-toolkit' );
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
			if ( intval( $instance['id_instagram'] ) === 0 || intval( $instance['token'] ) === 0 ) {
				echo '<strong>' . esc_html__( 'No user ID specified.', 'azirspares-toolkit' ) . '</strong>';
			} else {
				$response = wp_remote_get( 'https://api.instagram.com/v1/users/' . esc_attr( intval( $instance['id_instagram'] ) ) . '/media/recent/?access_token=' . esc_attr( $instance['token'] ) . '&count=' . esc_attr( $instance['items_limit'] ) );
				if ( !is_wp_error( $response ) ) {
					$response_body = json_decode( $response['body'] );
					if ( $response_body->meta->code !== 200 ) {
						echo '<p>' . esc_html__( 'User ID and access token do not match. Please check again.', 'azirspares-toolkit' ) . '</p>';
					}
					$items_as_objects = $response_body->data;
					$items            = array();
					if ( !empty( $items_as_objects ) ) {
						foreach ( $items_as_objects as $item_object ) {
							$item['link']     = $item_object->link;
							$item['user']     = $item_object->user;
							$item['likes']    = $item_object->likes;
							$item['comments'] = $item_object->comments;
							$item['src']      = $item_object->images->{$instance['image_resolution']}->url;
							$item['width']    = $item_object->images->{$instance['image_resolution']}->width;
							$item['height']   = $item_object->images->{$instance['image_resolution']}->height;
							$items[]          = $item;
						}
					}
				}
				if ( isset( $items ) && $items ):
					ob_start(); ?>
                    <div class="content-instagram">
						<?php foreach ( $items as $item ):
							$img_lazy = "data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20" . $item['width'] . "%20" . $item['height'] . "%27%2F%3E";
							?>
                            <a target="_blank" href="<?php echo esc_url( $item['link'] ) ?>" class="item">
                                <img class="img-responsive lazy" src="<?php echo esc_attr( $img_lazy ); ?>"
                                     data-src="<?php echo esc_url( $item['src'] ); ?>"
									<?php echo image_hwstring( $item['width'], $item['height'] ); ?>
                                     alt="<?php echo get_the_title(); ?>"/>
                            </a>
						<?php endforeach; ?>
                    </div>
					<?php
					echo apply_filters( 'azirspares_filter_widget_instagram', ob_get_clean(), $instance, $items );
				endif;
			}
			$this->widget_end( $args );
		}
	}
}
/**
 * Register Widgets.
 *
 * @since 2.3.0
 */
function Azirspares_Instagram_Widget()
{
	register_widget( 'Azirspares_Instagram_Widget' );
}

add_action( 'widgets_init', 'Azirspares_Instagram_Widget' );