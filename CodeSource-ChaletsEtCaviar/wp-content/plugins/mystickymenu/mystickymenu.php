<?php     
	/*
	Plugin Name: myStickymenu 
	Plugin URI: http://wordpress.transformnews.com/plugins/mystickymenu-simple-sticky-fixed-on-top-menu-implementation-for-twentythirteen-menu-269
	Description: Simple sticky (fixed on top) menu implementation for navigation menu. After install go to Settings / myStickymenu and change Sticky Class to .your_navbar_class or #your_navbar_id.
	Version: 2.0.6
	Author: m.r.d.a
	Author URI: http://wordpress.transformnews.com/
	Text Domain: mystickymenu
	Domain Path: /languages
	License: GPLv2 or later
	*/

defined('ABSPATH') or die("Cannot access pages directly.");
define( 'MYSTICKY_VERSION', '2.0.6' );

class MyStickyMenuBackend
{

    private $options;
	
	

	public function __construct()
	{  
	 
		
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'mysticky_load_transl') );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'admin_init', array( $this, 'mysticky_default_options' ) );
		
		//add_action( 'admin_enqueue_scripts',  array( $this, 'mysticky_enqueue_color_picker' ) );
		//add_action( 'admin_head', array( $this, 'mysticky_admin_script' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mysticky_admin_script' ) );
		
		add_filter( 'plugin_action_links_mystickymenu/mystickymenu.php', array( $this, 'mystickymenu_settings_link' )  );
		
		
    }

	public function mystickymenu_settings_link($links){ 
		$settings_link = '<a href="options-general.php?page=my-stickymenu-settings">Settings</a>'; 
		array_unshift($links, $settings_link); 
	return $links; 
	}
	




    public function mysticky_admin_script($hook) {
		if ($hook != 'settings_page_my-stickymenu-settings') {
			return;
		}

		wp_register_script('mystickymenuAdminScript', plugins_url('/js/mystickymenu-admin.js', __FILE__), array( 'jquery' ), MYSTICKY_VERSION);
		wp_enqueue_script('mystickymenuAdminScript');

		wp_register_style('mystickymenuAdminStyle', plugins_url('/css/mystickymenu-admin.css', __FILE__) );
	    wp_enqueue_style('mystickymenuAdminStyle');	
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', plugins_url('js/iris-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );	
	}

