<?PHP

	/*
	Plugin Name: Buddypress Activity Graphs
	Description: This plugin displays graphs for each user to allow activities on buddypress sites to be displayed as graphs 
	Version: 0.3
	Author: pgogy
	*/
	
	class buddypress_activity_graph extends BP_Group_Extension{

		var $lib;

		function __construct(){
		
			$this->lib = new buddypress_activity_library;
		
			$args = array( 
					'name' => __( 'Activities', 'buddypress' ), 
					'slug' => 'activity_graphs', 
					'position' => 75,
					'screen_function' => array($lib, 'activity_graph_link'),
					'show_for_displayed_user' => true,
					'default_subnav_slug' => 'activity_graphs',
					'item_css_id' => 'activity_graphs',
					'user_has_access' => true,
			);			
			parent::init( $args );
		}
		
		function display(){			
			$this->lib->activity_graph_content();		
		}
		
	}
	