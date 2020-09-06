<?php
$fastshop_enable_popup      = fastshop_get_option( 'fastshop_enable_popup' );
$fastshop_popup_title       = fastshop_get_option( 'fastshop_popup_title', 'SAVE 25%' );
$fastshop_popup_subtitle    = fastshop_get_option( 'fastshop_popup_subtitle', 'SIGN UP FOR EMAILS' );
$fastshop_popup_description = fastshop_get_option( 'fastshop_popup_description', '' );

$fastshop_popup_input_placeholder = fastshop_get_option( 'fastshop_popup_input_placeholder', 'Enter your email adress' );
$fastshop_popup_butotn_text       = fastshop_get_option( 'fastshop_popup_button_text', 'Sign-Up' );
$fastshop_poppup_background       = fastshop_get_option( 'fastshop_poppup_background', '' );

if ( $fastshop_enable_popup == 1 ) :
	?>
    <!--  Popup Newsletter-->
    <div class="modal fade" id="popup-newsletter" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" <?php if ( $fastshop_poppup_background ): ?> style="background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $fastshop_poppup_background, 'full' ) ); ?>)" <?php endif; ?>>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times-circle"></i>
                </button>
                <div class="modal-inner">
					<?php if ( $fastshop_popup_title ): ?>
                        <h2 class="title"><?php echo esc_html( $fastshop_popup_title ); ?></h2>
					<?php endif; ?>
					<?php if ( $fastshop_popup_subtitle ): ?>
                        <h3 class="sub-title"><?php echo esc_html( $fastshop_popup_subtitle ); ?></h3>
					<?php endif; ?>
					<?php if ( $fastshop_popup_description ): ?>
                        <div class="description"><?php echo force_balance_tags( $fastshop_popup_description ); ?></div>
					<?php endif; ?>
                    <div class="newsletter-form-wrap">
                        <input class="email" type="email" name="email"
                               placeholder="<?php echo esc_html( $fastshop_popup_input_placeholder ); ?>">
                        <button type="submit" name="submit_button" class="btn-submit submit-newsletter">
							<?php echo esc_html( $fastshop_popup_butotn_text ); ?>
                        </button>
                    </div>
                    <div class="checkbox btn-checkbox">
                        <label>
                            <input class="fastshop_disabled_popup_by_user" type="checkbox">
                            <span><?php echo esc_html__( 'Don&rsquo;t show this popup again', 'fastshop' ); ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div><!--  Popup Newsletter-->
<?php endif;