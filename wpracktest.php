<?php
/*
Plugin Name: WPRackTest Plugin
Plugin URI: 
Description: WP plugin that includes phpRack code
Version: 0.1
Author: Alexander Yachmenev
Author URI: http://www.odesk.com/users/~~94ca72c849152a57
*/

if ( !class_exists( 'WPRackTest' ) ) {
	class WPRackTest {
		public $pluginUrl;	
		/**
		 * Initializes plugin variables and sets up wordpress hooks/actions.
		 *
		 * @return void
		 */
		function __construct( ) {
			$this->pluginDir		= basename(dirname(__FILE__));
			$this->pluginPath		= WP_PLUGIN_DIR . '/' . $this->pluginDir;
			$this->pluginUrl 		= WP_PLUGIN_URL.'/'.$this->pluginDir;		
			// Initiate the plugin
			add_action('init',  array(&$this, 'wp_racktest_init'));
			add_action('admin_menu', array(&$this, 'wp_racktest_admin_menu'));							
		}
		
		function wp_racktest_init() {
			if(is_admin()){
				// Admin interface init
				add_action('admin_init', array(&$this, 'wp_racktest_admin_init'));
			}
		}

		function wp_racktest_admin_init() {

		}
		
		function wp_racktest_admin_menu() {
			add_management_page( "WPRackTest", "WPRackTest", "administrator", 'wp_racktest', array( &$this, "wp_racktest_admin_screen" ) );				
			
		}

		function wp_racktest_admin_screen() {
			// 
			if( $curl = curl_init() ){
				// 
				curl_setopt($curl,CURLOPT_URL,$this->pluginUrl . '/phprack.php');
				// 
				curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
				// 
				$out = curl_exec($curl);
				// 
				echo $out;
				// 
				curl_close($curl);
			}
		}		
		
	}
	
	global $WPRackTest;
	$WPRackTest = new WPRackTest();	
}
