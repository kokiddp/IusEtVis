<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/kokiddp/IusEtVis
 * @since      1.0.0
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix' ); ?>>

            <div class="post-content-inner-wrap course_post_list entry-content">

                <?php the_title( '<h2 class="course-title">', '</h2>' ); ?>

                <?php if ( has_post_thumbnail() ) { ?>

	                <div class="course_image_wrap">	                    
	                    <div class="course_image">
	                        <?php echo get_the_post_thumbnail( get_the_ID(), 'medium' ); ?>
	                    </div><!-- .course_image -->	                    
	                </div><!-- .course_image_wrap -->

                <?php } // end featured image check ?>

                <div class="course_profile_wrap">

					<div class="course_post_content">

                        <?php the_content(); ?>

                    </div><!-- .course_post_content -->

                    <div class="course_profile">
                        <ul>
                            <div class="course_meta_group1">

                                <?php // Course Time
                                   $course_time = get_post_meta( get_the_ID(), 'course_time', true );
                                   if ( !empty( $course_time ) ) {
                                ?>
	                                <li class="course_time">
	                                    <span class="beer_profile_heading"><?php _e('Time: ','iusetvis'); ?></span>
	                                    <span class="beer_profile_meta"><?php echo $course_time; ?></span>
	                                </li>
                                <?php } ?>

                            </div><!-- .course_meta_group1 -->
                        </ul>
                    </div><!-- .course_profile -->

                </div><!-- .course_profile_wrap -->

            </div><!-- .cpost-content-inner-wrap .course_post_list .entry-content -->

        </article>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>