/*	
	public function mysticky_enqueue_color_picker(  ) 
	{
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'my-script-handle', plugins_url('js/iris-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );		
		
	}	

*/

	
		
	public function mysticky_load_transl(){
		load_plugin_textdomain('mystickymenu', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
	}
	
	public function add_plugin_page(){
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin', 
			'myStickymenu', 
			'manage_options', 
			'my-stickymenu-settings', 
			array( $this, 'create_admin_page' )
		);
	}

	public function create_admin_page(){
		// Set class property
		$this->options = get_option( 'mysticky_option_name');
		?>
		<div id="mystickymenu" class="wrap">
			
			<h2><?php _e('myStickymenu', 'mystickymenu'); ?></h2>  
            <p><?php _e("Add sticky menu / header to any theme. <br />Simply change 'Sticky Class' to HTML element class desired to be sticky (div id can be used as well).", 'mystickymenu'); ?></p> 
            
            <div class="main-content"> 
                
            
            
            <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'mysticky-general';  ?> 
            
            <h2 class="nav-tab-wrapper">
                <a class="nav-tab btn-general <?php echo $active_tab == 'mysticky-general' ? 'nav-tab-active' : ''; ?>">General Settings</a>  
                <a class="nav-tab btn-style <?php echo $active_tab == 'mysticky-style' ? 'nav-tab-active' : ''; ?>">Style</a> 
                <a class="nav-tab btn-advanced <?php echo $active_tab == 'mysticky-advanced' ? 'nav-tab-active' : ''; ?>">Advanced</a> 
            </h2>
            
            
                   
            
            
            
          
            
            
			<form class="mysticky-hideform" method="post" action="options.php">
            
            
            
             <?php
			
			
			 //we check if the page is visited by click on the tabs or on the menu button.
                //then we get the active tab.
               
                if(isset($_GET["tab"]))
                {
					
					if($_GET["tab"] == "mysticky-general")
                    {
					echo '<div class="mysticky-general">';
					settings_fields( 'mysticky_option_group' );   
					do_settings_sections( 'my-stickymenu-settings' );
					echo '</div>';
					
					}
                    else if($_GET["tab"] == "mysticky-style")
                    {
					echo '<div class="mysticky-style">';	
					settings_fields( 'mysticky_option_group' );   
					do_settings_sections( 'my-stickymenu-settings' );
					echo '</div>';
                    }
					
					else if($_GET["tab"] == "mysticky-advanced")
                    {
					echo '<div class="mysticky-advanced">';		
					settings_fields( 'mysticky_option_group' );   
					do_settings_sections( 'my-stickymenu-settings' );
					echo '</div>';
                    } 
		
					
                }
				
				else {
						
					//	echo '<div class="mysticky-general">';
					settings_fields( 'mysticky_option_group' );   
					do_settings_sections( 'my-stickymenu-settings' );
				//	echo '</div>';
						
						
						}
			
				
				submit_button();
			?>
       
 
			</form>
  
			<form class="mysticky-hideformreset" method="post" action="">
				<input name="reset" class="button button-secondary confirm" type="submit" value="Reset to default settings" >
				<input type="hidden" name="action" value="reset" />
			</form>
            
            
			</div>
            
            
            <div class="main-sidebar">	
            
            <h3><?php _e('Plugin info','mystickymenu'); ?></h3>
            
			<div class="inner">
				<ul>
					<li><strong><?php _e('Author:','mystickymenu'); ?></strong> <a href="http://wordpress.transformnews.com" target="_blank">m.r.d.a</a></li>
					<li><strong><?php _e('Version:','mystickymenu'); ?></strong> <?php echo MYSTICKY_VERSION; ?></li>
					<li><strong><?php _e('Documentation:','mystickymenu'); ?></strong> <a href="http://wordpress.transformnews.com/plugins/mystickymenu-simple-sticky-fixed-on-top-menu-implementation-for-twentythirteen-menu-269" target="_blank">About Plugin</a> <a href="http://wordpress.transformnews.com/tutorials/mystickymenu-theme-support-682" target="_blank">Theme Support</a></li> 
					<li><strong><?php _e('Support Forum','mystickymenu'); ?></strong>: <a href="https://wordpress.org/support/plugin/mystickymenu" target="_blank">WordPress.org</a></li>
					<!--<li><strong><?php _e('Donate:','mystickymenu'); ?></strong> <a href="" target="_blank">Soon</a></li>-->
     
				</ul>
			</div>

			<p><a href="https://wordpress.org/support/plugin/mystickymenu/reviews/" target="_blank"><strong><?php _e('Add your own review','mystickymenu'); ?></strong></a></p>

			</div>
        </div>
		<?php
	}
	
	public function page_init()
	{   
		global $id, $title, $callback, $page;     
		register_setting(
			'mysticky_option_group',
			'mysticky_option_name',
			array( $this, 'sanitize' )
		);
		
		
		
		
		add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() );

		add_settings_section(
			'setting_section_id',
			__(" ", 'mystickymenu'),
			array( $this, 'print_section_info' ),
			'my-stickymenu-settings'
			
		);
		
		
		
		
		
		add_settings_field(
			'mysticky_class_selector',
			__("Sticky Class", 'mystickymenu'),
			array( $this, 'mysticky_class_selector_callback' ),
			'my-stickymenu-settings',
			'setting_section_id'
		);
		
		add_settings_field(
			'myfixed_fade', 
			__("Fade or slide effect", 'mystickymenu'),
			array( $this, 'myfixed_fade_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);	
		add_settings_field(
			'myfixed_zindex', 
			__("Sticky z-index", 'mystickymenu'),
			array( $this, 'myfixed_zindex_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'myfixed_bgcolor', 
			__("Sticky Background Color", 'mystickymenu'),
			array( $this, 'myfixed_bgcolor_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'myfixed_opacity', 
			__("Sticky Opacity", 'mystickymenu'),
			array( $this, 'myfixed_opacity_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'myfixed_transition_time', 
			__("Sticky Transition Time", 'mystickymenu'),
			array( $this, 'myfixed_transition_time_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'myfixed_disable_small_screen', 
			__("Disable at Small Screen Sizes", 'mystickymenu'),
			array( $this, 'myfixed_disable_small_screen_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'myfixed_disable_large_screen', 
			__("Disable at Large Screen Sizes", 'mystickymenu'),
			array( $this, 'myfixed_disable_large_screen_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_active_on_height', 
			__("Make visible on Scroll", 'mystickymenu'),
			array( $this, 'mysticky_active_on_height_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_active_on_height_home', 
			__("Make visible on Scroll at homepage", 'mystickymenu'),
			array( $this, 'mysticky_active_on_height_home_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		
		
		add_settings_field(
			'myfixed_disable_scroll_down', 
			__("Disable on scroll down", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);	
		
		add_settings_field(
			'myfixed_cssstyle', 
			__("CSS style", 'mystickymenu'),
			array( $this, 'myfixed_cssstyle_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'disable_css', 
			__("Disable CSS style", 'mystickymenu'),
			array( $this, 'disable_css_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		
		
		
		add_settings_field(
			'mysticky_disable_at_front_home', 
			__("Disable at", 'mystickymenu'),
			array( $this, 'mysticky_enable_callback' ), 
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_blog', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_page', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_tag', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_category', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_single', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_archive', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_enable_at_pages', 
			__(" ", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_enable_at_posts', 
			__(" ", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_search', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		add_settings_field(
			'mysticky_disable_at_404', 
			__("Disable at", 'mystickymenu'),
			'my-stickymenu-settings', 
			'setting_section_id'
		);
		
		
		
	}
/**
* Sanitize each setting field as needed
*
* @param array $input Contains all settings fields as array keys
*/
	public function sanitize( $input )
	{
		$new_input = array();
		if( isset( $input['mysticky_class_selector'] ) )
			$new_input['mysticky_class_selector'] = sanitize_text_field( $input['mysticky_class_selector'] );

		if( isset( $input['myfixed_zindex'] ) )
			$new_input['myfixed_zindex'] = absint( $input['myfixed_zindex'] );

		if( isset( $input['myfixed_bgcolor'] ) )
			$new_input['myfixed_bgcolor'] = sanitize_text_field( $input['myfixed_bgcolor'] );

		if( isset( $input['myfixed_opacity'] ) )
			$new_input['myfixed_opacity'] = absint( $input['myfixed_opacity'] );

		if( isset( $input['myfixed_transition_time'] ) )
			$new_input['myfixed_transition_time'] = sanitize_text_field( $input['myfixed_transition_time'] );

		if( isset( $input['myfixed_disable_small_screen'] ) )
			$new_input['myfixed_disable_small_screen'] = absint( $input['myfixed_disable_small_screen'] );
			
		if( isset( $input['myfixed_disable_large_screen'] ) )
			$new_input['myfixed_disable_large_screen'] = absint( $input['myfixed_disable_large_screen'] );

		if( isset( $input['mysticky_active_on_height'] ) )
			$new_input['mysticky_active_on_height'] = absint( $input['mysticky_active_on_height'] );

		if( isset( $input['mysticky_active_on_height_home'] ) )
			$new_input['mysticky_active_on_height_home'] = absint( $input['mysticky_active_on_height_home'] );

		if( isset( $input['myfixed_fade'] ) )
			$new_input['myfixed_fade'] = sanitize_text_field( $input['myfixed_fade'] ); 
		
		if( isset( $input['myfixed_disable_scroll_down'] ) )
			$new_input['myfixed_disable_scroll_down'] = sanitize_text_field( $input['myfixed_disable_scroll_down'] ); 
				
			
		if( isset( $input['myfixed_cssstyle'] ) )
			$new_input['myfixed_cssstyle'] = sanitize_text_field( $input['myfixed_cssstyle'] );
			
		if( isset( $input['disable_css'] ) )
			$new_input['disable_css'] = sanitize_text_field( $input['disable_css'] );	
			
			
			
		if( isset( $input['mysticky_disable_at_front_home'] ) )
			$new_input['mysticky_disable_at_front_home'] = sanitize_text_field( $input['mysticky_disable_at_front_home'] );
			
		if( isset( $input['mysticky_disable_at_blog'] ) )
			$new_input['mysticky_disable_at_blog'] = sanitize_text_field( $input['mysticky_disable_at_blog'] );
			
		if( isset( $input['mysticky_disable_at_page'] ) )
			$new_input['mysticky_disable_at_page'] = sanitize_text_field( $input['mysticky_disable_at_page'] );
		
		if( isset( $input['mysticky_disable_at_tag'] ) )
			$new_input['mysticky_disable_at_tag'] = sanitize_text_field( $input['mysticky_disable_at_tag'] );
			
		if( isset( $input['mysticky_disable_at_category'] ) )
			$new_input['mysticky_disable_at_category'] = sanitize_text_field( $input['mysticky_disable_at_category'] );
			
		if( isset( $input['mysticky_disable_at_single'] ) )
			$new_input['mysticky_disable_at_single'] = sanitize_text_field( $input['mysticky_disable_at_single'] );	
			
		if( isset( $input['mysticky_disable_at_archive'] ) )
			$new_input['mysticky_disable_at_archive'] = sanitize_text_field( $input['mysticky_disable_at_archive'] );
			
		if( isset( $input['mysticky_enable_at_pages'] ) )
			$new_input['mysticky_enable_at_pages'] = sanitize_text_field( $input['mysticky_enable_at_pages'] );	
				
		if( isset( $input['mysticky_enable_at_posts'] ) )
			$new_input['mysticky_enable_at_posts'] = sanitize_text_field( $input['mysticky_enable_at_posts'] );
				
		if( isset( $input['mysticky_disable_at_search'] ) )
			$new_input['mysticky_disable_at_search'] = sanitize_text_field( $input['mysticky_disable_at_search'] );
								
		if( isset( $input['mysticky_disable_at_404'] ) )
			$new_input['mysticky_disable_at_404'] = sanitize_text_field( $input['mysticky_disable_at_404'] );
				
			
			

		return $new_input;
	}

	public function mysticky_default_options() {

		global $options;
		$default = array(

				'mysticky_class_selector' => '.navbar',
				'myfixed_zindex' => '99990',
				'myfixed_bgcolor' => '#f7f5e7',
				'myfixed_opacity' => '90',
				'myfixed_transition_time' => '0.3',
				'myfixed_disable_small_screen' => '0',
				'myfixed_disable_large_screen' => '0',
				'mysticky_active_on_height' => '0',
				'mysticky_active_on_height_home' => '0',
				'myfixed_fade' => 'on',
				'myfixed_cssstyle' => '#mysticky-nav .myfixed { margin:0 auto; float:none; border:0px; background:none; max-width:100%; }'
			);

		if ( get_option('mysticky_option_name') == false ) {	
			update_option( 'mysticky_option_name', $default );		
		} 
		
		
		if(isset($_POST['reset'])) {
			update_option( 'mysticky_option_name', $default );
		}
	
		
	}
	
	
	
	


	public function print_section_info()
	{
		echo __(" ", 'mystickymenu');
    }

	public function mysticky_class_selector_callback()
	{
		printf(
			'<p class="description"><input type="text" size="18" id="mysticky_class_selector" class="mystickyinput" name="mysticky_option_name[mysticky_class_selector]" value="%s" /> ',  
			isset( $this->options['mysticky_class_selector'] ) ? esc_attr( $this->options['mysticky_class_selector']) : '' 
		);
		 echo __("menu or header element class or id.", 'mystickymenu');
		 echo '</p>';
	}

	public function myfixed_zindex_callback()
	{
		printf(
			'<p class="description"><input type="number" min="0" max="2147483647" step="1" id="myfixed_zindex" name="mysticky_option_name[myfixed_zindex]" value="%s" /></p>',
			isset( $this->options['myfixed_zindex'] ) ? esc_attr( $this->options['myfixed_zindex']) : ''
		);
	}

	public function myfixed_bgcolor_callback()
	{
		printf(
			'<p class="description"><input type="text" id="myfixed_bgcolor" name="mysticky_option_name[myfixed_bgcolor]" class="my-color-field" value="%s" /></p> ' ,
			isset( $this->options['myfixed_bgcolor'] ) ? esc_attr( $this->options['myfixed_bgcolor']) : ''
		);
	}

	public function myfixed_opacity_callback()
	{
		printf(
			'<p class="description"><input type="number" class="small-text" min="0" step="1" max="100" id="myfixed_opacity" name="mysticky_option_name[myfixed_opacity]"  value="%s" /> ',
			isset( $this->options['myfixed_opacity'] ) ? esc_attr( $this->options['myfixed_opacity']) : ''
		);
		echo __("numbers 1-100.", 'mystickymenu');
		echo '</p>';
	}

	public function myfixed_transition_time_callback()
	{
		printf(
			'<p class="description"><input type="number" class="small-text" min="0" step="0.1" id="myfixed_transition_time" name="mysticky_option_name[myfixed_transition_time]" value="%s" /> ',
			isset( $this->options['myfixed_transition_time'] ) ? esc_attr( $this->options['myfixed_transition_time']) : ''
		);
		echo __("in seconds.", 'mystickymenu');
		echo '</p>';
	}

	public function myfixed_disable_small_screen_callback()
	{
		printf(
		'<p class="description">'
		);
		echo __("less than", 'mystickymenu');
		printf(
		' <input type="number" class="small-text" min="0" step="1" id="myfixed_disable_small_screen" name="mysticky_option_name[myfixed_disable_small_screen]" value="%s" />',
			isset( $this->options['myfixed_disable_small_screen'] ) ? esc_attr( $this->options['myfixed_disable_small_screen']) : ''
		);
		echo __("px width, 0  to disable.", 'mystickymenu');
		echo '</p>';
	}
	
	
	
	public function myfixed_disable_large_screen_callback()
	{
		printf(
		'<p class="description">'
		);
		echo __("more than", 'mystickymenu');
		printf(
		' <input type="number" class="small-text" min="0" step="1" id="myfixed_disable_large_screen" name="mysticky_option_name[myfixed_disable_large_screen]" value="%s" />',
			isset( $this->options['myfixed_disable_large_screen'] ) ? esc_attr( $this->options['myfixed_disable_large_screen']) : ''
		);
		echo __("px width, 0  to disable.", 'mystickymenu');
		echo '</p>';
	}
	
	

	public function mysticky_active_on_height_callback()
	{
		printf(
		'<p class="description">'
		);
		echo __("after", 'mystickymenu');
		printf(
		' <input type="number" class="small-text" min="0" step="1" id="mysticky_active_on_height" name="mysticky_option_name[mysticky_active_on_height]" value="%s" />',
			isset( $this->options['mysticky_active_on_height'] ) ? esc_attr( $this->options['mysticky_active_on_height']) : ''
		);
		echo __("px. If set to 0 auto calculate will be used.", 'mystickymenu');
		echo '</p>';
	}

	public function mysticky_active_on_height_home_callback()
	{
		printf(
		'<p class="description">'
		);
		echo __("after", 'mystickymenu');
		printf(
		' <input type="number" class="small-text" min="0" step="1" id="mysticky_active_on_height_home" name="mysticky_option_name[mysticky_active_on_height_home]" value="%s" />',
			isset( $this->options['mysticky_active_on_height_home'] ) ? esc_attr( $this->options['mysticky_active_on_height_home']) : ''
		);
		echo __("px. If set to 0 it will use initial Make visible on Scroll value.", 'mystickymenu');
		echo '</p>';
	}

	public function myfixed_fade_callback()
	{
		printf(
			'<p class="description"><input id="%1$s" name="mysticky_option_name[myfixed_fade]" type="checkbox" %2$s /> ',
			'myfixed_fade',
			checked( isset( $this->options['myfixed_fade'] ), true, false ) 
		);
		echo __("Checked is slide, unchecked is fade.", 'mystickymenu');
		echo '</p>';	
		
		printf(
			'<p class="description"><input id="%1$s" name="mysticky_option_name[myfixed_disable_scroll_down]" type="checkbox" %2$s /> ',
			'myfixed_disable_scroll_down',
			checked( isset( $this->options['myfixed_disable_scroll_down'] ), true, false ) 
		);
		echo __("Disable sticky menu at scroll down", 'mystickymenu');
		echo '</p>';	
		
		
		
		
	} 

	public function myfixed_cssstyle_callback()
	{
		printf(
		'<p class="description">'
		);
		echo __("Add/edit CSS style. Leave it blank for default style.", 'mystickymenu');
		echo '</p>';
		printf(
			'<textarea type="text" rows="4" cols="60" id="myfixed_cssstyle" name="mysticky_option_name[myfixed_cssstyle]">%s</textarea> <br />',
			isset( $this->options['myfixed_cssstyle'] ) ? esc_attr( $this->options['myfixed_cssstyle']) : ''
		);
		
		echo '<p>';		
		echo __("CSS ID's and Classes to use:", 'mystickymenu'); 
		echo'</p>';
		
		echo '<pre>#mysticky-wrap { }<br>';
		echo '#mysticky-nav.wrapfixed { }<br>';
		echo '#mysticky-nav.wrapfixed.up { }<br>';
		echo '#mysticky-nav.wrapfixed.down { }<br>';
		echo '#mysticky-nav ';
		printf (
		isset( $this->options['mysticky_class_selector'] ) ? esc_attr( $this->options['mysticky_class_selector']) : '' 
		);
		echo ' { }<br>#mysticky-nav ';
		printf (
		isset( $this->options['mysticky_class_selector'] ) ? esc_attr( $this->options['mysticky_class_selector']) : '' 
		);
		echo '.myfixed { }</pre>';
		echo '<p class="description">';		
		echo __("Find examples <a href='http://wordpress.transformnews.com/tutorials/mystickymenu-extended-style-functionality-using-myfixed-sticky-class-403'>here</a>.", 'mystickymenu'); 
		echo'</p>';
	}
	
	public function disable_css_callback()
	{
		printf(
			'<p class="description"><input id="%1$s" name="mysticky_option_name[disable_css]" type="checkbox" %2$s /> ',
			'disable_css',
			checked( isset( $this->options['disable_css'] ), true, false ) 
		);
		echo __("Use this option if you plan to include CSS Style manually", 'mystickymenu');
		echo '</p>';	
	} 
	
	
	
	
	
	
	
	
	
	
	
	
	
		public function mysticky_enable_callback()
	{
		
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_front_home]" type="checkbox" %2$s />  ',
			'mysticky_disable_at_front_home',
			checked( isset( $this->options['mysticky_disable_at_front_home'] ), true, false ) 
		) ;
		_e('<span>front page </span>', 'mystickymenu');
		printf('</div>');
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_blog]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_blog',
			checked( isset( $this->options['mysticky_disable_at_blog'] ), true, false ) 
		);
		_e('<span>blog page </span>', 'mystickymenu');
		printf('</div>');
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_page]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_page',
			checked( isset( $this->options['mysticky_disable_at_page'] ), true, false ) 
		);
		_e('<span>pages </span>', 'mystickymenu');
		printf('</div>');
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_tag]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_tag',
			checked( isset( $this->options['mysticky_disable_at_tag'] ), true, false ) 
		);
		_e('<span>tags </span>', 'mystickymenu');
		printf('</div>');
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_category]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_category',
			checked( isset( $this->options['mysticky_disable_at_category'] ), true, false ) 
		);
		_e('<span>categories </span>', 'mystickymenu');
		printf('</div>');
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_single]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_single',
			checked( isset( $this->options['mysticky_disable_at_single'] ), true, false ) 
		);
		_e('<span>posts </span>', 'mystickymenu');
		printf('</div>');
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_archive]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_archive',
			checked( isset( $this->options['mysticky_disable_at_archive'] ), true, false ) 
		);
		_e('<span>archives </span>', 'mystickymenu');
		printf('</div>');
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_search]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_search',
			checked( isset( $this->options['mysticky_disable_at_search'] ), true, false ) 
		);
		_e('<span>search </span>', 'mystickymenu');
		printf('</div>');
		
		printf(
			'<div><input id="%1$s" name="mysticky_option_name[mysticky_disable_at_404]" type="checkbox" %2$s /> ',
			'mysticky_disable_at_404',
			checked( isset( $this->options['mysticky_disable_at_404'] ), true, false ) 
		);
		_e('<span>404 </span>', 'mystickymenu');
		printf('</div>');
	
		if  (isset ( $this->options['mysticky_disable_at_page'] ) == true )  {
			
			echo '<p> </p> <hr />';
			_e('<span class="">Except for this pages: </span>', 'mystickymenu');
	
			printf(
				'<input type="text" size="26" id="mysticky_enable_at_pages" name="mysticky_option_name[mysticky_enable_at_pages]" value="%s" /> ',  
				isset( $this->options['mysticky_enable_at_pages'] ) ? esc_attr( $this->options['mysticky_enable_at_pages']) : '' 
			); 
			
		 	_e('<span class="description">Comma separated list of pages to enable. It should be page name, id or slug. Example: about-us, 1134, Contact Us. Leave blank if you realy want to disable sticky menu for all pages.</span>', 'mystickymenu');
			
		}
	
		if  (isset ( $this->options['mysticky_disable_at_single'] ) == true )  {
			
			echo '<p> </p> <hr />';
			_e('<span class="">Except for this posts: </span>', 'mystickymenu');
	
			printf(
				'<input type="text" size="26" id="mysticky_enable_at_posts" name="mysticky_option_name[mysticky_enable_at_posts]" value="%s" /> ',  
				isset( $this->options['mysticky_enable_at_posts'] ) ? esc_attr( $this->options['mysticky_enable_at_posts']) : '' 
			); 
			
		 	_e('<span class="description">Comma separated list of posts to enable. It should be post name, id or slug. Example: about-us, 1134, Contact Us. Leave blank if you realy want to disable sticky menu for all posts.</span>', 'mystickymenu');
			
		}
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}



