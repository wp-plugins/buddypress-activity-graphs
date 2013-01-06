<?PHP

	/*
	Plugin Name: Buddypress Activity Graphs
	Description: This plugin displays graphs for each user to allow activities on buddypress sites to be displayed as graphs 
	Version: 0.1
	Author: pgogy
	Plugin URI: http://www.pgogy.com/code/buddypress_activity_graph
	Author URI: http://www.pgogy.com
	*/
	
	class buddypress_activity_graph{

		function activity_graph_method(){
		
			wp_enqueue_script(
				'buddypress_activity_graph',
				plugins_url('/js/buddypress_activity_graph.js', __FILE__)
			);
			
			wp_enqueue_script(
				'd3',
				plugins_url('/js/d3.js', __FILE__)
			);
			
			wp_enqueue_script(
				'buddypress_activity_graph_visualisation',
				plugins_url('/js/buddypress_activity_graph_visualisation.js', __FILE__)
			);
			
			wp_enqueue_script(
				'd3_cloud',
				plugins_url('/js/d3.layout.cloud.js', __FILE__)
			);
			
		}    
 
		function activity_graph_nav() {
		
			  global $bp;
		 
			  bp_core_new_nav_item( 
				
				array( 
					'name' => __( 'Activities', 'buddypress' ), 
					'slug' => 'activity_graphs', 
					'position' => 75,
					'screen_function' => array($this, 'activity_graph_link'),
					'show_for_displayed_user' => true,
					'default_subnav_slug' => 'activity_graphs',
					'item_css_id' => 'activity_graphs'
				)
	 
			  );
		}
		 
		function activity_graph_title() {
			echo 'My Activities';
		}
		 
		function activity_graph_content() {
			
			$dir = opendir(dirname(__FILE__) . "/buddypress_activity_graph");
			
			$first = "";
			
			echo '<div class="item-list-tabs buddypress_activity_graph" role="navigation">';
			
			echo "<ul id='buddypress_activity_graph'>";
			
			while($file = readdir($dir)){
			
				if(strpos($file, ".php")!==FALSE){
			
					require_once(dirname(__FILE__) . "/buddypress_activity_graph/" . $file);
					$class_name = str_replace(".php","",$file);
					$plugin = new $class_name;
					$data = $plugin->setup();
					echo "<li id='" . $data->ajax_action . "' ";
					if($first==""){
					
						$first = "buddypress_activity_graph_javascript(\"" . $data->ajax_action . "\",\"" . $data->js_function . "\")";
					
					}
					echo "><a onclick='javascript:buddypress_activity_graph_javascript(\"" . $data->ajax_action . "\",\"" . $data->js_function . "\");' >" . $data->name . "</a></li>";
				
				}
			
			}
			
			echo "</ul></div>";
			
			echo "<div id='buddypress_activity_graph_ajax_response'></div>";
			echo "<script>" . $first . "</script>";
			
		}
		
		function activity_graph_ajax() {
			
			$dir = opendir(dirname(__FILE__) . "/buddypress_activity_graph");
			
			while($file = readdir($dir)){
			
				if(strpos($file, ".php")!==FALSE){
			
					require_once(dirname(__FILE__) . "/buddypress_activity_graph/" . $file);	
					$class_name = str_replace(".php","",$file);
					$plugin = new $class_name;
					$data = $plugin->setup();
					add_action('wp_ajax_' . $data->ajax_action, array( $data->obj, "ajax_action"));
					add_action('wp_ajax_nopriv_' . $data->ajax_action , array( $data->obj, "ajax_action"));
				
				}
			
			}
			
			
		}
		
		function activity_graph_link(){
		
			//add title and content here – last is to call the members plugin.php template

			add_action( "bp_template_title", array($this, "activity_graph_title") );

			add_action( "bp_template_content", array($this, "activity_graph_content") );

			bp_core_load_template( apply_filters( "bp_core_template_plugin", "members/single/plugins" ) );
		
		}
	
	}
	
	$bps = new buddypress_activity_graph;
	
	add_action('init', array($bps, 'activity_graph_ajax'));
	add_action('bp_setup_nav', array($bps, 'activity_graph_nav'), 1000 );
	add_action('wp_enqueue_scripts', array($bps, 'activity_graph_method'));
	
?>