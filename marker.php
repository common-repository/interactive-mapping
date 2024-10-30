<?php
 
 add_action( 'init', 'im_create_post_type_Marker' );
function im_create_post_type_Marker() {
 
	$labels = array(
			'name' => 'Marker',
			'singular_name' => 'Marker',
			'add_new' => 'Add Marker',
			'all_items' => 'All Markers',
			'add_new_item' => 'Add Marker',
			'edit_item' => 'Edit Marker',
			'new_item' => 'New Marker',
			'view_item' => 'View Marker',
			'search_items' => 'Search Marker',
			'not_found' => 'No Marker found',
			'not_found_in_trash' => 'No Marker found in trash',
			'parent_item_colon' => 'Parent Marker'
			//'menu_name' => default to 'name'
		);
  
  
  $args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array(
				'title',
				//'editor',
				//'excerpt',
				 'thumbnail',
				//'author',
				//'trackbacks',
				//'custom-fields',
				//'comments',
				//'revisions',
				//'page-attributes', // (menu order, hierarchical must be true to show Parent option)
				//'post-formats',
			),
		 	'taxonomies' => array( 'category'  ), // add default post categories and tags
		//	'menu_position' => 5,
			'exclude_from_search' => false,
		 	'register_meta_box_cb' => 'im_Marker_add_post_type_metabox'
		);
		
		
		
  
  register_post_type( 'acme_marker', $args  );
  
  
  
}



function im_Marker_add_post_type_metabox() {
 
		add_meta_box( 'im_Marker_metabox', 'Marker Details', 'im_Marker_metabox', 'acme_Marker', 'normal' );
 
	
	}
 
 


function im_Marker_metabox() {
	

	
	
	
		global $post;
		 
	 
		$marker_latitude = get_post_meta($post->ID, 'marker_latitude', true);
			$marker_longitude = get_post_meta($post->ID, 'marker_longitude', true);
				$marker_zoom_level = get_post_meta($post->ID, 'marker_zoom_level', true);
		 	$marker_label = get_post_meta($post->ID, 'marker_label', true); 
	 
		?>
 
		<table class="form-table">
			<tr>
				<th>
					<label>Enter Latitude</label>
				</th>
				<td>
					<input type="text" name="marker_latitude" class="regular-text" value="<?php echo $marker_latitude; ?>"> 
					 
				</td>
			</tr>
			
			
			<tr>
				<th>
					<label>Enter Longitude</label>
				</th>
				<td>
					<input type="text" name="marker_longitude" class="regular-text" value="<?php echo $marker_longitude; ?>"> 
					 
				</td>
			</tr>
			
			
			<tr>
				<th>
					<label>Zoom Level</label>
				</th>
				<td>
					<input type="text" name="marker_zoom_level" class="regular-text" value="<?php echo $marker_zoom_level; ?>"> 
				</td>
			</tr>
			
			
			<tr>
				<th>
					<label>Enter Marker Label </label>
				</th>
				<td>
					
					


				 

 <?php 
		 
		wp_editor(htmlspecialchars_decode($marker_label) , 'marker_label', array(
				 
		));

?>
		
				</td>
			</tr>
		</table>
	<?php
	}
 
 
 
 
 /**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function im_save_marker_meta( $post_id, $post, $update ) {


    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $slug = 'acme_marker';

    
    if ( $slug != $post->post_type ) {
        return;
   }

    // - Update the post's metadata.

    if ( isset( $_REQUEST['marker_latitude'] ) ) {
        update_post_meta( $post_id, 'marker_latitude', sanitize_text_field( $_REQUEST['marker_latitude'] ) );
    }

    if ( isset( $_REQUEST['marker_longitude'] ) ) {
        update_post_meta( $post_id, 'marker_longitude', sanitize_text_field( $_REQUEST['marker_longitude'] ) );
    }
	
	if ( isset( $_REQUEST['marker_zoom_level'] ) ) {
        update_post_meta( $post_id, 'marker_zoom_level', sanitize_text_field( $_REQUEST['marker_zoom_level'] ) );
    }
	
		if ( isset( $_REQUEST['marker_label'] ) ) {
			
			$marker_label = htmlspecialchars($_POST['marker_label']);
			
        update_post_meta( $post_id, 'marker_label', sanitize_text_field( $marker_label) );
    }
 
}
add_action( 'save_post', 'im_save_marker_meta', 10, 3 );

