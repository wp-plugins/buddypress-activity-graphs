<?PHP

	class bp_status_updates{
		
		static $instance;
		
		function bp_status_updates(){
		}
		
		
		function __construct(){
		}
		
		function ajax_action(){
			
			global $wpdb, $bp;
			
			$myrows = $wpdb->get_results(
				"SELECT content FROM " . $wpdb->prefix . "bp_activity where type='activity_update' and item_id=" . $bp->groups->current_group->id
			);
			
			$conversations = new StdClass();
			
			foreach($myrows as $row){
			
				$words = explode(" ", strip_tags($row->content));
				
				foreach($words as $word){
				
					if(substr($word,0,1)==="@"){
					
						if(isset($conversations->{str_replace("@", "", $word)})){
						
							$conversations->{str_replace("@", "", $word)}++;
						
						}else{
						
							$conversations->{str_replace("@", "", $word)}=1;
						
						}
					
					}
				
				}
			
			}
			
			$data = array();
			
			foreach($conversations as $key => $name){
			
				$obj = new StdClass();
				$obj->label = ucfirst(str_replace("_"," ",$key));
				$obj->value = $name;
			
				array_push($data, $obj);
				
			}
			
			if(count($data)!=0){
			
				echo json_encode($data);
				
			}else{

				$error = new StdClass();
				$error->error = "No mentions found.";
				
				array_push($data,$error);

				echo json_encode($data);

			}
			
			die();
		}
		
		function setup(){
		
			$bp_status_updates = new StdClass();
			
			$bp_status_updates->name = "Status Updates";
			$bp_status_updates->ajax_action = "status_updates";
			$bp_status_updates->js_function = "draw_status_pie";
			$bp_status_updates->obj = $this;
			
			return $bp_status_updates;
			
		}
		
	}

?>