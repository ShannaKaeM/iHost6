<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package MIIHost
 */

get_header();
?>

<main id="primary" class="site-main container">

	<section class="error-404 not-found">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'miihost' ); ?></h1>
		</header><!-- .page-header -->

		<div class="page-content">
			<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'miihost' ); ?></p>

			<?php get_search_form(); ?>

			<div class="widget-area error-404-widgets">
				<div class="widget">
					<h2 class="widget-title"><?php esc_html_e( 'Recent Posts', 'miihost' ); ?></h2>
					<ul>
						<?php
						wp_get_archives(
							array(
								'type'  => 'postbypost',
								'limit' => 5,
							)
						);
						?>
					</ul>
				</div>

				<div class="widget">
					<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'miihost' ); ?></h2>
					<ul>
						<?php
						wp_list_categories(
							array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 5,
							)
						);
						?>
					</ul>
				</div>
			</div><!-- .error-404-widgets -->
		</div><!-- .page-content -->
	</section><!-- .error-404 -->

</main><!-- #main -->

<?php
get_footer();
