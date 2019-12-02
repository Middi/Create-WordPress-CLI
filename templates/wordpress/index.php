<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wordpress_template_theme
 */

get_header();
?>

<main>

    <div class="favourite">


        <?php $sticky_id = get_option('my_sticky_post');
        $args = array(
            'p' => $sticky_id,
            'posts_per_page' => 1
        );
        $stickyPost = new WP_Query($args);

        if ($stickyPost->have_posts()) : $stickyPost->the_post();

                if (has_post_thumbnail($post->ID)) : ?>
                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>

                    <div class="featured" style="background: linear-gradient(rgba(0,62,80,0.3),rgba(0,62,80,0.8)), url(<?php echo $image[0]; ?>); background-size:cover; background-position: center center;">

                        <div class="featured-info">
                            <h1><a class="featured-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                            <?php
                            $author_id = get_post_field('post_author', $cause_id);
                            $display_name = get_the_author_meta('display_name', $author_id); ?>
                            <p class="featured-meta"><span><?php the_author_posts_link(); ?> </span>- <?php echo get_the_date(); ?></p>
                        </div>

                    </div>

                <?php endif;

    endif;
    wp_reset_postdata(); ?>

    </div>
    <div class="container">


        <h1 class="page-title">Latest Posts</h1>

        <div id="card-container" class="card-container">


            <?php $custom_query = new WP_Query('posts_per_page=6'); // exclude category 9
            while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                <?php if (has_post_thumbnail($post->ID)) : ?>
                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>



                    <a class="card-title" href="<?php the_permalink(); ?>">
                        <div class="card">
                            <div class="card-image" style="background: url(<?php echo $image[0]; ?>); background-size:cover; background-position: center center;">

                            </div>
                            <div class="card-text">
                                <h4><a class="card-title" href="<?php the_permalink(); ?>"><?php echo mb_strimwidth(get_the_title(), 0, 50, '...'); ?></a></h4>

                                <?php
                                $author_id = get_post_field('post_author', $cause_id);
                                $display_name = get_the_author_meta('display_name', $author_id); ?>
                                <p class="card-content"><?php echo get_the_excerpt(); ?></p>

                                <div class="card-meta">
                                    <?php echo get_avatar(get_the_author_meta('user_email'), $size = '30'); ?>
                                    <div class="card-meta-text">
                                        <p class="card-username">
                                            <?php the_author_posts_link(); ?>
                                        </p>
                                        <p class="card-date">

                                            <?php echo get_the_date(); ?>
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </a>

                <?php endif; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata();
            ?>

        </div>
        <div class="card-container">

            <a class="btn" href="/blog/posts">All Posts</a>
        </div>

    </div>
</main>
<?php get_footer();