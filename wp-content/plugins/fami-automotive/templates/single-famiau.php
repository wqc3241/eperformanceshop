<?php
/**
 * The Template for displaying all single famiau (automotive listing single page)
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'famiau' ); ?>

<?php while ( have_posts() ) : the_post(); ?>
	
	<?php famiau_get_template_part( 'content', 'single-famiau' ); ?>

<?php endwhile; // end of the loop. ?>

<?php get_footer( 'famiau' );

