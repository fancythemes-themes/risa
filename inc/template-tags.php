<?php
/**
 * Custom template tags for Risa
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * 
 * @package Risa
 * @since Risa 1.2
 */

/**
 * Prints HTML with meta information for the categories, tags.
 * This function replace the function in the parent theme
 *
 * @since Risa 1.0
 */
function twentyfifteen_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() ) {
		printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'risa' ) );
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'risa' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

	}

	if ( 'post' == get_post_type() ) {
		if ( is_singular() || is_multi_author() ) {
		}

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'risa' ) );
		if ( $categories_list && twentyfifteen_categorized_blog() ) {
			printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Categories', 'Used before category names.', 'risa' ),
				$categories_list
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'risa' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Tags', 'Used before tag names.', 'risa' ),
				$tags_list
			);
		}
	}

	if ( is_attachment() && wp_attachment_is_image() ) {
		// Retrieve attachment metadata.
		$metadata = wp_get_attachment_metadata();

		printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
			_x( 'Full size', 'Used before full size attachment link.', 'risa' ),
			esc_url( wp_get_attachment_url() ),
			$metadata['width'],
			$metadata['height']
		);
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'risa' ), get_the_title() ) );
		echo '</span>';
	}
}

/**
 * Prints HTML with meta information for the categories, tags.
 *
 * @since Risa 1.0
 */
function risa_entry_meta_header() {

	if ( 'post' == get_post_type() ) {

		echo get_avatar( get_the_author_meta( 'user_email' ), 40 );

		printf( '<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span>',
			_x( 'Author', 'Used before post author name.', 'risa' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);

		printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
			_x( 'Posted on', 'Used before publish date.', 'risa' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

}

/**
 * Get first blockquote from the post content
 * used for quote post format
 * 
 * @since Risa 1.0
 */
function risa_related_posts() {

	$current_id = get_the_ID();
	$tags = wp_get_post_tags( $current_id, array( 'fields' => 'ids' ) );

	$query = array (
			'posts__not_in' => array ( $current_id ),
			'tag__in' => $tags,
			'posts_per_page' => 4,
		);

	$result = new WP_Query( $query );
	
	if ( $result->have_posts() ) :
	?>
		<div id="related-posts" class="related-posts widget widget-recent-posts">
			<h3 class="related-title widget-title"><span><?php _e('Related Posts', 'risa'); ?></span></h3>
			<ul>
			<?php while ( $result->have_posts() ) : $result->the_post() ?>
				<li>
				<?php if ( has_post_thumbnail() ) : ?>
					<a class="post-thumbnail alignleft" href="<?php the_permalink(); ?>" aria-hidden="true">
						<?php the_post_thumbnail( 'thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
					</a>
				<?php endif; ?>
					<span class="entry-meta"><?php echo get_the_date(); ?></span>
					<span class="entry-title"><a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a></span>
				</li>
			<?php endwhile; ?>
			</ul>
		</div>
	<?php
	endif;	
	wp_reset_postdata();
}

/**
 * Render the footer credit, taken from theme options footer_credit.
 *
 * @since Risa 1.2
 *
 * @return void
 */
function risa_footer_credit() {
		if ( $footer_credit = get_theme_mod('footer_credit', false) ) : 
			echo risa_sanitize_footer_credit( $footer_credit );
		else : ?>
			<span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span> 
			<?php _e('Powered by', 'risa'); ?> <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'risa' ) ); ?>"><?php printf( __( 'WordPress', 'risa' ) ); ?></a> <?php _e('and designed by ', 'risa'); ?> <a href="<?php echo esc_url( __( 'https://fancythemes.com/', 'risa' ) ); ?>" rel="nofollow"><?php printf( __( 'FancyThemes', 'risa' ) ); ?></a>
		<?php
		endif;
}
