<?php
if ( !class_exists( 'Azirspares_Welcome' ) ) {
	class Azirspares_Welcome
	{
		public $tabs = array();
		public $theme_name;

		public function __construct()
		{
			$this->set_tabs();
			$this->theme_name = wp_get_theme()->get( 'Name' );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		}

		public function admin_menu()
		{
			if ( current_user_can( 'edit_theme_options' ) ) {
				add_menu_page( 'Azirspares', 'Azirspares', 'manage_options', 'azirspares_menu', array( $this, 'welcome' ), AZIRSPARES_TOOLKIT_URL . '/assets/images/menu-icon.png', 2 );
				add_submenu_page( 'azirspares_menu', 'Azirspares Dashboard', 'Dashboard', 'manage_options', 'azirspares_menu', array( $this, 'welcome' ) );
			}
		}

		public function set_tabs()
		{
			$this->tabs = array(
				'dashboard' => esc_html__( 'Welcome', 'azirspares-toolkit' ),
				'demos'     => esc_html__( 'Import Data', 'azirspares-toolkit' ),
				'plugins'   => esc_html__( 'Plugins', 'azirspares-toolkit' ),
				'support'   => esc_html__( 'Support', 'azirspares-toolkit' ),
			);
		}

		public function active_plugin()
		{
			if ( empty( $_GET['magic_token'] ) || wp_verify_nonce( $_GET['magic_token'], 'panel-plugins' ) === false ) {
				esc_html_e( 'Permission denied', 'azirspares-toolkit' );
				die;
			}
			if ( isset( $_GET['plugin_slug'] ) && $_GET['plugin_slug'] != "" ) {
				$plugin_slug = $_GET['plugin_slug'];
				$plugins     = TGM_Plugin_Activation::$instance->plugins;
				foreach ( $plugins as $plugin ) {
					if ( $plugin['slug'] == $plugin_slug ) {
						activate_plugins( $plugin['file_path'] );
						?>
                        <script type="text/javascript">
                            window.location = "admin.php?page=azirspares_menu&tab=plugins";
                        </script>
						<?php
						break;
					}
				}
			}
		}

		public function deactivate_plugin()
		{
			if ( empty( $_GET['magic_token'] ) || wp_verify_nonce( $_GET['magic_token'], 'panel-plugins' ) === false ) {
				esc_html_e( 'Permission denied', 'azirspares-toolkit' );
				die;
			}
			if ( isset( $_GET['plugin_slug'] ) && $_GET['plugin_slug'] != "" ) {
				$plugin_slug = $_GET['plugin_slug'];
				$plugins     = TGM_Plugin_Activation::$instance->plugins;
				foreach ( $plugins as $plugin ) {
					if ( $plugin['slug'] == $plugin_slug ) {
						deactivate_plugins( $plugin['file_path'] );
						?>
                        <script type="text/javascript">
                            window.location = "admin.php?page=azirspares_menu&tab=plugins";
                        </script>
						<?php
						break;
					}
				}
			}
		}

		public function intall_plugin()
		{
		}

		/**
		 * Render HTML of intro tab.
		 *
		 * @return  string
		 */
		public function dashboard()
		{
			?>
            <div class="dashboard">
                <div class="dashboard-intro">
                    <h4 class="info-theme"><strong><?php echo ucfirst( esc_html( $this->theme_name ) ); ?></strong> is a modern, clean
                        and professional WooCommerce Wordpress Theme, It
                        is fully responsive, it looks stunning on all types of screens and devices.</h4>
                    <div class="image">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/theme-prev.jpg' ); ?>" alt="azirspares">
                    </div>
                    <div class="intro">
                        <h2>Quick Setings</h2>
                        <ul>
                            <li><a href="admin.php?page=azirspares_menu&tab=demos">Install Demos</a></li>
                            <li><a href="admin.php?page=azirspares_menu&tab=plugins">Install Plugins</a></li>
                            <li><a href="admin.php?page=azirspares">Theme Options</a></li>
                        </ul>
                    </div>
                </div>
            </div>
			<?php
			$this->support();
		}

		public function welcome()
		{
			/* deactivate_plugin */
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'deactivate_plugin' ) {
				$this->deactivate_plugin();
			}
			/* deactivate_plugin */
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'active_plugin' ) {
				$this->active_plugin();
			}
			$tab = 'dashboard';
			if ( isset( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			}
			?>
            <div class="azirspares-wrap">
                <div id="tabs-container" role="tabpanel">
                    <div class="nav-tab-wrapper">
						<?php foreach ( $this->tabs as $key => $value ): ?>
                            <a class="nav-tab azirspares-nav <?php if ( $tab == $key ): ?> active<?php endif; ?>"
                               href="admin.php?page=azirspares_menu&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></a>
						<?php endforeach; ?>
                    </div>
                    <div class="tab-content">
                        <?php $image_logo = AZIRSPARES_TOOLKIT_URL.'assets/images/logo.png';?>
                        <div class="logo-demo">
                            <img src="<?php echo esc_url($image_logo); ?>" alt="logo-demo">
                        </div>
                        <?php $this->$tab(); ?>
                    </div>
                </div>
            </div>
			<?php
		}

		public static function demos()
		{
			do_action( 'importer_page_content' );
		}

		public static function plugins()
		{
			$azirspares_tgm_theme_plugins = TGM_Plugin_Activation::$instance->plugins;
			$tgm                     = TGM_Plugin_Activation::$instance;
			?>
            <div class="plugins rp-row">
				<?php
				$wp_plugin_list = get_plugins();
				foreach ( $azirspares_tgm_theme_plugins as $azirspares_tgm_theme_plugin ) {
					if ( $tgm->is_plugin_active( $azirspares_tgm_theme_plugin['slug'] ) ) {
						$status_class = 'is-active';
						if ( $tgm->does_plugin_have_update( $azirspares_tgm_theme_plugin['slug'] ) ) {
							$status_class = 'plugin-update';
						}
					} else if ( isset( $wp_plugin_list[$azirspares_tgm_theme_plugin['file_path']] ) ) {
						$status_class = 'plugin-inactive';
					} else {
						$status_class = 'no-intall';
					}
					?>
                    <div class="rp-col">
                        <div class="plugin <?php echo esc_attr( $status_class ); ?>">
                            <div class="preview">
                                <?php if ( isset( $azirspares_tgm_theme_plugin['image'] ) && $azirspares_tgm_theme_plugin['image'] != "" ): ?>
                                    <img src="<?php echo esc_url( $azirspares_tgm_theme_plugin['image'] ); ?>"
                                         alt="azirspares">
                                <?php else: ?>
                                    <?php $image_plugin = AZIRSPARES_TOOLKIT_URL.'assets/images/'.$azirspares_tgm_theme_plugin['slug'].'.jpg';?>
                                    <img src="<?php echo esc_url( $image_plugin ); ?>"
                                         alt="azirspares">
                                <?php endif; ?>
                            </div>
                            <div class="plugin-name">
                                <h3 class="theme-name"><?php echo $azirspares_tgm_theme_plugin['name'] ?></h3>
                            </div>
                            <div class="actions">
                                <a class="button button-primary button-install-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'        => urlencode( $azirspares_tgm_theme_plugin['slug'] ),
												'tgmpa-install' => 'install-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Install', 'azirspares' ); ?></a>

                                <a class="button button-primary button-update-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'         => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'       => urlencode( $azirspares_tgm_theme_plugin['slug'] ),
												'tgmpa-update' => 'update-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Update', 'azirspares' ); ?></a>

                                <a class="button button-primary button-activate-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'azirspares_menu&tab=plugins',
											'plugin_slug' => urlencode( $azirspares_tgm_theme_plugin['slug'] ),
											'action'      => 'active_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>""><?php esc_html_e( 'Activate', 'azirspares' ); ?></a>
                                <a class="button button-secondary button-uninstall-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'azirspares_menu&tab=plugins',
											'plugin_slug' => urlencode( $azirspares_tgm_theme_plugin['slug'] ),
											'action'      => 'deactivate_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>""><?php esc_html_e( 'Deactivate', 'azirspares' ); ?></a>
                            </div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
		}

		public function support()
		{
			?>
            <div class="rp-row support-tabs">
                <div class="rp-col">
                    <div class="support-item doc-item">
                        <h3><?php esc_html_e( 'Documentation', 'azirspares-toolkit' ); ?></h3>
                        <p><?php esc_html_e( 'Here is our user guide for ' . ucfirst( esc_html( $this->theme_name ) ) . ', including basic setup steps, as well as ' . ucfirst( esc_html( $this->theme_name ) ) . ' features and elements for your reference.', 'azirspares-toolkit' ); ?></p>
                        <a target="_blank" href="<?php echo esc_url( 'http://docs.famithemes.com/docs/azirspares/' ); ?>"
                           class="button button-primary"><?php esc_html_e( 'Read Documentation', 'azirspares-toolkit' ); ?></a>
                    </div>
                </div>
                <div class="rp-col closed">
                    <div class="support-item video-item">
                        <h3><?php esc_html_e( 'Video Tutorials', 'azirspares-toolkit' ); ?></h3>
                        <p class="coming-soon"><?php esc_html_e( 'Video tutorials is the great way to show you how to setup ' . ucfirst( esc_html( $this->theme_name ) ) . ' theme, make sure that the feature works as it\'s designed.', 'azirspares-toolkit' ); ?></p>
                        <a href="#"
                           class="button button-primary disabled"><?php esc_html_e( 'See Video', 'azirspares-toolkit' ); ?></a>
                    </div>
                </div>
                <div class="rp-col">
                    <div class="support-item forum-item">
                        <h3><?php esc_html_e( 'Forum', 'azirspares-toolkit' ); ?></h3>
                        <p class="coming-soon"><?php esc_html_e( 'Can\'t find the solution on documentation? We\'re here to help, even on weekend. Just click here to start 1on1 chatting with us!', 'azirspares-toolkit' ); ?></p>
                        <a target="_blank" href="#"
                           class="button button-primary disabled"><?php esc_html_e( 'Request Support', 'azirspares-toolkit' ); ?></a>
                    </div>
                </div>
            </div>

			<?php
		}
	}

	new Azirspares_Welcome();
}
