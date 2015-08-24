<?PHP

	class bp_activity_graph{
		
		static $instance;
		
		function bp_activity_graph(){
		}
		
		
		function __construct(){
		}
		
		function ajax_action(){
			
			global $wpdb, $bp;
			
			$myrows = $wpdb->get_results(
					"SELECT distinct substring(`date_recorded`,1,10) as days FROM " . $wpdb->prefix . "bp_activity where item_id='" . $bp->groups->current_group->id . "' and secondary_item_id='0' order by days asc"
			);
			
			$data = array();
			
			foreach($myrows as $row){
			
				$inner_rows = $wpdb->get_var(
					"SELECT count(*) FROM " . $wpdb->prefix . "bp_activity where date_recorded like '" . $row->days . "%' and item_id='" . $bp->groups->current_group->id . "' and secondary_item_id='0'"
				);
				
				$obj = new StdClass();				
				$obj->label = $row->days;
				$obj->value = $inner_rows;
			
				array_push($data, $obj);

			}
			
			if(count($data)!=0){
			
				echo json_encode($data);
				
			}else{

				$error = new StdClass();
				$error->error = "No activities found.";
				
				array_push($data,$error);

				echo json_encode($data);

			}
			
			die();
		}
		
		function setup(){
		
			$bp_activity_graph = new StdClass();
			
			$bp_activity_graph->name = "Activity graph";
			$bp_activity_graph->ajax_action = "activity_graph";
			$bp_activity_graph->js_function = "bar_chart";
			$bp_activity_graph->obj = $this;
			
			return $bp_activity_graph;
			
		}
		
	}

?>