class MyStickyMenuFrontend
{
	
	public function __construct()
	{
		
	add_action( 'wp_head', array( $this, 'mysticky_build_stylesheet_content' ) );
	add_action( 'wp_enqueue_scripts', array( $this, 'mysticky_disable_at' ) );
	
	}
	
	public function mysticky_build_stylesheet_content() 
	
	{
		
	$mysticky_options = get_option( 'mysticky_option_name' );
	
	if (isset($mysticky_options['disable_css']))
	
	{
		
		//do nothing
		
	} else {
		
		$mysticky_options['disable_css'] = false;
		
	};
	
	if  ($mysticky_options ['disable_css'] == false )
	
	{
		
    echo '<style id="mystickymenu" type="text/css">';
	echo '#mysticky-nav { width:100%; position: static; }';
	echo '#mysticky-nav.wrapfixed { position:fixed; left: 0px; margin-top:0px;  z-index: '. $mysticky_options ['myfixed_zindex'] .'; -webkit-transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; -moz-transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; -o-transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; transition: ' . $mysticky_options ['myfixed_transition_time'] . 's; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=' . $mysticky_options ['myfixed_opacity'] . ')"; filter: alpha(opacity=' . $mysticky_options ['myfixed_opacity'] . '); opacity:' . $mysticky_options ['myfixed_opacity'] / 100 . '; background-color: ' . $mysticky_options ['myfixed_bgcolor'] . ';}';
			

	if  ($mysticky_options ['myfixed_disable_small_screen'] > 0 ){

	//echo '@media (max-width: '.$mysticky_options['myfixed_disable_small_screen'].'px) {#mysticky-nav.wrapfixed {position: static;} }';

	};
	
	if (  $mysticky_options['myfixed_cssstyle'] == "" )  {
		echo '#mysticky-nav .myfixed { margin:0 auto; float:none; border:0px; background:none; max-width:100%; }';
	}
	
	
	
	echo $mysticky_options ['myfixed_cssstyle']; 
	
	
	
	echo '</style>';
	}
}

