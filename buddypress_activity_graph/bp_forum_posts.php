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
					"SELECT topic_title, COUNT( topic_title ) AS forum_count
					FROM " . $wpdb->prefix . "bb_posts p, " . $wpdb->prefix . "bb_topics t, " . $wpdb->prefix . "bb_forums f
					WHERE t.forum_id = p.forum_id
					AND t.forum_id = f.forum_id
					AND forum_order = " . $bp->groups->current_group->id
			);
			
			$data = array();
			
			if($myrows[0]->topic_title!=""){
										
				foreach($myrows as $row){
				
					$obj = new StdClass();
					$obj->label = ucfirst(str_replace("_"," ",$row->topic_title));
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