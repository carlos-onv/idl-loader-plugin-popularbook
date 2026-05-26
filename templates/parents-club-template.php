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
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<style>
/* Clean Porto/Book-Junky Theme Overrides for True Fluid Full-Width Rendering */
body.page-template-parents-club-template {
    overflow-x: hidden !important;
}

/* Force container wrapper to expand edge-to-edge */
.page-template-parents-club-template #main,
.page-template-parents-club-template #main .container,
.page-template-parents-club-template #main .container-fluid,
.page-template-parents-club-template #content {
    max-width: 100% !important;
    width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* Remove default main section paddings */
.page-template-parents-club-template #main {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

/* Ensure the article content stretches 100% */
.page-template-parents-club-template article,
.page-template-parents-club-template .page-content {
    width: 100% !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
}

/* Guarantee full screen layout isolation for parents-club visual elements */
.parents-club-section,
#parents-club-section-1,
#parents-club-section-2,
#parents-club-section-3 {
    width: 100% !important;
    max-width: 100% !important;
    margin-left: auto !important;
    margin-right: auto !important;
}
</style>

<?php get_footer(); ?>