	// add_action('wp_head', 'mysticky_build_stylesheet_content');

	public function mystickymenu_script() {
		
		$mysticky_options = get_option( 'mysticky_option_name' );
	
			if ( is_admin_bar_showing() ) {
				$top = "true";
				} else {
				$top = "false";
			}
			
		
		// needed for update 1.7 => 1.8 ... will be removed in the future ()
		if (isset($mysticky_options['mysticky_active_on_height_home'])){
				 //do nothing
			} else {
				$mysticky_options['mysticky_active_on_height_home'] = $mysticky_options['mysticky_active_on_height'];
		};
		
		
		if  ($mysticky_options['mysticky_active_on_height_home'] == 0 ){
			$mysticky_options['mysticky_active_on_height_home'] = $mysticky_options['mysticky_active_on_height'];
		};
		
		
		if ( is_front_page() && is_home() ) {
		
			$mysticky_options['mysticky_active_on_height'] = $mysticky_options['mysticky_active_on_height_home'];
	
		} elseif ( is_front_page()){
		
			$mysticky_options['mysticky_active_on_height'] = $mysticky_options['mysticky_active_on_height_home'];
		
		}
		
			wp_register_script('mystickymenu', plugins_url( 'js/mystickymenu.min.js', __FILE__ ), array('jquery'), MYSTICKY_VERSION, true);
			wp_enqueue_script( 'mystickymenu' );

		$myfixed_disable_scroll_down = isset($mysticky_options['myfixed_disable_scroll_down']) ? $mysticky_options['myfixed_disable_scroll_down'] : 'false';
		$mystickyTransition = isset($mysticky_options['myfixed_fade']) ? $mysticky_options['myfixed_fade'] : 'fade';
		$mystickyDisableLarge = isset($mysticky_options['myfixed_disable_large_screen']) ? $mysticky_options['myfixed_disable_large_screen'] : '0';
			
		$mysticky_translation_array = array( 
		    'mystickyClass' => $mysticky_options['mysticky_class_selector'] ,
			'activationHeight' => $mysticky_options['mysticky_active_on_height'],
			'disableWidth' => $mysticky_options['myfixed_disable_small_screen'],
			'disableLargeWidth' => $mystickyDisableLarge,
			'adminBar' => $top,
			'mystickyTransition' => $mystickyTransition,
			'mysticky_disable_down' => $myfixed_disable_scroll_down,
			
			
		);
		
			wp_localize_script( 'mystickymenu', 'option', $mysticky_translation_array );
	}

