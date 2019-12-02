<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package wordpress_template_theme
 */

get_header();
?>

<main>
	
	<article class="article-container">
		<div class="title">
			<h1 class="h1-title"><?php echo get_the_title($post_id); ?></h1>
		</div>
		<?php if (has_post_thumbnail($post->ID)) : ?>
			<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
			<figure class="post-featured-image" style="background: url(<?php echo $image[0]; ?>); background-size: cover; 
        background-position: top center;">
				
			</figure>
			
		<?php endif; ?>

		<?php
			$user = wp_get_current_user();
			
			if ( $user ) :
				?>
				<div class="article-meta">	
					<a href="<?php echo get_author_posts_url( $user->ID); ?>">
						<img src="<?php echo esc_url( get_avatar_url( $user->ID, ['size' => '48'] ) ); ?>" class="avatar" />
					</a>
					<div class="article-meta-info">
						<p class="small">
							<a href="<?php echo get_author_posts_url( $user->ID); ?>"><?php echo ($user->display_name); ?></a></p>
						<p class="small">
							<?php echo get_the_date(); ?>
						</p>   
					</div>
					            
				</div>
		
			<?php endif; ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</article>
</main>


<?php
get_footer();
