<?php

/**
 *	ACF Admin Columns
 *
 */

 function add_acf_columns ( $columns ) {
   return array_merge ( $columns, array ( 
     'publish' => __ ( 'Show Live' ),
     // 'end_date'   => __ ( 'Ends' ) 
   ) );
 }
 add_filter ( 'manage_food_menus_posts_columns', 'add_acf_columns' );

  /*
 * Add columns to food_menus CPT
 */
 function hosting_custom_column ( $column, $post_id ) {
   switch ( $column ) {
     case 'publish':
       echo get_post_meta ( $post_id, 'publish', true );
       break;
     case 'end_date':
       echo get_post_meta ( $post_id, 'hosting_end_date', true );
       break;
   }
}
add_action ( 'manage_food_menus_posts_custom_column', 'hosting_custom_column', 10, 2 );

 /*
 * Add Sortable columns
 */

// function my_column_register_sortable( $columns ) {
// 	$columns['start_date'] = 'start_date';
// 	$columns['end_date'] = 'start_date';
// 	return $columns;
// }
// add_filter('manage_edit-food_menus_sortable_columns', 'my_column_register_sortable' );
