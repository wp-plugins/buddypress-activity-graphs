<?PHP

	class bp_activity_breakdown{
		
		static $instance;
		
		function bp_activity_breakdown(){
		}
		
		
		function __construct(){
		}
		
		function ajax_action(){

			global $wpdb, $bp;
						
			$myrows = $wpdb->get_results(
					"SELECT type, count(type) as type_count FROM " . $wpdb->prefix . "bp_activity where item_id = " . $bp->groups->current_group->id . " group by type"
			);
			
			$data = array();
			
			foreach($myrows as $row){
			
				$obj = new StdClass();
				$obj->label = ucfirst(str_replace("_"," ",$row->type));
				$obj->value = $row->type_count;
			
				array_push($data, $obj);
				
			}
			
			if(count($data)!=0){
			
				echo json_encode($data);
				
			}else{

				$error = new StdClass();
				$error->error = "No activities found for this user.";
				
				array_push($data,$error);

				echo json_encode($data);

			}
			
			die(0);
			
		}
		
		function setup(){
		
			$bp_activity_breakdown = new StdClass();
			
			$bp_activity_breakdown->name = "Activity breakdown";
			$bp_activity_breakdown->ajax_action = "activity_breakdown";
			$bp_activity_breakdown->js_function = "draw_activity_pie";
			$bp_activity_breakdown->obj = $this;
			
			return $bp_activity_breakdown;
			
		}
		
	}

?>