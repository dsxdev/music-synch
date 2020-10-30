<?php
	/**
	 *
	 * @link              https://codeboxr.com
	 * @since             1.0.0
	 * @package           CustomTaxFilterinAdmin
	 *
	 * @wordpress-plugin
	 * Plugin Name:       CBX Custom Taxonomy Filter
	 * Plugin URI:        https://codeboxr.com/product/custom-taxonomy-filter-in-wp-admin-post-listing
	 * Description:       This plugin adds custom taxonomy filter in wordpress admin post listing panel.
	 * Version:           1.4.2
	 * Author:            codeboxr
	 * Author URI:        https://codeboxr.com
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       customtaxfilterinadmin
	 * Domain Path:       /languages
	 */

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	//plugin definition specific constants
	defined( 'CUSTOMTAXFILTERINADMIN_PLUGIN_NAME' ) or define( 'CUSTOMTAXFILTERINADMIN_PLUGIN_NAME', 'customtaxfilterinadmin' );
	defined( 'CUSTOMTAXFILTERINADMIN_PLUGIN_VERSION' ) or define( 'CUSTOMTAXFILTERINADMIN_PLUGIN_VERSION', '1.4.2' );
	defined( 'CUSTOMTAXFILTERINADMIN_PLUGIN_BASE_NAME' ) or define( 'CUSTOMTAXFILTERINADMIN_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
	defined( 'CUSTOMTAXFILTERINADMIN_PLUGIN_ROOT_PATH' ) or define( 'CUSTOMTAXFILTERINADMIN_PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ) );
	defined( 'CUSTOMTAXFILTERINADMIN_PLUGIN_ROOT_URL' ) or define( 'CUSTOMTAXFILTERINADMIN_PLUGIN_ROOT_URL', plugin_dir_url( __FILE__ ) );


	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-customtaxfilterinadmin-activator.php
	 */
	function activate_customtaxfilterinadmin() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-customtaxfilterinadmin-activator.php';
		CustomTaxFilterinAdmin_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-customtaxfilterinadmin-deactivator.php
	 */
	function deactivate_customtaxfilterinadmin() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-customtaxfilterinadmin-deactivator.php';
		CustomTaxFilterinAdmin_Deactivator::deactivate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-customtaxfilterinadmin-uninstall.php
	 */
	function uninstall_customtaxfilterinadmin(){
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-customtaxfilterinadmin-uninstall.php';
		CustomTaxFilterinAdmin_Uninstall::uninstall();
	}

	//hooks for activate, deactivate and uninstall
	register_activation_hook( __FILE__, 'activate_customtaxfilterinadmin' );
	register_deactivation_hook( __FILE__, 'deactivate_customtaxfilterinadmin' );
	register_uninstall_hook(__FILE__, 'uninstall_customtaxfilterinadmin' );


	if(!class_exists('CustomTaxFilterinAdmin') && defined('CUSTOMTAXFILTERINADMIN_PLUGIN_NAME')){
		class CustomTaxFilterinAdmin {
			function __construct() {
				//add translation
				load_plugin_textdomain( 'customtaxfilterinadmin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );



				require_once CUSTOMTAXFILTERINADMIN_PLUGIN_ROOT_PATH .'includes/class-customtaxfilterinadmin-setting.php';

				$this->settings_api = new CustomTaxFilterinAdminSettings();

				//add setting
				add_action('admin_init', array($this, 'admin_init'));

				//adding menu in admin menu settings
				add_action('admin_menu', array($this, 'admin_pages'));

				//add css and js in admin setting page
				add_action('admin_enqueue_scripts', array($this, 'add_css_js'));

				//add setting link in plugin listing page
				add_filter( 'plugin_action_links_'.CUSTOMTAXFILTERINADMIN_PLUGIN_BASE_NAME, array($this, 'add_settings_link') );

				add_action( 'restrict_manage_posts', array($this, 'display_taxdropdown') );

				add_filter( 'plugin_row_meta', array($this, 'plugin_row_meta'), 10, 4 );
			}//end method __construct

			/**
			 * Init the setting api
			 */
			public function admin_init(){
				//init setting api
				$this->settings_api->set_sections($this->get_setting_sections());
				$this->settings_api->set_fields($this->get_setting_fields());

				//initialize them
				$this->settings_api->admin_init();
			}//end method admin_init

			/**
			 * Initialize the setting
			 * 
			 * @return mixed|void
			 */
			public function get_setting_sections()
			{
				$sections = array(
					array(
						'id'    => 'cbxcustomtaxfilterinadmin_tax_settings',
						'title' => esc_html__('Taxonomy Settings', 'customtaxfilterinadmin')
					),					
					array(
						'id'    => 'cbxcustomtaxfilterinadmin_tools',
						'title' => esc_html__('Tools', 'customtaxfilterinadmin')
					)
				);

				return apply_filters('cbxcustomtaxfilterinadmin_setting_sections', $sections);
			}//end method get_setting_sections

			/**
			 * Setting fields
			 * 
			 * @return mixed|void
			 */
			public function get_setting_fields(){
				$fields = array(
					'cbxcustomtaxfilterinadmin_tax_settings' => apply_filters('cbxcustomtaxfilterinadmin_tax_fields', array(
						'taxsetting' =>  array(
							'name'     => 'taxsetting',
							'label'    => esc_html__('Post and Taxonomy Setting', 'customtaxfilterinadmin'),
							'type'     => 'taxsetting',
							'default'  => array()
						)
					)),					
					'cbxcustomtaxfilterinadmin_tools'    => apply_filters('cbxcustomtaxfilterinadmin_tools_fields', array(
							'delete_global_config' =>  array(
								'name'     => 'delete_global_config',
								'label'    => esc_html__('On Uninstall delete plugin data', 'customtaxfilterinadmin'),
								'desc'     => esc_html__('If set yes, then on plugin delete/uninstall all information saved by this plugin will be deleted', 'customtaxfilterinadmin'),
								'type'     => 'radio',
								'options'  => array(
									'yes' => esc_html__('Yes', 'customtaxfilterinadmin'),
									'no'  => esc_html__('No', 'customtaxfilterinadmin'),
								),
								'default'  => 'no'
							)							
						)
					)
				);

				return apply_filters('cbxcustomtaxfilterinadmin_setting_fields', $fields);
			}//end method get_setting_fields

			/**
			 * Adds plugin setting page menu
			 */
			public function admin_pages(){
				$page_hook = add_options_page(esc_html__('Custom Taxonomy filter in Wordpress Admin Post Listing', 'customtaxfilterinadmin'), esc_html__('CBX Custom Tax Filter', 'customtaxfilterinadmin'), 'manage_options', 'customtaxfilterinadmin', array($this, 'admin_page'));
			}//end method admin_pages

			/**
			 * Show setting page
			 */
			public function admin_page(){
				$plugin_data = get_plugin_data(plugin_dir_path(__DIR__) . '/' . CUSTOMTAXFILTERINADMIN_PLUGIN_BASE_NAME);
				include('partials/admin-settings-display.php');
			}//end method admin_page

			/**
			 * Add css and js in admin setting
			 *
			 * @param $hook
			 */
			public function add_css_js($hook){
				$current_page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';
				$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

				if($current_page == 'customtaxfilterinadmin'){

					wp_register_style( 'chosen-jquery', plugin_dir_url( __FILE__ ) . '/assets/css/chosen.min.css', array(), CUSTOMTAXFILTERINADMIN_PLUGIN_VERSION, 'all' );
					wp_register_style( 'customtaxfilterinadmin-admin-setting', plugin_dir_url( __FILE__ ) . '/assets/css/customtaxfilterinadmin-admin-setting.css', array('chosen-jquery'), CUSTOMTAXFILTERINADMIN_PLUGIN_VERSION, 'all' );

					wp_register_script( 'chosen-jquery', plugin_dir_url( __FILE__ ) . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), CUSTOMTAXFILTERINADMIN_PLUGIN_VERSION, true );
					wp_register_script( 'customtaxfilterinadmin-admin-setting', plugin_dir_url( __FILE__ ) . '/assets/js/customtaxfilterinadmin-admin-setting.js', array( 'jquery', 'chosen-jquery', 'wp-color-picker' ), CUSTOMTAXFILTERINADMIN_PLUGIN_VERSION, true );

					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_style( 'chosen');
					wp_enqueue_style( 'customtaxfilterinadmin-admin-setting');

					wp_enqueue_media();
					wp_enqueue_script('jquery');
					wp_enqueue_script( 'wp-color-picker' );
					wp_enqueue_script('chosen-jquery');
					wp_enqueue_script('customtaxfilterinadmin-admin-setting');
				}


			}//end method add_css_js
			
			/**
			 * Add setting link in plugin listing page
			 *
			 * @param $links
			 *
			 * @return mixed
			 */
			public function add_settings_link( $links ) {
				$settings_link = '<a style="color:#9c27b0 !important; font-weight: bold;" href="options-general.php?page=customtaxfilterinadmin">'.esc_html__('Settings', 'customtaxfilterinadmin').'</a>';
				array_unshift( $links, $settings_link );

				return $links;
			}//end method add_settings_link

			
			/**
			 * List all global option name with prefix cbxcustomtaxfilterinadmin_
			 */
			public static function getAllOptionNames() {
				global $wpdb;

				$prefix       = 'cbxcustomtaxfilterinadmin_';
				$option_names = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'", ARRAY_A );

				return apply_filters( 'cbxcustomtaxfilterinadmin_option_names', $option_names );
			}//end method getAllOptionNames

			/**
			 * Is builtin taxonomy
			 *
			 * @param string $flag
			 * @return string
			 */
			public static function isBuiltinTax($flag = ''){
				if($flag == '1'){ return esc_html__('Yes', 'customtaxfilterinadmin');}
				else  return esc_html__('No', 'customtaxfilterinadmin');
			}

			/**
			 * Display the taxonomy
			 */
			public function display_taxdropdown(){
				$settings = new CustomTaxFilterinAdminSettings();

				$taxsetting = $settings->get_option( 'taxsetting', 'cbxcustomtaxfilterinadmin_tax_settings', array());

				global $typenow;
				global $wpcustomtaxfilterinadmin;

				$argsb   =   array( 'public' => true, '_builtin' => false );
				$argsc  =   array( 'public' => true, '_builtin' => true );
				$post_typesb        = get_post_types($argsb);
				$post_typesc        = get_post_types($argsc);
				$post_types         = array_merge($post_typesb, $post_typesc);

				if ( in_array($typenow, $post_types) && isset($taxsetting[$typenow]) &&  intval($taxsetting[$typenow]) == 1) {
					$filter = get_object_taxonomies($typenow);

					foreach ($filter as $tax_slug) {

						if(isset($taxsetting[$typenow.'_tax'][$tax_slug])  && intval($taxsetting[$typenow.'_tax'][$tax_slug]) == 1){
							$tax_obj = get_taxonomy($tax_slug);

							wp_dropdown_categories(array(
								'show_option_all'   => $tax_obj->labels->all_items,
								'taxonomy'          => $tax_slug,
								//'name'              => $tax_obj->name,
								'name'              => $tax_obj->query_var,
								'orderby'           => 'name',
								'selected'          => isset($_GET[$tax_obj->query_var]) ? $_GET[$tax_obj->query_var]: '',
								'hierarchical'      => $tax_obj->hierarchical,
								'show_count'        => true,
								'hide_empty'        => false,
								'value_field'       => 'slug',
							));
						}//end if
					}//end foreach
				}
			}//end method display_taxdropdown

			/**
			 * Filters the array of row meta for each/specific plugin in the Plugins list table.
			 * Appends additional links below each/specific plugin on the plugins page.
			 *
			 * @access  public
			 *
			 * @param array  $links_array      An array of the plugin's metadata
			 * @param string $plugin_file_name Path to the plugin file
			 * @param array  $plugin_data      An array of plugin data
			 * @param string $status           Status of the plugin
			 *
			 * @return  array       $links_array
			 */
			public function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
				if ( strpos( $plugin_file_name, CUSTOMTAXFILTERINADMIN_PLUGIN_BASE_NAME ) !== false ) {


					$links_array[] = '<a target="_blank" style="color:#9c27b0 !important; font-weight: bold;" href="https://wordpress.org/support/plugin/custom-taxonomy-filter/" aria-label="' . esc_attr__( 'Free Support', 'customtaxfilterinadmin' ) . '">' . esc_html__( 'Free Support', 'customtaxfilterinadmin' ) . '</a>';

					$links_array[] = '<a target="_blank" style="color:#9c27b0 !important; font-weight: bold;" href="https://wordpress.org/plugins/custom-taxonomy-filter/#reviews" aria-label="' . esc_attr__( 'Reviews', 'customtaxfilterinadmin' ) . '">' . esc_html__( 'Reviews', 'customtaxfilterinadmin' ) . '</a>';

					$links_array[] = '<a target="_blank" style="color:#9c27b0 !important; font-weight: bold;" href="https://codeboxr.com/product/custom-taxonomy-filter-in-wordpress-admin-post-listing/" aria-label="' . esc_attr__( 'Documentation', 'customtaxfilterinadmin' ) . '">' . esc_html__( 'Documentation', 'customtaxfilterinadmin' ) . '</a>';

					$links_array[] = '<a target="_blank" style="color:#9c27b0 !important; font-weight: bold;" href="https://codeboxr.com/product/customization-support/" aria-label="' . esc_attr__( 'Pro Support', 'customtaxfilterinadmin' ) . '">' . esc_html__( 'Pro Support', 'customtaxfilterinadmin' ) . '</a>';


				}

				return $links_array;
			}//end plugin_row_meta

		}//end class CustomTaxFilterinAdmin
	}//end if class exists checking


	//initialize the class
	new CustomTaxFilterinAdmin();