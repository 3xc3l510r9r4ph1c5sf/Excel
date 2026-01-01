<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package ALGENIX
 * @since ALGENIX 1.0
 */

if ( algenix_sidebar_present() ) {
	
	$algenix_sidebar_type = algenix_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $algenix_sidebar_type && ! algenix_is_layouts_available() ) {
		$algenix_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $algenix_sidebar_type ) {
		// Default sidebar with widgets
		$algenix_sidebar_name = algenix_get_theme_option( 'sidebar_widgets' );
		algenix_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $algenix_sidebar_name ) ) {
			dynamic_sidebar( $algenix_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$algenix_sidebar_id = algenix_get_custom_sidebar_id();
		do_action( 'algenix_action_show_layout', $algenix_sidebar_id );
	}
	$algenix_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $algenix_out ) ) {
		$algenix_sidebar_position    = algenix_get_theme_option( 'sidebar_position' );
		$algenix_sidebar_position_ss = algenix_get_theme_option( 'sidebar_position_ss', 'below' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $algenix_sidebar_position );
			echo ' sidebar_' . esc_attr( $algenix_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $algenix_sidebar_type );

			$algenix_sidebar_scheme = apply_filters( 'algenix_filter_sidebar_scheme', algenix_get_theme_option( 'sidebar_scheme', 'inherit' ) );
			if ( ! empty( $algenix_sidebar_scheme ) && ! algenix_is_inherit( $algenix_sidebar_scheme ) && 'custom' != $algenix_sidebar_type ) {
				echo ' scheme_' . esc_attr( $algenix_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="algenix_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'algenix_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $algenix_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$algenix_title = apply_filters( 'algenix_filter_sidebar_control_title', 'float' == $algenix_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'algenix' ) : '' );
				$algenix_text  = apply_filters( 'algenix_filter_sidebar_control_text', 'above' == $algenix_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'algenix' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $algenix_title ); ?>"><?php echo esc_html( $algenix_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'algenix_action_before_sidebar', 'sidebar' );
				algenix_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $algenix_out ) );
				do_action( 'algenix_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'algenix_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
