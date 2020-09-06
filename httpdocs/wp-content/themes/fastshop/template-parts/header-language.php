<?php if( class_exists('SitePress') ):
	global $sitepress;

	$current_language = $sitepress->get_current_language();
	$active_languages = $sitepress->get_ls_languages();

	?>
	<li class="menu-item menu-item-has-children switcher-language dropdown">
		<a  role="button" href="#" class="switcher-trigger language-toggle" data-toggle="dropdown">
			<?php foreach( $active_languages as $key=>$value ) : ?>
				<?php if ( $value['active'] == 1 ) : ?>
					<img class="switcher-flag icon" alt="flag" src="<?php echo esc_url( $value['country_flag_url'] ); ?>" />
				<?php endif; ?>
			<?php endforeach; ?>
			<span><?php echo ICL_LANGUAGE_NAME_EN; ?></span>
		</a>
		<ul class="submenu">
			<?php foreach( $active_languages as $key=>$value ) : ?>
				<li class="switcher-option">
					<a href="<?php echo esc_url( $value['url'] ); ?>">
						<?php if( !empty( $value['country_flag_url'] ) ) : ?>
							<img class="switcher-flag icon" alt="flag" src="<?php echo esc_url( $value['country_flag_url'] ); ?>" />
						<?php endif; ?>
						<?php echo esc_html( $value['native_name'] ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</li>
<?php endif; ?>