<?php
	/*
	Plugin Name: Route 
	Plugin URI: http://route.to
	Description: Now marketing automation is really for Marketers, not Devs! Use this plugin to insert the Route javascript tracker on your wordpress with no efforts.
	Author: Route
	License: Apache
	Version: 1.1
	Author URI: http://route.to
	*/

	namespace route_plugin;

	class Route {
		public function __construct() {
			if(!is_admin()) {		    
				require_once dirname( __FILE__ ) . '/page.php';
				return;
			}
									
			add_action('admin_menu', array($this, 'add_settings_page'));				
			add_action('admin_init', array($this, 'route_init'));				
	    }
                      
		public function add_settings_page() {	        
			add_options_page('Route Configuration', 'Route Configuration', 'manage_options', 'route-admin', array($this, 'create_settings_page'));
	    }
		
		public function create_settings_page() {
?>
			<div class="wrap">
			    <?php screen_icon(); ?>
			    <h2>Route Settings</h2>
				
			    <?php settings_errors(  ) ?>
				
			    <form method="post" action="options.php">
			    <?php
		            // This prints out all hidden setting fields
				    settings_fields('route_settings_group');
				    do_settings_sections('route_options');
				?>
			        <?php submit_button(); ?>
			    </form>
			</div>
<?php
	    }

		public function print_section_info() {
			print 'Enter your Route Organization ID on below field:';
	    }

		function organization_id_input( $args ) {
		    $name = esc_attr( $args['name'] );
		    $value = esc_attr( $args['value'] );
			
		    echo "<input type='text' name='$name' size='26' value='$value' />";
		}	   
                 
	    public function route_init() {
			register_setting('route_settings_group', 'route_settings');
	      	$settings = (array) get_option( 'route_settings' );
            
            // Now we set that function up to execute when the admin_notices action is called
            add_action( 'admin_notices', array($this, 'route_notice') );
            
	        add_settings_section(
			    'route_settings_group',
			    'Route Options',
			    array($this, 'print_section_info'),
			    'route_options'
			);

			add_settings_field(
			    'organization_id',
			    'Route Organization ID',
			    array($this, 'organization_id_input'), 
			    	'route_options',
			    	'route_settings_group', array(
				    	'name' => 'route_settings[organization_id]',
				    	'value' => $settings['organization_id'],
					)
			);		
		}
		
		public function route_notice() {
    		$class = 'notice notice-error';
    		$message =  sprintf( __('Route is disabled. Please go to the <a href="%s">plugin admin page</a> to enable Route.', 'route' ), admin_url( 'options-general.php?page=route-admin'));
			$settings = (array) get_option( 'route_settings' );    		
			$routeIsConfigured = !empty($settings['organization_id']);

    		if(is_plugin_active( 'route/route.php') && !$routeIsConfigured && substr( $_SERVER["PHP_SELF"], -11 ) == 'plugins.php' && function_exists( "admin_url"))
				printf( '<div class="%1$s"><strong><p>%2$s</p></strong></div>', $class, $message );     
        }
		
		public function validate($input) {
			$output = get_option( 'route_settings' );
		    if ( ctype_alnum( $input['organization_id'] ) ) {
		        $output['organization_id'] = $input['organization_id'];
		    } else {
		    	echo "Adding Error \n"; #die;
		        add_settings_error( 'mixpanel_options', 'organization_id', 'The Route Organization Id looks invalid.' );
		    }
		    return $output;
		}
	}
	
	$route = new \route_plugin\Route();
?>