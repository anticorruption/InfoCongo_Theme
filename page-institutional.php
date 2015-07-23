<?php
/*
 * Template Name: Institutional
 */
?>

<?php get_header(); ?>

<?php
// Slider
$slider_query = new WP_Query(array('post_type' => 'slider', 'posts_per_page' => 4));
if($slider_query->have_posts()) :
	$first_img = wp_get_attachment_image_src(get_post_thumbnail_id($slider_query->post->ID), 'full');
	?>
	<section id="slider">
<div class="slider-content": url(<?php echo $first_img[0]; ?>);">
			<?php while($slider_query->have_posts()) :
				$slider_query->the_post();
				$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-image="<?php echo $image[0]; ?>">
                    <div class="container">
                      <div class="twelve columns" align="center"> <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_big.png" class="logo" /> 
              
                <section class="post-content" align="center" >
										<h3><?php the_content(); ?></h3>
								</section>
						
</div>
					<a class="selector" href="#" data-item="post-<?php the_ID(); ?>">&nbsp;</a>
				</article>
			<?php endwhile; ?>
		</div>
        
	<?php
endif;
?>

<?php if(have_posts()) : the_post(); ?>

	<article class="single-post">
		<section id="stage" class="row">
			<div class="container">
				<div class="twelve columns">
					<header class="post-header">
						<?php echo get_the_term_list($post->ID, 'publisher', '', ', ', ''); ?>
						<h1 class="title"><?php the_title(); ?></h1>
					</header>
					<?php if(jeo_has_marker_location()) : ?>
						<div id="main-map" class="stage-map">
							<?php jeo_map(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<section id="content">
			<div class="container row">
				<div class="post-content">
					<div class="seven columns">
						<div class="post-description">
							<p class="date"><strong><?php echo get_the_date(); ?></strong></p>
							<?php the_content(); ?>
						</div>
					</div>
					<div class="five columns">
						<?php $thumbnail = infocongo_get_thumbnail(); ?>
						<div class="thumbnail">
							<?php if($thumbnail) : ?>
								<img src="<?php echo $thumbnail; ?>" />
							<?php endif; ?>
							<a class="button" href="<?php echo get_post_meta($post->ID, 'url', true); ?>" target="_blank"><?php _e('Go to the original article', 'infocongo'); ?></a>
							<p class="buttons">
								<a class="button embed-button" href="<?php echo jeo_get_share_url(array('p' => $post->ID)); ?>" target="_blank"><?php _e('Embed this story', 'infocongo'); ?></a>
								<a class="button print-button" href="<?php echo jeo_get_embed_url(array('p' => $post->ID)); ?>" target="_blank"><?php _e('Print', 'infocongo'); ?></a>
							</p>
							<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="verdana" data-action="recommend"></div>
							<div class="twitter-button">
								<a href="https://twitter.com/share" class="twitter-share-button" data-via="infocongo" <?php if(function_exists('qtrans_getLanguage')) : ?>data-lang="<?php echo qtrans_getLanguage(); ?>"<?php endif; ?>>Tweet</a>
							</div>
						</div>
					</div>
				</div>

				<script type="text/javascript">
					var embedUrl = jQuery('.embed-button').attr('href');
					var printUrl = jQuery('.print-button').attr('href');
					jeo.mapReady(function(map) {
						if(map.conf.postID) {
							jQuery('.print-button').attr('href', printUrl + '&map_id=' + map.conf.postID + '#print');
							jQuery('.embed-button').attr('href', embedUrl + '&map_id=' + map.conf.postID);
						}
					});
					jeo.groupReady(function(group) {
						jQuery('.print-button').attr('href', printUrl + '&map_id=' + group.currentMapID + '#print');
						jeo.groupChanged(function(group, prevMap) {
							jQuery('.print-button').attr('href', printUrl + '&map_id=' + group.currentMapID + '#print');
						});
					});
				</script>

			</div>
		</section>
	</article>
<?php endif; ?>

<?php
wp_reset_query();
?>

<?php get_template_part('section', 'main-widget'); ?>

<?php get_footer(); ?>