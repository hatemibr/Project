<?php
if ( !class_exists( 'Fastshop_Welcome' ) ) {
	class Fastshop_Welcome
	{
		public $tabs = array();
		public $theme_name;

		public function __construct()
		{
			$this->set_tabs();
			$this->theme_name = wp_get_theme()->get( 'Name' );
			// Add action to enqueue scripts.
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
		}

		public function admin_menu()
		{
			if ( current_user_can( 'edit_theme_options' ) ) {
				add_menu_page( 'Fastshop', 'Fastshop', 'manage_options', 'fastshop_menu', array( $this, 'welcome' ), FASTSHOP_TOOLKIT_URL . '/assets/images/menu-icon.png', 2 );
				add_submenu_page( 'fastshop_menu', 'Fastshop Dashboard', 'Dashboard', 'manage_options', 'fastshop_menu', array( $this, 'welcome' ) );
			}
		}

		public function set_tabs()
		{
			$this->tabs = array(
				'dashboard' => esc_html__( 'Welcome', 'fastshop-toolkit' ),
				'plugins'   => esc_html__( 'Plugins', 'fastshop-toolkit' ),
				'support'   => esc_html__( 'Support', 'fastshop-toolkit' ),
			);
		}

		public function active_plugin()
		{
			if ( empty( $_GET['magic_token'] ) || wp_verify_nonce( $_GET['magic_token'], 'panel-plugins' ) === false ) {
				esc_html_e( 'Permission denied', 'fastshop-toolkit' );
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
                            window.location = "admin.php?page=fastshop_menu&tab=plugins";
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
				esc_html_e( 'Permission denied', 'fastshop-toolkit' );
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
                            window.location = "admin.php?page=fastshop_menu&tab=plugins";
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
                <h1>Wellcome to <?php echo ucfirst( esc_html( $this->theme_name ) ); ?></h1>
                <p class="about-text">Thanks for using our theme, we have worked very hard to release a great product
                    and we will do our absolute best to support this theme and fix all the issues. </p>
                <div class="dashboard-intro">
                    <div class="image">
                        <img src="<?php echo esc_url( get_theme_file_uri( '/screenshot.jpg' ) ); ?>" alt="">
                    </div>
                    <div class="intro">
                        <p><strong><?php echo ucfirst( esc_html( $this->theme_name ) ); ?></strong> is a modern, clean
                            and professional WooCommerce Wordpress Theme, It
                            is fully responsive, it looks stunning on all types of screens and devices.</p>
                        <h2>Quick Setings</h2>
                        <ul>
                            <li><a href="admin.php?page=fastshop_menu&tab=plugins">Install Plugins</a></li>
                            <li><a href="admin.php?page=fastshop">Theme Options</a></li>
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
            <div class="fastshop-wrap">
                <div id="tabs-container" role="tabpanel">
                    <div class="nav-tab-wrapper">
						<?php foreach ( $this->tabs as $key => $value ): ?>
                            <a class="nav-tab fastshop-nav <?php if ( $tab == $key ): ?> active<?php endif; ?>"
                               href="admin.php?page=fastshop_menu&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></a>
						<?php endforeach; ?>
                    </div>
                    <div class="tab-content">
						<?php $this->$tab(); ?>
                    </div>
                </div>
            </div>
			<?php
		}

		public static function plugins()
		{
			$fastshop_tgm_theme_plugins = TGM_Plugin_Activation::$instance->plugins;
			$tgm                        = TGM_Plugin_Activation::$instance;
			?>
            <div class="plugins rp-row">
				<?php
				$wp_plugin_list = get_plugins();
				foreach ( $fastshop_tgm_theme_plugins as $fastshop_tgm_theme_plugin ) {
					if ( $tgm->is_plugin_active( $fastshop_tgm_theme_plugin['slug'] ) ) {
						$status_class = 'is-active';
						if ( $tgm->does_plugin_have_update( $fastshop_tgm_theme_plugin['slug'] ) ) {
							$status_class = 'plugin-update';
						}
					} else if ( isset( $wp_plugin_list[$fastshop_tgm_theme_plugin['file_path']] ) ) {
						$status_class = 'plugin-inactive';
					} else {
						$status_class = 'no-intall';
					}
					?>
                    <div class="rp-col">
                        <div class="plugin <?php echo esc_attr( $status_class ); ?>">
                            <div class="preview">
								<?php if ( isset( $fastshop_tgm_theme_plugin['image'] ) && $fastshop_tgm_theme_plugin['image'] != "" ): ?>
                                    <img src="<?php echo esc_url( $fastshop_tgm_theme_plugin['image'] ); ?>" alt="">
								<?php else: ?>
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/framework/assets/images/no-image.jpg' ); ?>"
                                         alt="">
								<?php endif; ?>
                            </div>
                            <div class="plugin-name">
                                <h3 class="theme-name"><?php echo $fastshop_tgm_theme_plugin['name'] ?></h3>
                            </div>
                            <div class="actions">
                                <a class="button button-primary button-install-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'        => urlencode( $fastshop_tgm_theme_plugin['slug'] ),
												'tgmpa-install' => 'install-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Install', 'fastshop-toolkit' ); ?></a>

                                <a class="button button-primary button-update-plugin" href="<?php
								echo esc_url( wp_nonce_url(
										add_query_arg(
											array(
												'page'         => urlencode( TGM_Plugin_Activation::$instance->menu ),
												'plugin'       => urlencode( $fastshop_tgm_theme_plugin['slug'] ),
												'tgmpa-update' => 'update-plugin',
											),
											admin_url( 'themes.php' )
										),
										'tgmpa-install',
										'tgmpa-nonce'
									)
								);
								?>"><?php esc_html_e( 'Update', 'fastshop-toolkit' ); ?></a>

                                <a class="button button-primary button-activate-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'page=fastshop_menu&tab=plugins',
											'plugin_slug' => urlencode( $fastshop_tgm_theme_plugin['slug'] ),
											'action'      => 'active_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>""><?php esc_html_e( 'Activate', 'fastshop-toolkit' ); ?></a>
                                <a class="button button-secondary button-uninstall-plugin" href="<?php
								echo esc_url(
									add_query_arg(
										array(
											'page'        => 'page=fastshop_menu&tab=plugins',
											'plugin_slug' => urlencode( $fastshop_tgm_theme_plugin['slug'] ),
											'action'      => 'deactivate_plugin',
											'magic_token' => wp_create_nonce( 'panel-plugins' ),
										),
										admin_url( 'admin.php' )
									)
								);
								?>""><?php esc_html_e( 'Deactivate', 'fastshop-toolkit' ); ?></a>
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
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Documentation', 'fastshop-toolkit' ); ?></h3>
                        <p><?php esc_html_e( 'Here is our user guide for ' . ucfirst( esc_html( $this->theme_name ) ) . ', including basic setup steps, as well as ' . ucfirst( esc_html( $this->theme_name ) ) . ' features and elements for your reference.', 'fastshop-toolkit' ); ?></p>
                        <a target="_blank" href="<?php echo esc_url( home_url( '/changelog.txt' ) ); ?>"
                           class="button button-primary"><?php esc_html_e( 'Read Documentation', 'fastshop-toolkit' ); ?></a>
                    </div>
                </div>
                <div class="rp-col closed">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Video Tutorials', 'fastshop-toolkit' ); ?></h3>
                        <p class="coming-soon"><?php esc_html_e( 'Video tutorials is the great way to show you how to setup ' . ucfirst( esc_html( $this->theme_name ) ) . ' theme, make sure that the feature works as it\'s designed.', 'fastshop-toolkit' ); ?></p>
                        <a href="#"
                           class="button button-primary disabled"><?php esc_html_e( 'See Video', 'fastshop-toolkit' ); ?></a>
                    </div>
                </div>
                <div class="rp-col">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Forum', 'fastshop-toolkit' ); ?></h3>
                        <p><?php esc_html_e( 'Can\'t find the solution on documentation? We\'re here to help, even on weekend. Just click here to start 1on1 chatting with us!', 'fastshop-toolkit' ); ?></p>
                        <a target="_blank" href="http://support.kutethemes.net/support-system"
                           class="button button-primary"><?php esc_html_e( 'Request Support', 'fastshop-toolkit' ); ?></a>
                    </div>
                </div>
            </div>

			<?php
		}
	}

	new Fastshop_Welcome();
}
