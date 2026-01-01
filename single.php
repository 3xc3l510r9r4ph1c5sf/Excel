<?php
/**
 * The template to display single post
 *
 * @package ALGENIX
 * @since ALGENIX 1.0
 */

// Full post loading
$full_post_loading          = algenix_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = algenix_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = algenix_get_theme_option( 'posts_navigation_scroll_which_block', 'article' );

// Position of the related posts
$algenix_related_position   = algenix_get_theme_option( 'related_position', 'below_content' );

// Type of the prev/next post navigation
$algenix_posts_navigation   = algenix_get_theme_option( 'posts_navigation' );
$algenix_prev_post          = false;
$algenix_prev_post_same_cat = (int)algenix_get_theme_option( 'posts_navigation_scroll_same_cat', 1 );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( algenix_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	algenix_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'algenix_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $algenix_posts_navigation ) {
		$algenix_prev_post = get_previous_post( $algenix_prev_post_same_cat );  // Get post from same category
		if ( ! $algenix_prev_post && $algenix_prev_post_same_cat ) {
			$algenix_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $algenix_prev_post ) {
			$algenix_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $algenix_prev_post ) ) {
		algenix_sc_layouts_showed( 'featured', false );
		algenix_sc_layouts_showed( 'title', false );
		algenix_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $algenix_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'algenix_filter_get_template_part', 'templates/content', 'single-' . algenix_get_theme_option( 'single_style' ) ), 'single-' . algenix_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $algenix_related_position, 'inside' ) === 0 ) {
		$algenix_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'algenix_action_related_posts' );
		$algenix_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $algenix_related_content ) ) {
			$algenix_related_position_inside = max( 0, min( 9, algenix_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $algenix_related_position_inside ) {
				$algenix_related_position_inside = mt_rand( 1, 9 );
			}

			$algenix_p_number         = 0;
			$algenix_related_inserted = false;
			$algenix_in_block         = false;
			$algenix_content_start    = strpos( $algenix_content, '<div class="post_content' );
			$algenix_content_end      = strrpos( $algenix_content, '</div>' );

			for ( $i = max( 0, $algenix_content_start ); $i < min( strlen( $algenix_content ) - 3, $algenix_content_end ); $i++ ) {
				if ( $algenix_content[ $i ] != '<' ) {
					continue;
				}
				if ( $algenix_in_block ) {
					if ( strtolower( substr( $algenix_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$algenix_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $algenix_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $algenix_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$algenix_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $algenix_content[ $i + 1 ] && in_array( $algenix_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$algenix_p_number++;
					if ( $algenix_related_position_inside == $algenix_p_number ) {
						$algenix_related_inserted = true;
						$algenix_content = ( $i > 0 ? substr( $algenix_content, 0, $i ) : '' )
											. $algenix_related_content
											. substr( $algenix_content, $i );
					}
				}
			}
			if ( ! $algenix_related_inserted ) {
				if ( $algenix_content_end > 0 ) {
					$algenix_content = substr( $algenix_content, 0, $algenix_content_end ) . $algenix_related_content . substr( $algenix_content, $algenix_content_end );
				} else {
					$algenix_content .= $algenix_related_content;
				}
			}
		}

		algenix_show_layout( $algenix_content );
	}

	// Comments
	do_action( 'algenix_action_before_comments' );
	comments_template();
	do_action( 'algenix_action_after_comments' );

	// Related posts
	if ( 'below_content' == $algenix_related_position
		&& ( 'scroll' != $algenix_posts_navigation || (int)algenix_get_theme_option( 'posts_navigation_scroll_hide_related', 0 ) == 0 )
		&& ( ! $full_post_loading || (int)algenix_get_theme_option( 'open_full_post_hide_related', 1 ) == 0 )
	) {
		do_action( 'algenix_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $algenix_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $algenix_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $algenix_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $algenix_prev_post ) ); ?>"
			data-cur-post-link="<?php echo esc_attr( get_permalink() ); ?>"
			data-cur-post-title="<?php the_title_attribute(); ?>"
			<?php do_action( 'algenix_action_nav_links_single_scroll_data', $algenix_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
