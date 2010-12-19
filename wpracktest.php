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
			?>
			<div class="wrap">
			<?php screen_icon(); ?>
			<h2>WP Rack Test</h2>
			<?php
			if (isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'rack-test') {
			?>
				<h3 class='plugin-title'><strong><?php echo($_REQUEST['plugin']); ?></strong></h3>
			<?php	
				$this->wp_racktest_run($_REQUEST['plugin']);
			} else {
				$plugins = get_plugins();

				/* Plugins array. */
				$racktest_plugins = array();				
				$racktest_plugins = apply_filters( 'get_racktest_plugins', $racktest_plugins );			
				
				$plugin_keys = array_keys($plugins);
				foreach ($plugin_keys as $plugin_key) {
/*
					$plugin_files = get_plugin_files($plugin_key);
					foreach ($plugin_files as $plugin_file) {
						if (strpos($plugin_file, 'rack-test') !== false) {
							$racktest_plugins[ $plugin_key ] = $plugin_file;
							continue;
						}
					}
*/					
					if (!isset($racktest_plugins[ $plugin_key ])) {
						unset( $plugins[ $plugin_key ] );
					}
				}
				?>
				<table class="widefat" cellspacing="0" id="<?php echo $context ?>-plugins-table">
				<thead>
				<tr>
					<th scope="col" class="manage-column"><?php _e('Plugin'); ?></th>
					<th scope="col" class="manage-column"><?php _e('Description'); ?></th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th scope="col" class="manage-column"><?php _e('Plugin'); ?></th>
					<th scope="col" class="manage-column"><?php _e('Description'); ?></th>
				</tr>
				</tfoot>
				<tbody class="plugins">
				<?php

				if ( empty($plugins) ) {
					echo '<tr>
						<td colspan="2">' . __('No plugins to show') . '</td>
					</tr>';
				}
				foreach ( (array)$plugins as $plugin_file => $plugin_data) {
					// preorder
					$plugin = explode("/", $plugin_file);
					$plugin = $plugin[0];					
					$actions['Rack Test'] = '<a href="'. wp_nonce_url('tools.php?page=wp_racktest&amp;action=rack-test&amp;plugin=' . $plugin) . '" title="Rack Test Plugin">Rack Test</a>';
					if ( 'mustuse' == $context ) {
						$is_active = true;
					} elseif ( 'dropins' == $context ) {
						$dropins = _get_dropins();
						$plugin_name = $plugin_file;
						if ( $plugin_file != $plugin_data['Name'] )
							$plugin_name .= '<br/>' . $plugin_data['Name'];
						if ( true === ( $dropins[ $plugin_file ][1] ) ) { // Doesn't require a constant
							$is_active = true;
							$description = '<p><strong>' . $dropins[ $plugin_file ][0] . '</strong></p>';
						} elseif ( constant( $dropins[ $plugin_file ][1] ) ) { // Constant is true
							$is_active = true;
							$description = '<p><strong>' . $dropins[ $plugin_file ][0] . '</strong></p>';
						} else {
							$is_active = false;
							$description = '<p><strong>' . $dropins[ $plugin_file ][0] . ' <span class="attention">' . __('Inactive:') . '</span></strong> ' . sprintf( __( 'Requires <code>%s</code> in <code>wp-config.php</code>.' ), "define('" . $dropins[ $plugin_file ][1] . "', true);" ) . '</p>';
						}
						if ( $plugin_data['Description'] )
							$description .= '<p>' . $plugin_data['Description'] . '</p>';
					} else {
						$is_active_for_network = is_plugin_active_for_network($plugin_file);
						$is_active = $is_active_for_network || is_plugin_active( $plugin_file );
					} // end if $context

					$class = $is_active ? 'active' : 'inactive';
					if ( 'dropins' != $context ) {
						$description = '<p>' . $plugin_data['Description'] . '</p>';
						$plugin_name = $plugin_data['Name'];
					}
					echo "
				<tr class='$class'>
					<td class='plugin-title'><strong>$plugin_name</strong></td>
					<td class='desc'>$description</td>
				</tr>
				<tr class='$class second'>
					<td class='plugin-title'>";
					echo '<div class="row-actions-visible">';
					foreach ( $actions as $action => $link ) {
						$sep = end($actions) == $link ? '' : ' | ';
						echo "<span class='$action'>$link$sep</span>";
					}
					echo "</div></td>
					<td class='desc'>";
					$plugin_meta = array();
					if ( !empty($plugin_data['Version']) )
						$plugin_meta[] = sprintf(__('Version %s'), $plugin_data['Version']);
					if ( !empty($plugin_data['Author']) ) {
						$author = $plugin_data['Author'];
						if ( !empty($plugin_data['AuthorURI']) )
							$author = '<a href="' . $plugin_data['AuthorURI'] . '" title="' . __( 'Visit author homepage' ) . '">' . $plugin_data['Author'] . '</a>';
						$plugin_meta[] = sprintf( __('By %s'), $author );
					}
					if ( ! empty($plugin_data['PluginURI']) )
						$plugin_meta[] = '<a href="' . $plugin_data['PluginURI'] . '" title="' . __( 'Visit plugin site' ) . '">' . __('Visit plugin site') . '</a>';

					$plugin_meta = apply_filters('plugin_row_meta', $plugin_meta, $plugin_file, $plugin_data, $context);
					echo implode(' | ', $plugin_meta);
					echo "</td>
				</tr>\n";
				}
			?>
				</tbody>
			</table>
		<?php } ?>
			</div>
		<?php
	}		

	function wp_racktest_run($plugin) {
		// 
		$url = $this->pluginUrl . '/phprack.php';
		if( $curl = curl_init($url) ){
			// 
			curl_setopt($curl, CURLOPT_URL,$url);						
			curl_setopt($curl, CURLOPT_FAILONERROR, 1); 
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);// allow redirects  			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,1); // return into a variable  			
			curl_setopt($curl, CURLOPT_POST, 1); // set POST method  			
			$data = array ('plugin_dir' => WP_PLUGIN_DIR.'/'.$plugin.'/rack-tests');				
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			$out = curl_exec($curl);
			curl_close($curl);
			echo $out;
		}	
	}	
		
	}
	

	global $WPRackTest;
	$WPRackTest = new WPRackTest();	
	
}
