<?PHP

	/*
	Plugin Name: Buddypress Activity Graphs
	Description: This plugin displays graphs for each user to allow activities on buddypress sites to be displayed as graphs 
	Version: 0.4
	Author: pgogy
	*/
	
	class buddypress_activity_library{

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
			
			wp_register_style( 'buddypress_activity_graph_css', plugins_url('/css/buddypress_activity_graph.css', __FILE__) );
			wp_enqueue_style( 'buddypress_activity_graph_css' );
			
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
					add_action('wp_ajax_' . $data->ajax_action, array( $plugin, "ajax_action"));
					add_action('wp_ajax_nopriv_' . $data->ajax_action , array( $plugin, "ajax_action"));
				
				}
			
			}			
			
		}
		
		function bpag_init() {
			require_once("buddypress_activity_graph_lib.php");
			bp_register_group_extension( 'buddypress_activity_graph' );
		}
	
	}
	
	$bps = new buddypress_activity_library;
	
	add_action('init', array($bps, 'activity_graph_ajax'));
	add_action('wp_enqueue_scripts', array($bps, 'activity_graph_method'));	
	add_action('bp_init', array($bps, 'bpag_init') );
	
?>