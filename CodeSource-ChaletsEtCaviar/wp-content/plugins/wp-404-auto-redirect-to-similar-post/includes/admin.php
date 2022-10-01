<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('WP_404_Auto_Redirect'))
    return;

trait WP_404_Auto_Redirect_Admin {
    
    function admin_menu(){
        add_submenu_page('options-general.php', 'WP 404 Auto Redirect', 'WP 404 Auto Redirect', 'manage_options', 'wp-404-auto-redirect', array($this, 'admin_page'));
    }
    
    function admin_link($links, $plugin_file){
        $plugin = plugin_basename(WP404ARSP_FILE);
        if($plugin != $plugin_file)
            return $links;
        
        return array_merge(
            $links, 
            array('<a href="' . admin_url('options-general.php?page=wp-404-auto-redirect') . '">' . __('Settings', 'wp404-auto-redirect') . '</a>')
        );
    }
    
    function admin_settings(){
        register_setting('wp404arsp_settings', 'wp404arsp_settings');
    }
    
    function admin_scripts($page){
        if($page != 'settings_page_wp-404-auto-redirect')
            return;
        
        wp_enqueue_script('wp404arsp_admin_js', plugins_url('assets/admin.js', WP404ARSP_FILE), array('jquery'));
        wp_enqueue_style('wp404arsp_admin_css', plugins_url('assets/admin.css', WP404ARSP_FILE));
    }

