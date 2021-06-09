<?php
/*
*
*	River's Edge Shortcode

	[location_menu location="rivers-edge"]

	Shortcode Location parameters

		- rivers-edge
		- outfitters
		- pump-house-biergarten
		- the-market
		- the-trail-center
		- land-yacht
*
*/
function riversedge_shortcode( $atts ) {
	
	$a = shortcode_atts( array(
			// 'date' => date("m-d-Y"),
			'location' => '',
	), $atts );

	ob_start(); 
	
	$i = 0;
	$pbTitle = '';
	$location = $a['location'];
	// get the terms
	$ourTerm = get_term_by( 'slug', $location, 'fnb_location' );
	$termID = $ourTerm->term_id;
	// echo '<pre>';
	// print_r($ourTerm);
	// echo '</pre>';
	
	$logo = get_field( 'logo', 'fnb_location_'.$termID );
	$termDesc = term_description( $termID );

		// build the query
		$query = new WP_Query( array( 
			'post_type'=>'food_menus',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'fnb_location', 
					'field' => 'slug',
					'terms' => array(  $location ) 
				)
			)
		) );
		if( $query->have_posts() ): ?>
			<section class="the-menu">
				<?php if($logo) { ?>
					<div class="fnb-logo">
						<img src="<?php echo $logo['url']; ?>">
					</div>
					<div class="clear"></div>
				<?php } ?>
				<div class="fnb-wrap">
			<?php while( $query->have_posts() ): $query->the_post(); $i++; 

				// Do they want this live check
				$published = get_field('publish');
				if( $published != 'No' ):
					$title = get_the_title();
					// $pbTitle = get_field('page_break_title');
					$pageBreak = get_field('page_break');
					if( $pageBreak == 'yes' ) {
						$pClass = 'hide';
					} else {
						$pClass = 'show';
					}
					//echo $title;
					if( $pageBreak == 'yes' ) { ?>
						<div class="pagebreak"><h2><?php the_title(); ?></h2></div>
					<?php }

					?>
					<div class="fnb <?php echo $pClass; ?>">
						<?php 
					// echo 'works';
					$introText = get_field('intro_text');
					?>
					<h2 class="m-item"><?php the_title(); ?></h2>
					<?php
					if( $introText ) { ?>
						<div class="introtext"><?php echo $introText; ?></div>
					<?php } 
					//if( function_exists( get_field )) :
					 
						if( have_rows('menu_item') ) : ?>
							<div class="fnb-item">
								<?php while( have_rows('menu_item') ) : the_row();
									$name = get_sub_field('name');
									$price = get_sub_field('price');
									$description = get_sub_field('description');
									$note = get_sub_field('note');
									
							?>
							<div class="item-row">
								<?php if( $note == 'vegetarian' ) { ?>
									<div class="single"><div class="vegetarian ficon">Vegetarian</div></div>
								<?php } ?>
								<?php if( $note == 'gluten' ) { ?>
									<div class="single"><div class="gluten ficon">Gluten-Free</div></div>
								<?php } ?>
								<?php if( $note == 'both' ) { ?>
									<div class="both">
										<div class="gluten ficon">Gluten-Free</div>
										<div class="vegetarian ficon">Vegetarian</div>
									</div>
								<?php } ?>
								<h3><?php echo $name; ?></h3>
								<?php if( $price ) { ?><div class="price"><?php echo $price; ?></div><?php } ?>
								<?php if( $description ) { ?><div class="fdesc"><?php echo $description; ?></div><?php } ?>
							</div>
								<?php endwhile; ?>
							</div>
						<?php 
						endif; // end repeater loop ?>
						</div>
					<?php endif; ?>
				<?php //endif; // need to look up future proofing "get_field" contant
			endwhile; ?>
			</div><!-- fnb wrap -->
			<div class="bottom-section">
				<div class="fnb-ww-logo">
					Whitewater Center
				</div>
				<div class="notes">
					<div class="noter">
						<div class="flexer">
							<div class="vegetarian"></div><div class="thenote">Vegetarian</div>
						</div>
					</div>
					<div class="noter">
						<div class="flexer">
							<div class="gluten"></div><div class="thenote">Gluten Free</div>
						</div>
					</div>
				</div>
				<div class="term-desc"><?php echo $termDesc; ?></div>
			</div>
			
			</section>
		<?php endif;
		wp_reset_query(); ?>
	
	<?php 
	$content = wpautop(trim($content));
	return $content;
	// Spit everythng out
	return ob_get_clean();
}

add_shortcode( 'location_menu', 'riversedge_shortcode' );