	//add_action( 'wp_enqueue_scripts', 'mystickymenu_script' );
	
	
	public function mysticky_disable_at() {
	
	
		$mysticky_options = get_option( 'mysticky_option_name' );	
		
		$mysticky_disable_at_front_home = isset($mysticky_options['mysticky_disable_at_front_home']);
		$mysticky_disable_at_blog = isset($mysticky_options['mysticky_disable_at_blog']);
		$mysticky_disable_at_page = isset($mysticky_options['mysticky_disable_at_page']);
		$mysticky_disable_at_tag = isset($mysticky_options['mysticky_disable_at_tag']);
		$mysticky_disable_at_category = isset($mysticky_options['mysticky_disable_at_category']);
		$mysticky_disable_at_single = isset($mysticky_options['mysticky_disable_at_single']);
		$mysticky_disable_at_archive = isset($mysticky_options['mysticky_disable_at_archive']);
		$mysticky_disable_at_search = isset($mysticky_options['mysticky_disable_at_search']);
		$mysticky_disable_at_404 = isset($mysticky_options['mysticky_disable_at_404']);
		$mysticky_enable_at_pages = isset($mysticky_options['mysticky_enable_at_pages']) ? $mysticky_options['mysticky_enable_at_pages'] : '';
		$mysticky_enable_at_posts = isset($mysticky_options['mysticky_enable_at_posts']) ? $mysticky_options['mysticky_enable_at_posts'] : '';
		//$mystickymenu_enable_at_pages_exp = explode( ',', $mystickymenu_enable_at_pages ); 
		// Trim input to ignore empty spaces
		$mysticky_enable_at_pages_exp = array_map('trim', explode(',', $mysticky_enable_at_pages));
		$mysticky_enable_at_posts_exp = array_map('trim', explode(',', $mysticky_enable_at_posts));
		
		
	
	
		if ( is_front_page() && is_home() ) {
		// Default homepage
			if ( $mysticky_disable_at_front_home == false ) { 
				$this->mystickymenu_script();
				
			};
	
	
		} elseif ( is_front_page()){
		//Static homepage
			if ( $mysticky_disable_at_front_home == false ) { 
				$this->mystickymenu_script();
			};
	

		} elseif ( is_home()){
		
		//Blog page
			if ( $mysticky_disable_at_blog == false ) { 
				$this->mystickymenu_script();
			};
	
	
		} elseif ( is_page() ){
		
		//Single page
			if ( $mysticky_disable_at_page == false ) { 
				$this->mystickymenu_script();
			};
		
			if ( is_page( $mysticky_enable_at_pages_exp  )  ){ 
			$this->mystickymenu_script();
			}
	
			
		} elseif ( is_tag()){
		
		//Tag page
			if ( $mysticky_disable_at_tag == false ) { 
				$this->mystickymenu_script();
			};
	
		} elseif ( is_category()){
		
		//Category page
			if ( $mysticky_disable_at_category == false ) { 
				$this->mystickymenu_script();
			};
	
	
		} elseif ( is_single()){
		
		//Single post
			if ( $mysticky_disable_at_single == false ) { 
				$this->mystickymenu_script();
			};
		
			if ( is_single( $mysticky_enable_at_posts_exp  )  ){ 
				$this->mystickymenu_script();
			}
	
		} elseif ( is_archive()){
		
		//Archive
			if ( $mysticky_disable_at_archive == false ) { 
				$this->mystickymenu_script();
			};

		} elseif ( is_search()){
		
		//Search
			if ( $mysticky_disable_at_search == false ) { 
				$this->mystickymenu_script();
			};

		} elseif ( is_404()){
		
		//404
			if ( $mysticky_disable_at_404 == false ) { 
				$this->mystickymenu_script();
			};

		}
		

	}




	
	
	
	
	
}

if( is_admin() ) {
	
	new MyStickyMenuBackend();

} else {
		
	new MyStickyMenuFrontend();

}
?>