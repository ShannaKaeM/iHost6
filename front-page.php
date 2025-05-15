<?php
/**
 * The template for displaying the front page
 *
 * @package MIIHost
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo esc_html(get_bloginfo('name')); ?></h1>
                <p class="hero-description"><?php echo esc_html(get_bloginfo('description')); ?></p>
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php endif; ?>
                <div class="hero-buttons">
                    <a href="#" class="button primary-button">Learn More</a>
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="button secondary-button">View Blog</a>
                </div>
            </div>
        </div>
    </div>

    <div class="featured-content container">
        <h2 class="section-title">Featured Content</h2>
        <div class="featured-posts">
            <?php
            // Display 3 recent posts
            $recent_posts = new WP_Query(array(
                'posts_per_page' => 3,
                'post_status' => 'publish',
            ));

            if ($recent_posts->have_posts()) :
                while ($recent_posts->have_posts()) :
                    $recent_posts->the_post();
            ?>
                <article class="featured-post">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="featured-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="post-content">
                        <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <div class="entry-meta">
                            <span class="posted-on"><?php echo get_the_date(); ?></span>
                        </div>
                        <div class="entry-summary">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <p>No posts found.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (is_active_sidebar('home-widgets')) : ?>
        <div class="home-widgets container">
            <?php dynamic_sidebar('home-widgets'); ?>
        </div>
    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();
