<?PHP

	class bp_forum_posts{
		
		static $instance;
		
		function bp_forum_posts(){
		}
		
		
		function __construct(){
		}
		
		function ajax_action(){
			global $wpdb, $bp;
			
			$myrows = $wpdb->get_results(
					"SELECT forum_name, count(forum_name) as forum_count FROM " . $wpdb->prefix . "bp_activity, " . $wpdb->prefix . "bb_forums where forum_id = item_id and type like 'new_forum_%' and user_id='" . $bp->displayed_user->id . "'"
			);
			
			$data = array();
			
			if($myrows[0]->forum_name!=""){
										
				foreach($myrows as $row){
				
					$obj = new StdClass();
					$obj->label = ucfirst(str_replace("_"," ",$row->forum_name));
					$obj->value = $row->forum_count;
				
					array_push($data, $obj);
					
				}
				
				echo json_encode($data);
			
			}else{

				$error = new StdClass();
				$error->error = "No forum posts found for this user.";
				
				array_push($data,$error);

				echo json_encode($data);

			}
			
			die();
		}
		
		function setup(){
		
			$bp_forum_posts = new StdClass();
			
			$bp_forum_posts->name = "Forum Posts";
			$bp_forum_posts->ajax_action = "forum_posts";
			$bp_forum_posts->js_function = "draw_forum_pie";
			$bp_forum_posts->obj = $this;
			
			return $bp_forum_posts;
			
		}
		
	}

?>