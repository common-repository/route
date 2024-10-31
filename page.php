<?php
	add_action( 'wp_head', array('Route','insert_route_tracker' ));	

  	class Route {

		 public static function insert_route_tracker() {
			$settings = (array) get_option( 'route_settings' );
			
			if(!isset($settings['organization_id'])) {
				self::no_route_organization_id_found();
				return false;
			}		
			
			require_once dirname(__FILE__) . '/route-js.php';
			return true;
		}

		 public static function no_route_organization_id_found() {
			echo "<!-- No Route Token Defined -->";
		}
  }
?>