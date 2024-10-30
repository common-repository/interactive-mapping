<?php
/*
Plugin Name: Interactive Mapping
Plugin URL: http://interactivemapping.co.uk/
Description: A plugin to create an interactive map using Open Street Maps and add markers
Version: 0.9
Author:  Cregy	, susheelhbti
Licence: GPL2+
*/

 add_action( 'init', 'im_create_post_type_map' );
function im_create_post_type_map() {
	
	$labels = array(
			'name' => 'Map',
			'singular_name' => 'Map',
			'add_new' => 'Add Map',
			'all_items' => 'All Maps',
			'add_new_item' => 'Add Map',
			'edit_item' => 'Edit Map',
			'new_item' => 'New Map',
			'view_item' => 'View Map',
			'search_items' => 'Search Map',
			'not_found' => 'No Map found',
			'not_found_in_trash' => 'No Map found in trash',
			'parent_item_colon' => 'Parent Map'
			
			
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
		
		
			),
		
		'exclude_from_search' => false,
		 	'register_meta_box_cb' => 'im_map_add_post_type_metabox'
		);
		
		
		
  
  register_post_type( 'acme_map', $args  );
  
  
  
}



function im_map_add_post_type_metabox() { // add the meta box
		add_meta_box( 'im_map_metabox', 'Map Details', 'im_map_metabox', 'acme_map', 'normal' );
		
		add_meta_box( 'im_map_shortcode', 'Map Shortcode', 'im_map_shortcode', 'acme_map', 'normal' );

		
	}
 
function im_map_shortcode() {
	  global $post; 
	
 echo "[CREATIVEMAP ID=\"". $post->ID."\"] ";  
	
	
}
function im_map_marker_box() {
		  global $post; 
	$marker_ID = get_post_meta($post->ID, 'marker_ID', true);
	
	 $args = array(
	
	
	
	
	'post_type'        => 'acme_marker',
	
	
	'post_status'      => 'publish'
	
	
);
$posts_array = get_posts( $args );


echo "<select name='marker_ID'>";
foreach($posts_array as $pa)
{
	if($marker_ID==$pa->ID )
	{
	 echo "<option selected value=".$pa->ID.">".$pa->post_title . "  </option>";
 	
	}
	
	else
	{ 

echo "<option value=".$pa->ID.">".$pa->post_title.    "</option>";
 
		
	}
	
 

 
 
}
echo "</select>";


 
	
}

function im_map_metabox() {
		global $post;
		
		
 
		// Get the data if its already been entered
		$map_latitude = get_post_meta($post->ID, 'map_latitude', true);
			$map_longitude = get_post_meta($post->ID, 'map_longitude', true);
				$map_zoom_level = get_post_meta($post->ID, 'map_zoom_level', true);
		 
		// Echo out the field
		?>
 
		<table class="form-table">
			<tr>
				<th>
					<label>Enter Latitude</label>
				</th>
				<td>
					<input type="text" name="map_latitude" class="regular-text" value="<?php echo $map_latitude; ?>"> 
					 
				</td>
			</tr>
			
			
			<tr>
				<th>
					<label>Enter Longitude</label>
				</th>
				<td>
					<input type="text" name="map_longitude" class="regular-text" value="<?php echo $map_longitude; ?>"> 
					 
				</td>
			</tr>
			
			
			<tr>
				<th>
					<label>Zoom Level</label>
				</th>
				<td>
					<input type="text" name="map_zoom_level" class="regular-text" value="<?php echo $map_zoom_level; ?>"> 
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
function im_save_map_meta( $post_id, $post, $update ) {

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $slug = 'acme_map';

    // If this isn't a 'map' post, don't update it.
    if ( $slug != $post->post_type ) {
        return;
    }

    // - Update the post's metadata.

    if ( isset( $_REQUEST['map_latitude'] ) ) {
        update_post_meta( $post_id, 'map_latitude', sanitize_text_field( $_REQUEST['map_latitude'] ) );
    }

    if ( isset( $_REQUEST['map_longitude'] ) ) {
        update_post_meta( $post_id, 'map_longitude', sanitize_text_field( $_REQUEST['map_longitude'] ) );
    }
	
	if ( isset( $_REQUEST['map_zoom_level'] ) ) {
        update_post_meta( $post_id, 'map_zoom_level', sanitize_text_field( $_REQUEST['map_zoom_level'] ) );
    }
	
	if ( isset( $_REQUEST['marker_ID'] ) ) {
        update_post_meta( $post_id, 'marker_ID', sanitize_text_field( $_REQUEST['marker_ID'] ) );
    }
		
 
}
add_action( 'save_post', 'im_save_map_meta', 10, 3 );

/// map short code begin
 
 

function im_map_func( $atts ) {
	 
		$map_latitude = get_post_meta( $atts['id'], 'map_latitude', true);
			$map_longitude = get_post_meta( $atts['id'], 'map_longitude', true);
				$map_zoom_level = get_post_meta( $atts['id'], 'map_zoom_level', true);
				
			// get marker id

  if ( isset($atts['markerid'] ) ) {

  
	$marker_ID =$atts['markerid'];// get_post_meta( $atts['id'], 'marker_ID', true);			
				
	$marker = get_post( $marker_ID );			
		
		 
		$marker_latitude = get_post_meta($marker->ID, 'marker_latitude', true);
			$marker_longitude = get_post_meta($marker->ID, 'marker_longitude', true);
				$marker_zoom_level = get_post_meta($marker->ID, 'marker_zoom_level', true);
				
				
				
		 	$marker_label = get_post_meta($marker->ID, 'marker_label', true);
			
		
			//echo $marker_label;
			
		$marker_label= 	htmlspecialchars_decode ($marker_label);
				$marker_label ="<h2>".get_the_title( $marker->ID)."</h2> ".str_replace("\"","\' ",$marker_label );
			
  }
 
 
   
 
 
 ?> <link rel="stylesheet" href="//cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
 <script src="//cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
 
 <div id="map"></div>
 

 
 
 <script>
 var map = L.map('map').setView([<?php echo $map_latitude; ?>,<?php echo $map_longitude; ?>], <?php echo $map_zoom_level; ?>);

L.tileLayer('//{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="//osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
 <?php if ( isset($atts['markerid'] ) ) {
 
	?>
L.marker([<?php echo $marker_latitude; ?>,<?php echo $marker_longitude; ?>]).addTo(map)
    .bindPopup("<?php echo  $marker_label; ?>");
	<?php 
}

 

 

?>

	</script>
	<?php 
	 
}
add_shortcode( 'CREATIVEMAP', 'im_map_func' );

  

add_action('wp_head','im_map_css');





function im_map_css() {

	$output="<style> #map{height:400px; } </style>";

	echo $output;

}



include "marker.php";