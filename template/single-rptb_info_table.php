<?php
 /*Template Name: Template for displaying 'Responsive Pricing & Info Tables'
 */
get_header();
?>
<div id="content" role="main">
    <div id="primary">
    <?php while ( have_posts() ) : the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding:15px;">
            <header class="entry-header">
				<h2><?php the_title(); ?></h2>
            </header>
 
            <!-- Display rptb table -->
            <div class="entry-content">
				<?php echo do_shortcode('[rptb_table id='. get_the_ID() . ']');;?>
			</div>
        </article>
 
    <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>