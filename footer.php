<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package ALGENIX
 * @since ALGENIX 1.0
 */

							do_action( 'algenix_action_page_content_end_text' );
							
							// Widgets area below the content
							algenix_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'algenix_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'algenix_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'algenix_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'algenix_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$algenix_body_style = algenix_get_theme_option( 'body_style' );
					$algenix_widgets_name = algenix_get_theme_option( 'widgets_below_page', 'hide' );
					$algenix_show_widgets = ! algenix_is_off( $algenix_widgets_name ) && is_active_sidebar( $algenix_widgets_name );
					$algenix_show_related = algenix_is_single() && algenix_get_theme_option( 'related_position', 'below_content' ) == 'below_page';
					if ( $algenix_show_widgets || $algenix_show_related ) {
						if ( 'fullscreen' != $algenix_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $algenix_show_related ) {
							do_action( 'algenix_action_related_posts' );
						}

						// Widgets area below page content
						if ( $algenix_show_widgets ) {
							algenix_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $algenix_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'algenix_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'algenix_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! algenix_is_singular( 'post' ) && ! algenix_is_singular( 'attachment' ) ) || ! in_array ( algenix_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="algenix_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'algenix_action_before_footer' );

				// Footer
				$algenix_footer_type = algenix_get_theme_option( 'footer_type' );
				if ( 'custom' == $algenix_footer_type && ! algenix_is_layouts_available() ) {
					$algenix_footer_type = 'default';
				}
				get_template_part( apply_filters( 'algenix_filter_get_template_part', "templates/footer-" . sanitize_file_name( $algenix_footer_type ) ) );

				do_action( 'algenix_action_after_footer' );

			}
			?>

			<?php do_action( 'algenix_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'algenix_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'algenix_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>