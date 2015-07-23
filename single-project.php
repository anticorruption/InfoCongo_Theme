<?php get_header(); ?>

<section id="content">
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
	<div id="page" class="gray-page">
		<div class="container row">
			<div class="eight columns">
				<article id="post-<?php the_ID(); ?>" <?php post_class('main'); ?>>
						<header class="page-header">
							<h1><?php the_title(); ?></h1>
						</header>
						<aside class="social clearfix">
							<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="verdana"></div>
							<div class="twitter-button">
								<a href="https://twitter.com/share" class="twitter-share-button" data-via="infocongo" <?php if(function_exists('qtrans_getLanguage')) : ?>data-lang="<?php echo qtrans_getLanguage(); ?>"<?php endif; ?>>Tweet</a>
							</div>
						</aside>
						<section class="post-content">
							<?php the_content(); ?>
							<a href="<?php the_field('project_url'); ?>" title="<?php the_title(); ?>" rel="external" target="_blank" class="button"><?php _e('View this project', 'infocongo'); ?></a>
						</section>
				</article>
			</div>
		</div>
	</div>
<?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>