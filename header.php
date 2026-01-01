<?php
/**
 * The Header: Logo and main menu
 *
 * @package ALGENIX
 * @since ALGENIX 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( algenix_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'algenix_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'algenix_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('algenix_action_body_wrap_attributes'); ?>>

		<?php do_action( 'algenix_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'algenix_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('algenix_action_page_wrap_attributes'); ?>>

			<?php do_action( 'algenix_action_page_wrap_start' ); ?>

			<?php
			$algenix_full_post_loading = ( algenix_is_singular( 'post' ) || algenix_is_singular( 'attachment' ) ) && algenix_get_value_gp( 'action' ) == 'full_post_loading';
			$algenix_prev_post_loading = ( algenix_is_singular( 'post' ) || algenix_is_singular( 'attachment' ) ) && algenix_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $algenix_full_post_loading && ! $algenix_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="algenix_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'algenix_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'algenix' ); ?></a>
				<?php if ( algenix_sidebar_present() ) { ?>
				<a class="algenix_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'algenix_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'algenix' ); ?></a>
				<?php } ?>
				<a class="algenix_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'algenix_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'algenix' ); ?></a>

				<?php
				do_action( 'algenix_action_before_header' );

				// Header
				$algenix_header_type = algenix_get_theme_option( 'header_type' );
				if ( 'custom' == $algenix_header_type && ! algenix_is_layouts_available() ) {
					$algenix_header_type = 'default';
				}
				get_template_part( apply_filters( 'algenix_filter_get_template_part', "templates/header-" . sanitize_file_name( $algenix_header_type ) ) );

				// Side menu
				if ( in_array( algenix_get_theme_option( 'menu_side', 'none' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'algenix_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				if ( apply_filters( 'algenix_filter_use_navi_mobile', true ) ) {
					get_template_part( apply_filters( 'algenix_filter_get_template_part', 'templates/header-navi-mobile' ) );
				}

				do_action( 'algenix_action_after_header' );

			}
			?>

			<?php do_action( 'algenix_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( algenix_is_off( algenix_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $algenix_header_type ) ) {
						$algenix_header_type = algenix_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $algenix_header_type && algenix_is_layouts_available() ) {
						$algenix_header_id = algenix_get_custom_header_id();
						if ( $algenix_header_id > 0 ) {
							$algenix_header_meta = algenix_get_custom_layout_meta( $algenix_header_id );
							if ( ! empty( $algenix_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$algenix_footer_type = algenix_get_theme_option( 'footer_type' );
					if ( 'custom' == $algenix_footer_type && algenix_is_layouts_available() ) {
						$algenix_footer_id = algenix_get_custom_footer_id();
						if ( $algenix_footer_id ) {
							$algenix_footer_meta = algenix_get_custom_layout_meta( $algenix_footer_id );
							if ( ! empty( $algenix_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'algenix_action_page_content_wrap_class', $algenix_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'algenix_filter_is_prev_post_loading', $algenix_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( algenix_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'algenix_action_page_content_wrap_data', $algenix_prev_post_loading );
			?>>
				<?php
				do_action( 'algenix_action_page_content_wrap', $algenix_full_post_loading || $algenix_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'algenix_filter_single_post_header', algenix_is_singular( 'post' ) || algenix_is_singular( 'attachment' ) ) ) {
					if ( $algenix_prev_post_loading ) {
						if ( algenix_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) != 'article' ) {
							do_action( 'algenix_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$algenix_path = apply_filters( 'algenix_filter_get_template_part', 'templates/single-styles/' . algenix_get_theme_option( 'single_style' ) );
					if ( algenix_get_file_dir( $algenix_path . '.php' ) != '' ) {
						get_template_part( $algenix_path );
					}
				}

				// Widgets area above page
				$algenix_body_style   = algenix_get_theme_option( 'body_style' );
				$algenix_widgets_name = algenix_get_theme_option( 'widgets_above_page', 'hide' );
				$algenix_show_widgets = ! algenix_is_off( $algenix_widgets_name ) && is_active_sidebar( $algenix_widgets_name );
				if ( $algenix_show_widgets ) {
					if ( 'fullscreen' != $algenix_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					algenix_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $algenix_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'algenix_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $algenix_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'algenix_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'algenix_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="algenix_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( algenix_is_singular( 'post' ) || algenix_is_singular( 'attachment' ) )
							&& $algenix_prev_post_loading 
							&& algenix_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) == 'article'
						) {
							do_action( 'algenix_action_between_posts' );
						}

						// Widgets area above content
						algenix_create_widgets_area( 'widgets_above_content' );

						do_action( 'algenix_action_page_content_start_text' );
