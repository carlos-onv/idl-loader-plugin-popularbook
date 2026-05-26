<?php
/**
 * Template Name: Parents Club Landing (No Sidebar, Full Width)
 * Post Type: page
 * 
 * Custom full-width page template for Parents Club landing pages.
 * Programmatically overrides theme sidebars and boxed containers.
 */

// Force Porto theme to render full-width layout without sidebars
global $porto_layout;
$porto_layout = 'fullwidth';

get_header();
?>
<div id="content" role="main" class="parents-club-landing-wrapper">
    <?php while (have_posts()):
        the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>