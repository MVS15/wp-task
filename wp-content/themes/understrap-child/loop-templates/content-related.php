<div class="related mt-5">
	<h2 class="mb-4">Связанные объявления</h2>
	
	<?php
	$query = new WP_Query([
		'posts_per_page' => 10,
		'orderby' => 'date',
		'order' => 'desc',
		'post_type' => 'estate',
		'post_status' => 'publish',
		'meta_key' => 'city_id',
		'meta_value' => get_the_ID(),
	]);
	
	if ( $query->have_posts() ) {
		// Start the Loop.
		while ( $query->have_posts() ) {
			$query->the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'loop-templates/content', get_post_format() );
		}
	} else {
		get_template_part( 'loop-templates/content', 'none' );
	}
	
	wp_reset_postdata();
	?>
</div>