    function admin_page(){
    ?>
    <div class="wrap" id="wp404arsp_settings">
        <h1 class="wp-heading-inline">WP 404 Auto Redirect to Similar Post</h1>
        <hr class="wp-header-end" />
        
        <h2 class="nav-tab-wrapper">
            <a href="#settings" class="nav-tab nav-tab-active"><?php _e('Settings', 'wp404-auto-redirect'); ?></a>
            <a href="#post-types" class="nav-tab"><?php _e('Post Types', 'wp404-auto-redirect'); ?></a>
            <a href="#taxonomies" class="nav-tab"><?php _e('Taxonomies', 'wp404-auto-redirect'); ?></a>
            <a href="#hooks" class="nav-tab"><?php _e('Engines', 'wp404-auto-redirect'); ?></a>
        </h2>
        
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                
                    <form method="post" action="options.php">
                    <?php 
                    settings_fields('wp404arsp_settings');
                    do_settings_sections('wp404arsp_settings');
                    $settings = wp404arsp_settings_get();
                    ?>

                        <div class="meta-box-sortables ui-sortable">
                        
                            <!-- Tab: Settings -->
                            <div class="nav-tab-panel" id="settings">
                            
                                <div class="postbox">
                                    <div class="inside">
                                        <table class="form-table">
                                            <tbody>
                                            
                                                <tr>
                                                    <th scope="row"><?php _e('Debug Mode', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Debug Mode', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_debug">
                                                                <input 
                                                                    name="wp404arsp_settings[debug]" 
                                                                    id="wp404arsp_settings_debug" 
                                                                    value="1" 
                                                                    type="checkbox" 
                                                                    <?php checked(1, $settings['debug'], true); ?> 
                                                                    />
                                                                <?php _e('Enable', 'wp404-auto-redirect'); ?>
                                                            </label>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Display the Debug Console instead of being redirected. <code>Administrators</code> only.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row"><?php _e('Expose Headers', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Expose Headers', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_headers">
                                                                <input 
                                                                    name="wp404arsp_settings[headers]" 
                                                                    id="wp404arsp_settings_headers" 
                                                                    value="1" 
                                                                    type="checkbox" 
                                                                    <?php checked(1, $settings['headers'], true); ?> 
                                                                    />
                                                                <?php _e('Enable', 'wp404-auto-redirect'); ?>
                                                            </label>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Expose \'WP-404-Auto-Redirect\' headers on 404 pages. <code>Administrators</code> only.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row"><?php _e('Log Redirections', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        
                                                        <?php if(!WP_DEBUG || !WP_DEBUG_LOG){ ?>
                                                            <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Log Redirections', 'wp404-auto-redirect'); ?></span></legend>
                                                                <p class="description"><?php _e('To enable this feature, please set <code>WP_DEBUG</code> and <code>WP_DEBUG_LOG</code> to <code>true</code>. Read the <a href="https://codex.wordpress.org/Editing_wp-config.php#Debug" target="_blank">WP Config documentation</a>.', 'wp404-auto-redirect'); ?></p>
                                                            </fieldset>
                                                        <?php }else{ ?>
                                                            <fieldset>
                                                                <legend class="screen-reader-text"><span><?php _e('Log Redirections', 'wp404-auto-redirect'); ?></span></legend>
                                                                <label for="wp404arsp_settings_log">
                                                                    <input 
                                                                        name="wp404arsp_settings[log]" 
                                                                        id="wp404arsp_settings_log" 
                                                                        value="1" 
                                                                        type="checkbox" 
                                                                        <?php checked(1, $settings['log'], true); ?> 
                                                                        />
                                                                    <?php _e('Enable', 'wp404-auto-redirect'); ?>
                                                                </label>
                                                            </fieldset>
                                                            <p class="description"><?php _e('Log redirections in the <code>/wp-content/debug.log</code> file.', 'wp404-auto-redirect'); ?></p>
                                                        <?php } ?>
                                                        
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row"><?php _e('Fallback Behavior', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Fallback Behavior', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_fallback_type">
                                                                <select name="wp404arsp_settings[fallback][type]" id="wp404arsp_settings_fallback_type">
                                                                    <option value="home" <?php if($settings['fallback']['type'] == 'home') echo "selected"; ?>><?php _e('Redirect to Homepage', 'wp404-auto-redirect'); ?></option>
                                                                    <option value="custom" <?php if($settings['fallback']['type'] == 'custom') echo "selected"; ?>><?php _e('Custom Redirection', 'wp404-auto-redirect'); ?></option>
                                                                    <option value="disabled" <?php if($settings['fallback']['type'] == 'disabled') echo "selected"; ?>><?php _e('Default 404', 'wp404-auto-redirect'); ?></option>
                                                                </select>
                                                            </label>
                                                            
                                                            <?php 
                                                            $fallback = array(
                                                                'value' => home_url(),
                                                                'class' => 'disabled',
                                                                'attr'  => 'readonly="readonly"',
                                                            );
                                                            
                                                            if($settings['fallback']['type'] == 'custom'){
                                                                $fallback['value']  = $settings['fallback']['url'];
                                                                $fallback['attr']   = '';
                                                                $fallback['class']  = '';
                                                            }
                                                            
                                                            if($settings['fallback']['type'] == 'disabled'){
                                                                $fallback['value']  = '';
                                                                $fallback['attr']   = '';
                                                                $fallback['class']  = 'hidden';
                                                            }
                                                            ?>
                                                            
                                                            <input name="wp404arsp_settings[fallback][home_url]" id="wp404arsp_settings_fallback_home_url" type="hidden" value="<?php echo home_url(); ?>" />
                                                            <input name="wp404arsp_settings[fallback][url]" id="wp404arsp_settings_fallback_url" type="text" value="<?php echo $fallback['value']; ?>" class="<?php echo $fallback['class']; ?>" <?php echo $fallback['attr']; ?> />
                                                            
                                                        </fieldset>
                                                        <p class="description"><?php _e('If nothing similar is found, this behavior will be applied.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row"><?php _e('Redirections Headers', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Redirections Headers', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_method">
                                                                <select name="wp404arsp_settings[method]" id="wp404arsp_settings_method">
                                                                    <option value="301" <?php if($settings['method'] == 301) echo "selected"; ?>>301 Status</option>
                                                                    <option value="302" <?php if($settings['method'] == 302) echo "selected"; ?>>302 Status</option>
                                                                </select>
                                                            </label>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Learn more about <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes" target="_blank">HTTP headers & redirections</a>.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row"><?php _e('Plugin Priority', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Plugin Priority', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_priority">
                                                                <input 
                                                                    type="number" 
                                                                    name="wp404arsp_settings[priority]" 
                                                                    id="wp404arsp_settings_priority" 
                                                                    value="<?php echo isset($settings['priority']) ? $settings['priority'] : '999'; ?>" 
                                                                    required 
                                                                    />
                                                            </label>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Advanced users only. Default: <code>999</code>', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                
                                <div id="wp404arsp_settings_redirection_preview">
                                    <div class="postbox">
                                        <div class="inside">
                                            <table class="form-table">
                                                <tbody>
                                                
                                                    <tr>
                                                        <th scope="row"><?php echo home_url(); ?></th>
                                                        <td>
                                                            <input class="request" type="text" value="/example-url" />
                                                            <p class="description"><?php _e('Enter the URL you would like to test, starting with <code>/</code>.', 'wp404-auto-redirect'); ?></p>
                                                            
                                                            <p class="submit">
                                                                <?php submit_button(__('Preview', 'wp404-auto-redirect'), 'secondary', '', false); ?>
                                                                <span class="loading spinner"></span>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="results"></div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- Tab: Post Types -->
                            <div class="nav-tab-panel" id="post-types">
                            
                                <div class="postbox">
                                    <div class="inside">
                                        <table class="form-table">
                                            <tbody>
                                            
                                                <tr>
                                                    <th scope="row"><?php _e('Exclude Post Meta', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Exclude Post Meta', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_rules_redirection_exclude_post_meta">
                                                                <input 
                                                                    name="wp404arsp_settings[rules][exclude][post_meta]" 
                                                                    id="wp404arsp_settings_rules_redirection_exclude_post_meta" 
                                                                    type="checkbox" 
                                                                    value="1" 
                                                                    <?php checked(1, $settings['rules']['exclude']['post_meta'], true); ?>
                                                                    />
                                                                <?php _e('Enable', 'wp404-auto-redirect'); ?>
                                                            </label>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Exclude posts with the post meta: <code>wp404arsp_no_redirect = 1</code> from possible redirections.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th scope="row"><?php _e('Exclude Post Type(s)', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Exclude Post Type(s)', 'wp404-auto-redirect'); ?></span></legend>
                                                            <div id="wp404arsp_settings_rules_redirection_exclude_post_types">
                                                                <?php foreach(get_post_types(array('public' => true), 'objects') as $post_type) { ?>
                                                                    <?php 
                                                                    $checked = '';
                                                                    if( 
                                                                        isset($settings['rules']['exclude']['post_types']) && 
                                                                        is_array($settings['rules']['exclude']['post_types']) && 
                                                                        in_array($post_type->name, $settings['rules']['exclude']['post_types'])
                                                                    )
                                                                        $checked = 'checked="checked"'; ?>
                                                                    <div><input type="checkbox" name="wp404arsp_settings[rules][exclude][post_types][]" id="wp404arsp_settings_rules_redirection_exclude_post_types_<?php echo $post_type->name; ?>" value="<?php echo $post_type->name; ?>" <?php echo $checked; ?> />
                                                                    <label for="wp404arsp_settings_rules_redirection_exclude_post_types_<?php echo $post_type->name; ?>"><?php echo $post_type->label; ?></label></div>
                                                                <?php } ?>
                                                            </div>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Exclude one or multiple post types from possible redirections.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- Tab: Taxonomies -->
                            <div class="nav-tab-panel" id="taxonomies">
                            
                                <div class="postbox">
                                    <div class="inside">
                                        <table class="form-table">
                                            <tbody>
                                            
                                                <tr>
                                                    <th scope="row"><?php _e('Disable Taxonomy Redirection', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Disable Taxonomy Redirection', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_rules_redirection_disable_taxonomies">
                                                                <input 
                                                                    name="wp404arsp_settings[rules][disable][taxonomies]" 
                                                                    id="wp404arsp_settings_rules_redirection_disable_taxonomies" 
                                                                    type="checkbox" 
                                                                    value="1" 
                                                                    <?php checked(1, $settings['rules']['disable']['taxonomies'], true); ?>
                                                                    />
                                                                <?php _e('Disable', 'wp404-auto-redirect'); ?>
                                                            </label>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Never redirect to terms archives.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                            
                                                <tr class="wp404arsp_settings_taxonomies">
                                                    <th scope="row"><?php _e('Exclude Term Meta', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Exclude Term Meta', 'wp404-auto-redirect'); ?></span></legend>
                                                            <label for="wp404arsp_settings_rules_redirection_exclude_term_meta">
                                                                <input 
                                                                    name="wp404arsp_settings[rules][exclude][term_meta]" 
                                                                    id="wp404arsp_settings_rules_redirection_exclude_term_meta" 
                                                                    type="checkbox" 
                                                                    value="1" 
                                                                    <?php checked(1, $settings['rules']['exclude']['term_meta'], true); ?>
                                                                    />
                                                                <?php _e('Enable', 'wp404-auto-redirect'); ?>
                                                            </label>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Exclude terms with the term meta: <code>wp404arsp_no_redirect = 1</code> from possible redirections.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                                <tr class="wp404arsp_settings_taxonomies">
                                                    <th scope="row"><?php _e('Exclude Taxonomie(s)', 'wp404-auto-redirect'); ?></th>
                                                    <td>
                                                        <fieldset>
                                                            <legend class="screen-reader-text"><span><?php _e('Exclude Taxonomie(s)', 'wp404-auto-redirect'); ?></span></legend>
                                                            <div id="wp404arsp_settings_rules_redirection_exclude_taxonomies">
                                                                <?php foreach(get_taxonomies(array('public' => true), 'objects') as $taxonomy) { ?>
                                                                    <?php 
                                                                    $checked = '';
                                                                    if( 
                                                                        isset($settings['rules']['exclude']['taxonomies']) && 
                                                                        is_array($settings['rules']['exclude']['taxonomies']) && 
                                                                        in_array($taxonomy->name, $settings['rules']['exclude']['taxonomies'])
                                                                    )
                                                                        $checked = 'checked="checked"'; ?>
                                                                    <div><input type="checkbox" name="wp404arsp_settings[rules][exclude][taxonomies][]" id="wp404arsp_settings_rules_redirection_exclude_taxonomies_<?php echo $taxonomy->name; ?>" value="<?php echo $taxonomy->name; ?>" <?php echo $checked; ?> />
                                                                    <label for="wp404arsp_settings_rules_redirection_exclude_taxonomies_<?php echo $taxonomy->name; ?>"><?php echo $taxonomy->label; ?></label></div>
                                                                <?php } ?>
                                                            </div>
                                                        </fieldset>
                                                        <p class="description"><?php _e('Exclude one or multiple taxonomies from possible redirections.', 'wp404-auto-redirect'); ?></p>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- Tab: Hooks -->
                            <div class="nav-tab-panel" id="hooks">
                            
                                <?php 
                                do_action('wp404arsp/search/init', false);
                                
                                // init Groups
                                $groups = array();
                                $groups = wp404arsp()->groups->get_groups;
                                $groups_count = count($groups);
                                
                                // init Engines
                                $engines = array();
                                $engines = wp404arsp()->engines->get_engines;
                                $engines_count = count($engines);
                                ?>
                                
                                <div style="float:left; width:49%; margin-right:2%;">
                                    <table class="widefat" style="margin-bottom:20px;">
                                    
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="row-title"><h3 style="margin:7px 0;">Groups <span style="color:#555d66;">(<?php echo $groups_count; ?>)</span></h3></th>
                                            </tr>
                                        </thead>
                                    
                                        <thead>
                                            <tr>
                                                <th class="row-title">Name</th>
                                                <th>Engines</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php if(!empty($groups)){ ?>
                                            
                                                <?php $i=0; foreach($groups as $group){ $i++; ?>
                                                    <tr <?php echo !($i % 2) ? 'class="alternate"': ''; ?>>
                                                        <td class="row-title">
                                                            <span style="color: #0073aa;"><?php echo $group['name']; ?></span><br />
                                                            <small style="font-weight:normal;"><?php echo $group['slug']; ?></small>
                                                        </td>
                                                        <td>
                                                            <?php foreach($group['engines'] as $engine){ ?>
                                                                
                                                                <?php if($engine = wp404arsp_get_engine_by_slug($engine)){ ?>
                                                                    <div style="margin-bottom:5px;">
                                                                        <?php echo $engine['name']; ?>
                                                                    </div>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            
                                                        </td>
                                                    </tr>
                                                    
                                                <?php } ?>
                                            
                                            <?php }else{ ?>
                                            
                                                <tr>
                                                    <td class="row-title" colspan="2" style="text-align:center;">
                                                        <em>No Groups found.</em>
                                                    </td>
                                                </tr>
                                                
                                            <?php } ?>
                                        </tbody>
                                        
                                    </table>
                                </div>
                                
                                <div style="float:left; width:49%;">
                                    <table class="widefat" style="margin-bottom:20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="row-title"><h3 style="margin:7px 0;">Engines <span style="color:#555d66;">(<?php echo $engines_count; ?>)</span></h3></th>
                                            </tr>
                                        </thead>
                                    
                                        <thead>
                                            <tr>
                                                <th class="row-title">Name</th>
                                                <th>Weight</th>
                                                <th>Primary</th>
                                                <th>Defined</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php if(!empty($engines)){ ?>
                                            
                                                <?php $i=0; foreach($engines as $engine){ $i++; ?>
                                                    
                                                    <?php $has_filter = has_filter('wp404arsp/search/engine/' . $engine['slug']); ?>
                                                    
                                                    <tr <?php echo !($i % 2) ? 'class="alternate"': ''; ?> <?php if(!$has_filter) echo 'style="background:#f7e5e5;"'; ?>>
                                                        <td class="row-title">
                                                            <span style="color: #0073aa;"><?php echo $engine['name']; ?></span><br />
                                                            <small style="font-weight:normal;"><?php echo $engine['slug']; ?></small>
                                                        </td>
                                                        <td><?php echo $engine['weight']; ?></td>
                                                        <td>
                                                            <?php if($engine['primary']){ ?>
                                                                <span class="dashicons dashicons-yes"></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php if($has_filter){ ?>
                                                            
                                                                <span class="dashicons dashicons-yes"></span>
                                                                
                                                            <?php }else{ ?>
                                                                
                                                                <span class="dashicons dashicons-no" style="color:#cc0000;"></span>
                                                                
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                
                                            <?php }else{ ?>
                                                
                                                <tr>
                                                    <td class="row-title" colspan="3" style="text-align:center;">
                                                        <em>No Engines found.</em>
                                                    </td>
                                                </tr>
                                                
                                            <?php } ?>
                                        </tbody>
                                        
                                    </table>

                                </div>
                                <div style="clear:both;"></div>
                                
                            </div>
                            
                        </div>
                        
                        <div class="postbox">
                            <div class="inside">
                                <p class="submit">
                                    <?php submit_button(__('Save Settings', 'wp404-auto-redirect'), 'primary', '', false); ?>
                                </p>
                            </div>
                        </div>
                        
                    </form>
                    
                </div>
                
                <?php $plugin_data = get_plugin_data(WP404ARSP_FILE, false, false); ?>
                <div id="postbox-container-1" class="postbox-container">
                    <div class="meta-box-sortables">
                        <div class="postbox">

                            <div class="inside">
                                <img src="<?php echo plugins_url('assets/logo.png', WP404ARSP_FILE); ?>" class="logo" />

                                <p><?php _e('Automatically redirect 404 pages to similar posts based on Title, Post Types & Taxonomies.', 'wp404-auto-redirect'); ?></p>
                                
                                <h3><?php _e('Rate us', 'wp404-auto-redirect'); ?></h3>
                                
                                <p><?php _e('Enjoying this plugin? Please rate us. It\'s always much appreciated!', 'wp404-auto-redirect'); ?></p>
                                <p><a href="https://wordpress.org/support/plugin/wp-404-auto-redirect-to-similar-post/reviews/#new-post" target="_blank" class="button"><?php _e('Rate this plugin', 'wp404-auto-redirect'); ?></a></p>
                                
                                <?php if(!wp404arsp_is_empty($plugin_data['Version'])){ ?>
                                
                                    <h3><?php _e('Changelog', 'wp404-auto-redirect'); ?></h3>
                                    <p><?php _e('See what\'s new in', 'wp404-auto-redirect'); ?> <a href="https://wordpress.org/plugins/wp-404-auto-redirect-to-similar-post/#developers" target="_blank" style="text-decoration:none;">version <?php echo $plugin_data['Version']; ?></a>.</p>
                                    
                                <?php } ?>
                                
                                <h3><?php _e('Resources', 'wp404-auto-redirect'); ?></h3>
                                
                                <ul>
                                    <li><a href="https://wordpress.org/plugins/wp-404-auto-redirect-to-similar-post/" target="_blank" style="text-decoration:none;"><i class="dashicons dashicons-admin-home"></i> <?php _e('Website', 'wp404-auto-redirect'); ?></a></li>
                                    <li><a href="https://wordpress.org/plugins/wp-404-auto-redirect-to-similar-post/" target="_blank" style="text-decoration:none;"><i class="dashicons dashicons-sos"></i> <?php _e('Documentation', 'wp404-auto-redirect'); ?></a></li>
                                    <li><a href="https://wordpress.org/support/plugin/wp-404-auto-redirect-to-similar-post" target="_blank" style="text-decoration:none;"><i class="dashicons dashicons-editor-help"></i> <?php _e('Support', 'wp404-auto-redirect'); ?></a></li>
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </div>

            </div>
            <br class="clear">
            
        </div>
    </div>
    <?php

    }
}