<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Azirspares socials
 *
 * Displays socials widget.
 *
 * @author   Khanh
 * @category Widgets
 * @package  Azirspares/Widgets
 * @version  1.0.0
 * @extends  AZIRSPARES_Widget
 */
if ( !class_exists( 'Azirspares_Socials_Widget' ) ) {
	class Azirspares_Socials_Widget extends AZIRSPARES_Widget
	{
		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$socials     = array();
			$all_socials = cs_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $social ) {
					$socials[$key] = $social['title_social'];
				}
			}
			$array_settings           = apply_filters( 'azirspares_filter_settings_widget_socials',
				array(
					'title'         => array(
						'type'  => 'text',
						'title' => esc_html__( 'Title', 'azirspares-toolkit' ),
					),
					'azirspares_socials' => array(
						'type'    => 'checkbox',
						'class'   => 'horizontal',
						'title'   => esc_html__( 'Select Social', 'azirspares-toolkit' ),
						'options' => $socials,
					),
				)
			);
			$this->widget_cssclass    = 'widget-azirspares-socials';
			$this->widget_description = esc_html__( 'Display the customer Socials.', 'azirspares-toolkit' );
			$this->widget_id          = 'widget_azirspares_socials';
			$this->widget_name        = esc_html__( 'Azirspares: Socials', 'azirspares-toolkit' );
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
			$all_socials = cs_get_option( 'user_all_social' );
			ob_start();
			?>
            <div class="content-socials">
				<?php if ( !empty( $instance['azirspares_socials'] ) ) : ?>
                    <ul class="socials-list">
						<?php foreach ( $instance['azirspares_socials'] as $value ) : ?>
							<?php if ( isset( $all_socials[$value] ) ) :
								$array_socials = $all_socials[$value]; ?>
                                <li>
                                    <a href="<?php echo esc_url( $array_socials['link_social'] ) ?>"
                                       target="_blank">
                                        <span class="<?php echo esc_attr( $array_socials['icon_social'] ); ?>"></span>
										<?php echo esc_html( $array_socials['title_social'] ); ?>
                                    </a>
                                </li>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </div>
			<?php
			echo apply_filters( 'azirspares_filter_widget_socials', ob_get_clean(), $instance );
			$this->widget_end( $args );
		}
	}
}
/**
 * Register Widgets.
 *
 * @since 2.3.0
 */
function Azirspares_Socials_Widget()
{
	register_widget( 'Azirspares_Socials_Widget' );
}

add_action( 'widgets_init', 'Azirspares_Socials_Widget' );