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

                            <div class="course_meta_time">

                                <?php // Course Start Time
                                   $course_start_time = get_post_meta( get_the_ID(), 'course_start_time', true );
                                   if ( !empty( $course_start_time ) ) {
                                ?>
	                                <li class="course_start_time">
	                                    <span class="course_profile_heading"><?php _e('Start Time and Date: ','iusetvis'); ?></span>
	                                    <span class="course_profile_meta">
                                            <?php 
                                                echo date_i18n( get_option( 'date_format' )  . ' - '.  get_option( 'time_format' ), $course_start_time );
                                            ?>
                                        </span>
	                                </li>
                                <?php } ?>

                                <?php // Subscriptions Dead End
                                   $course_subs_dead_end = get_post_meta( get_the_ID(), 'course_subs_dead_end', true );
                                   if ( !empty( $course_subs_dead_end ) ) {
                                ?>
                                    <li class="course_subs_dead_end">
                                        <span class="course_profile_heading"><?php _e('Subscriptions dead end Time and Date: ','iusetvis'); ?></span>
                                        <span class="course_profile_meta">
                                            <?php
                                                echo date_i18n( get_option( 'date_format' )  . ' - '.  get_option( 'time_format' ), $course_subs_dead_end );
                                            ?>
                                        </span>
                                    </li>
                                <?php } ?>

                            </div><!-- .course_meta_time -->

                            <div class="course_meta_credits">

                                <?php // Course Credits
                                   $course_credits_inst = get_post_meta( get_the_ID(), 'course_credits_inst', true );
                                   $course_credits_val = get_post_meta( get_the_ID(), 'course_credits_val', true );
                                   $course_credits_subj = get_post_meta( get_the_ID(), 'course_credits_subj', true );
                                   if ( !empty( $course_credits_inst ) && !empty( $course_credits_val ) && !empty( $course_credits_subj ) ) {
                                        $credits_format = __('This event is accounted by %1$s, according to the new Regulation in force since 01/01/2015, for <b>%2$s credits</b> in the subject %3$s.', 'iusetvis');
                                ?>
                                    <li class="course_credits">
                                        <span class="course_profile_heading"><?php _e('Credits: ','iusetvis'); ?></span>
                                        <span class="course_profile_meta">
                                            <?php echo sprintf($credits_format, $course_credits_inst, $course_credits_val, $course_credits_subj); ?>
                                        </span>
                                    </li>
                                <?php } ?>

                            </div><!-- .course_meta_credits -->

                            <div class="course_meta_price">

                                <?php // Price for associates
                                   $course_price_assoc = get_post_meta( get_the_ID(), 'course_price_assoc', true );
                                   if ( !empty( $course_price_assoc ) ) {
                                ?>
                                    <li class="course_price_assoc">
                                        <span class="course_profile_heading"><?php _e('Price for associates: ','iusetvis'); ?></span>
                                        <span class="course_profile_meta"><?php echo $course_price_assoc; ?></span>
                                    </li>
                                <?php } ?>

                                <?php // Regular price
                                   $course_price_reg = get_post_meta( get_the_ID(), 'course_price_reg', true );
                                   if ( !empty( $course_price_reg ) ) {
                                ?>
                                    <li class="course_price_reg">
                                        <span class="course_profile_heading"><?php _e('Regular price: ','iusetvis'); ?></span>
                                        <span class="course_profile_meta"><?php echo $course_price_reg; ?></span>
                                    </li>
                                <?php } ?>

                            </div><!-- .course_meta_price -->

                            <div class="course_meta_mod">

                                <?php // Course Moderator
                                   $course_mod_title = get_post_meta( get_the_ID(), 'course_mod_title', true );
                                   $course_mod_name = get_post_meta( get_the_ID(), 'course_mod_name', true );
                                   $course_mod_extra = get_post_meta( get_the_ID(), 'course_mod_extra', true );
                                    if ( !empty( $course_mod_title ) && !empty( $course_mod_name ) && !empty( $course_mod_extra ) ) {
                                        $mod_format = __('<b>%1$s %2$s</b>, %3$s.', 'iusetvis');
                                ?>
                                    <li class="course_moderators">
                                        <span class="course_profile_heading"><?php _e('Moderator: ','iusetvis'); ?></span>
                                        <span class="course_profile_meta">
                                            <?php echo sprintf($mod_format, $course_mod_title, $course_mod_name, $course_mod_extra); ?>
                                        </span>
                                    </li>
                                <?php } ?>

                            </div><!-- .course_meta_mod -->

                            <div class="course_meta_rel">

                                <?php // Course Relators
                                   $course_rel_title = maybe_unserialize( get_post_meta( get_the_ID(), 'course_rel_title', true ) );
                                   $course_rel_name = maybe_unserialize( get_post_meta( get_the_ID(), 'course_rel_name', true ) );
                                   $course_rel_extra = maybe_unserialize( get_post_meta( get_the_ID(), 'course_rel_extra', true ) );
                                   $rel_format = __('<b>%1$s %2$s</b>, %3$s.', 'iusetvis');
                                   for ($i=0; $i < count( $course_rel_name ); $i++) {
                                        if ( !empty( $course_rel_title[$i] ) && !empty( $course_rel_name[$i] ) && !empty( $course_rel_extra[$i] ) ) {                            
                                ?>
                                    <li class="course_relators">
                                        <span class="course_profile_heading"><?php _e('Relator: ','iusetvis'); ?></span>
                                        <span class="course_profile_meta">
                                            <?php echo sprintf($rel_format, $course_rel_title[$i], $course_rel_name[$i], $course_rel_extra[$i]); ?>
                                        </span>
                                    </li>
                                    <?php } 
                                    } ?>

                            </div><!-- .course_meta_rel -->

                        </ul>
                    </div><!-- .course_profile -->

                </div><!-- .course_profile_wrap -->

                <div class="course_actions_wrap">

                    <div class="course_hidden_fields">
                        <p id="hidden_user_id">
                            <?= get_current_user_id() ?>
                        </p>
                        <p id="hidden_course_id">
                            <?= get_the_ID() ?>
                        </p>
                    </div><!-- .course_hidden_fields -->

                    <div class="course_actions">                        
                        <?= do_shortcode('[course_subscribe]'); ?>
                        <?= do_shortcode('[course_unsubscribe]'); ?>
                        <?= do_shortcode('[course_waiting_list_subscribe]'); ?>
                        <?= do_shortcode('[bill_download]'); ?>
                        <?= do_shortcode('[notice_download]'); ?>
                        <?= do_shortcode('[diploma_download]'); ?>
                        <h3 id="actions_response_field"></h3>
                    </div><!-- .course_actions -->

                </div><!-- .course_actions_wrap -->

            </div><!-- .cpost-content-inner-wrap .course_post_list .entry-content -->

        </article>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>