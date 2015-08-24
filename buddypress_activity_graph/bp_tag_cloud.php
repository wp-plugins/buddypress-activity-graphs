<?PHP

	class bp_tag_cloud{
		
		static $instance;
		
		function bp_tag_cloud(){
		}
		
		
		function __construct(){
		}
		
		function ajax_action(){
			
			global $wpdb, $bp;
			
			$myrows = $wpdb->get_results(
					"SELECT content FROM " . $wpdb->prefix . "bp_activity where item_id='" . $bp->groups->current_group->id . "' and content!=''"
			);
			
			$conversations = new StdClass();
			
			foreach($myrows as $row){
			
				$words = explode(" ", strip_tags($row->content));
				
				foreach($words as $word){
				
					if(isset($conversations->{$word})){
						
						$conversations->{$word}++;
						
					}else{
						
						$conversations->{$word}=1;
					
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
				$error->error = "No words found for this user.";
				
				array_push($data,$error);

				echo json_encode($data);

			}
			
			die();
		}
		
		function setup(){
		
			$bp_tag_cloud = new StdClass();
			
			$bp_tag_cloud->name = "Tag cloud";
			$bp_tag_cloud->ajax_action = "Tag cloud";
			$bp_tag_cloud->js_function = "tag_cloud";
			$bp_tag_cloud->obj = $this;
			
			return $bp_tag_cloud;
			
		}
		
	}

?>