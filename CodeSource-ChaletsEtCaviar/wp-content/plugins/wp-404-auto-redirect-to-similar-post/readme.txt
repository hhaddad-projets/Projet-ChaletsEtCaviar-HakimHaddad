=== WP 404 Auto Redirect to Similar Post ===
Contributors: hwk-fr
Donate link: https://hwk.fr/
Tags: SEO, 404, Redirect, 301, Similar, Related, Search, Broken Link, Webmaster Tools, Google
Requires at least: 4.0
Tested up to: 4.9.8
Requires PHP: 5.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically Redirect any 404 page to a Similar Post based on the Title Post Type & Taxonomy using 301 or 302 Redirects!

== Description ==

Welcome to WP 404 Auto Redirect to Similar Post!

This plugin automatically redirect 404 pages to similar posts based on Title, Post Types & Taxonomies. If nothing similar is found, visitors will be redirected to the homepage or a custom URL.

= Features: =

* Automatically detect any 404.
* Automatically search a similar post based on multiple factors:
    * Title
    * Potential Post Type
    * Potential Taxonomy
* If nothing similar is found, set your Fallback Behavior:
    * Redirect to homepage
    * Redirect to a custom URL
    * Display the default 404 page
* Choose the redirection HTTP header status:
    * 301 headers
    * 302 headers
* Exclude Post Types from possible redirections.
* Exclude Taxonomies from possible redirections.
* Exclude Posts based on a custom post meta.
* Exclude Terms based on a custom term meta.
* Display the Debug Console instead of being redirected (Admin).
* Preview possible redirection from the administration panel.

= *New* Features: =

* Expose 'WP-404-Auto-Redirect' headers on 404 pages. (Admin).
* Log redirections in the /wp-content/debug.log file.
* Create your own search engines logic.
* Create your own search engines groups & fire sequence.

= *New* Engines & Groups: =

WP 404 Auto Redirect to Similar Post 1.0 introduces the concept of engines and groups which let you customize your own searching & matching logic. The plugin comes with 5 engines and 1 default group out of the box!

Default Group Engines:

1. Fix URL
Find and fix common URL mistakes.

2. Direct Match
Search for a Post that perfectly match keywords.

4. Search Post
Search for a similar Post.

5. Search Term
Search for a similar Term.

6. Search Post: Fallback
If a Post Type is set in the WP Query, redirect to the Post Type Archive.

= But Also: =

* Easy to Install / Uninstall.
* No useless data saved in Database.
* Blazing Fast Performance.

= Compatibility: =

WP 404 Auto Redirect to Similar Post is 100% compatible with all popular manual redirection plugins:

* [Redirection](https://wordpress.org/plugins/redirection/)
* [Simple 301 Redirects](https://wordpress.org/plugins/simple-301-redirects/)
* [Yoast Redirections](https://yoast.com/wordpress/plugins/seo/redirects-manager/)
* etc...

If you use one of them, but missed a manual redirection and a 404 is about to be displayed, WP 404 Auto Redirect to Similar Post will cover you.

== Reviews ==

They talk about us! :)

* [Quels plugins utiliser pour corriger les erreurs 404 sous WordPress ?](https://sebastienpierrepack.com/plugins-corriger-404-wordpress/)
* [6+ 404 Redirect WordPress Plugins 2018 (Free and Paid)](https://www.formget.com/404-redirect-wordpress-plugins/)
* [12 Best Free SEO Plugins for WordPress](https://uproer.com/articles/best-seo-plugins-wordpress/)
* [Recommended Plugins for WordPress](https://wpstuff.org/plugins/recommended-plugins-for-wordpress/)
* [Top WordPress Plugins I Can’t Do Without](http://blogingenuity.com/top-wordpress-plugins-i-cant-do-without/)
* [Permalink Finder Plugin is deprecated](http://bhaaratham.com/permalink-finder-plugin-sanitize_url-is-deprecated/)
* [Membuat halaman Error 404 menjadi SEO friendly](https://www.seosatu.com/optimalkan-seo-dengan-halaman-404/)
* [The Ultimate Guide to Starting a Travel Blog](https://www.littlemissgemtravels.com/ultimate-guide-to-starting-a-travel-blog/)
* [80  Best WordPress Plugins for 2018](https://sayoho.com/80-best-wordpress-plugins-for-2018/)

== Frequently Asked Questions ==

= Developers: Create a Custom Group =

**Advanced Usage:** If you don't know how to use filters & actions, please read the official [WordPress Plugin API](https://codex.wordpress.org/Plugin_API).


`
// Create a Group with only 3 Default Engines, and set a custom fire sequence
add_action('wp404arsp/search/init', 'my_404_group');
function my_404_group($query){
    
    wp404arsp_register_group(array(
    
        // Set Group Name
        'name' => 'My Group',
        
        // Set Group Slug
        'slug' => 'my_group',
        
        // Set Engines & the fire sequence
        'engines' => array(
            'default_post',     // Add Default: Search Post Engine
            'default_fix_url',  // Add Default: Fix URL Engine
            'default_direct',   // Add Default: Default: Direct Match Engine
        )
        
    ));
    
}

// Trigger the Custom Group: 'My Group' when the 404 Page URL starts with '/product/xxxx/'
add_filter('wp404arsp/search/group', 'my_404_group_trigger', 10, 2);
function my_404_group_trigger($group, $query){

    // Developers: Print $query array for more request context
    
    // Our condition: 404 Page URL starts with '/product/xxxx/'
    if(preg_match('#/product/(.+?)/?$#i', $query['request']['url'])){
        $group = 'my_group'; // My Group Slug
    }
    
    // Always return Group
    return $group;
    
}
`

= Developers: Create a Custom Engine =

**Advanced Usage:** If you don't know how to use filters & actions, please read the official [WordPress Plugin API](https://codex.wordpress.org/Plugin_API).


`
// Create a Custom Engine
add_action('wp404arsp/search/init', 'my_404_group_engine');
function my_404_group_engine($query){

    wp404arsp_register_engine(array(
    
        // Set Engine Name
        'name' => 'My Engine',
        
        // Set Engine Slug
        'slug' => 'my_engine',
        
        // Set Engine Weight (Score = Keyword_Found * Weight)
        'weight' => 100,
        
        // Set Primary Option (true|false). If Primary is true, then stop fire sequence if the score > 0.
        'primary' => true
        
    ));
    
    // Use the engine in a new Group called 'My Group'
    wp404arsp_register_group(array(
    
        // Set Group Name
        'name' => 'My Group',
        
        // Set Group Slug
        'slug' => 'my_group',
        
        // Set Engines & the fire sequence
        'engines' => array(
            'my_engine', // Add My Engine
        )
        
    ));
    
}

// Trigger the Custom Group: 'My Group' when the 404 Page URL starts with '/product/xxxx/'
add_filter('wp404arsp/search/group', 'my_404_group_trigger', 10, 2);
function my_404_group_trigger($group, $query){
    
    // Developers: Print $query array for more request context
    
    // Our condition: 404 Page URL starts with '/product/xxxx/'
    if(preg_match('#/product/(.+?)/?$#i', $query['request']['url'])){
        $group = 'my_group'; // My Group Slug
    }
    
    // Always return Group
    return $group;
    
}

// Define a Custom Engine Logic
add_filter('wp404arsp/search/engine/my_engine', 'my_404_engine_definition', 10, 3);
function my_404_engine_definition($result, $query, $group){

    // Developers: Print $query array for more request context

    // You have access to $query & the current $group as a context for the engine logic
    // In this example 'My Engine' is the only engine in 'My Group'
    // 'My Group' is triggered when the 404 Page URL starts with '/product/xxxx/'
    
    // What we want: Search for a similar post inside a specific Post Type: 'project'
    
    // Grab all Keywords in the URL
    $keywords = explode('-', $query['request']['keywords']['all']);
    
    // Run Search
    $search = wp404arsp_search(array(
        'keywords'  => $keywords,   // Add keywords
        'mode'      => 'post',      // Search for Post
        'post_type' => 'project',   // inside Post Type: 'project'
    ), $query);
    
    // Found something!
    if($search['score'] > 0){
        
        // Return result
        return array(
            'score' => $search['score'],
            'url'   => get_permalink($search['post_id']),
            'why'	=> "This engine is Awesome! We found a similar Product inside the Post Type <strong>project</strong>!"
        );
        
    }
    
    // Nothing found :(
    else{
        
        return "Mehh... No similar Product found inside the Post Type <strong>project</strong>.";
        
    }
    
}
`

= Developers: Manipulate existing Groups & Engines =

**Advanced Usage:** If you don't know how to use filters & actions, please read the official [WordPress Plugin API](https://codex.wordpress.org/Plugin_API).


`
add_action('wp404arsp/search/init', 'my_404_manipulate_groups_and_engines');
function my_404_manipulate_groups_and_engines($query){
    
    // Move the default engine 'Direct Match' at the end of the 'Default Group' fire Sequence
    wp404arsp_reorder_group_engines(array(
        
        // Target Group Slug
        'group' => 'default',
        
        // Target Engine Slug
        'engine' => 'default_direct',
        
        // Set new Position in fire sequence. (In this example: 4 instead of 2).
        'order' => 4
        
    ));
    
    // Register new Engines & Fire Sequence for the existing Group 'My Group'
    wp404arsp_register_group_engines(array(
    
        // Target Group Slug
        'group' => 'my_group',
        
        // New Engines & Fire Sequence
        'engines' => array(
            'my_engine',    // Add Custom: My Engine
            'default_post'  // Add Default: Search Post Engine
        )
        
    ));
    
    // Deregister an existing Engine.
    // The engine will be removed from any Groups which use it. The engine won't be registered anymore.
    
    // Target specific Engine Slug
    wp404arsp_deregister_engine('my_another_engine');
    
}
`

= Developers: Always use a custom Group =

**Advanced Usage:** If you don't know how to use filters & actions, please read the official [WordPress Plugin API](https://codex.wordpress.org/Plugin_API).


`
// Always trigger the Custom Group 'My Group' instead of the Default Group
add_filter('wp404arsp/search/group', 'my_404_group_trigger_forever', 10, 2);
function my_404_group_trigger_forever($group, $query){

    // Developers: Print $query array for more request context
    
    // Always return 'My Group'
    return 'my_group';
    
}
`

= Developers: Disable the plugin initialization at some conditions =

**Advanced Usage:** If you don't know how to use filters & actions, please read the official [WordPress Plugin API](https://codex.wordpress.org/Plugin_API).


`
// Do not load the plugin if the 404 URL starts with '/calendar/xxxx/'
add_filter('wp404arsp/init', 'my_404_no_init', 10, 2);
function my_404_no_init($init, $query){
    
    // Developers: Print $query array for more request context
    
    if(preg_match('#/calendar/(.+?)/?$#i', $query['request']['url']))
        $init = false;
        
    return $init;
    
}
`

= Developers: Send an e-mail after every redirection =

**Advanced Usage:** If you don't know how to use filters & actions, please read the official [WordPress Plugin API](https://codex.wordpress.org/Plugin_API).


`
// Do something after a redirection
add_action('wp404arsp/after_redirect', 'my_404_after_redirect');
function my_404_after_redirect($query){

    // Developers: Print $query array for more request context

    // Send me an e-mail
    wp_mail(
        'my@email.com', 
        'WP 404 Auto Redirect: New redirection', 
        'Hi! New redirection from ' . $args['request']['url'] . ' to ' . $query['redirection']['url'], 
        array('Content-Type: text/html; charset=UTF-8')
    );
    
    return;
}
`

== Installation ==

= Wordpress Install =

1. Upload the plugin files to the `/wp-content/plugins/wp-404-auto-redirect-similar-post` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to Settings > WP 404 Auto Redirect to change your settings.
4. Everything is ready! Now try to trigger a 404 page!

== Screenshots ==

1. Admin: Settings Page
2. Admin: Post Types
3. Admin: Taxonomies
4. Admin: Engines
5. Front: Debug Console

== Changelog ==

= 1.0.2 =
* Fix: Request sanitization compatibility for nginx servers

= 1.0.1 =
* Fix: Priority exception when it equals 0
* Fix: Settings page link missing from plugins page

= 1.0 =
* Added: Log redirections in the /wp-content/debug.log file.
* Added: Expose 'WP-404-Auto-Redirect' headers on 404 pages. (Admin).
* Added: Groups & Engines Feature.
* Added: Groups & Engines Documentation & Examples (developers).
* Added: Groups & Engines Admin panel.
* Added: Index.php file redirecting to root domain (avoid plugin folder file listing).
* Added: `action('wp404arsp/search/init', $query)`
* Added: `filter('wp404arsp/search/group', $group, $query)`
* Added: `filter('wp404arsp/search/query', $query)`
* Added: `filter('wp404arsp/search/engine/{engine}', $result, $query, $group)`
* Added: `filter('wp404arsp/search/results', $query)`
* Added: `filter('wp404arsp/search/redirect', $redirect, $query)`
* Improvement: Core reworked from scratch for better extensibility.
* Removed: 'Hooks' tab

= 0.9.0.2 =
* Fix: Plugin priority set to 999 by debault
* Added: New Filter available `('wp404arsp/init', $init, $request_uri)`
* Added: Filters & Actions documentation
* Added: Hooks tab documentation in Administration panel
* Improvement: Plugin's page description
* Removed: Unnecessary filter `('wp404arsp/settings', $settings)`

= 0.9.0.1 =
* Fix: Paged request redirection "Uncaught Argument" error

= 0.9 =
* Fix: 302 headers option would not save
* Fix: Redirection loop in some specific cases - Direct match on private posts
* Fix: Ajax URL for custom `/wp-admin/` path (Preview Mode)
* Added: New Filter available `('wp404arsp/settings', $settings)`
* Added: New Filter available `('wp404arsp/redirect', $args, $settings)`
* Added: New Action available `('wp404arsp/after_redirect', $args, $settings)`
* Added: New page header 'WP-404-Auto-Redirect: true' on redirection
* Added: Exclude posts with the post meta: `wp404arsp_no_redirect = 1` from possible redirections.
* Added: Exclude terms with the term meta: `wp404arsp_no_redirect = 1` from possible redirections.
* Added: Exclude one or multiple taxonomies from possible redirections.
* Added: Disable Taxonomy Redirection - Never redirect to terms archives.
* Added: Plugin priority - Advanced users only (Default 999).
* Improvement: Revamped code
* Improvement: Administration panel with tabs
* Improvement: Plugin is now translation ready

= 0.7.7 =
* Fix: PHP header() error on upgrade
* Fix: Exclude Post Type from Redirections UI & Logic
* Fix: 'Compatibility' typo in description
* Added: 999 priority on template_redirect action for compatibility
* Improvement: Updated Plugin Screenshot

= 0.7.6 =
* Fix: Typos & added better descriptions
* Fix: minor PHP Notice
* Added: "Custom Redirect URL" as Fallback Behavior (Feature Request)
* Added: "Exclude Post Type" Multi-select to possible redirections (Feature Request)
* Improvement: Better overall request validation

= 0.7.2 =
* Added: Re-introduced Term search as fallback (if no similar post found)
* Improvement: Direct match algorythm
* Improvement: Sanitization of requests with an extension
* Improvement: Post_type matching to only search "active" post_types

= 0.7.1 =
* Fix: Bug while displaying legacy 404
* Fix: PHP notice on preview mode
* Improvement: Post Types handle
* Improvement: Paged request handle

= 0.7 =
* Added: Settings page
* Added: Debug mode for administrators (settings page)
* Added: Ability to disable "Redirect to Homepage" if nothing found (Feature request)
* Added: Ability to choose between 301 or 302 HTTP Headers
* Added: Ability preview URL Redirections
* Improvement: Reworked code

= 0.4.0.2 =
* Fix: Sanitization bug
* Fix: Debug typo

= 0.4 =
* Improvement: Revamped Code
* Improvement: Processing speed
* Improvement: Better Post Type / Category / Taxonomy matching

= 0.3.2 =
* Added: Debug monitoring
* Improvement: Better management of paged requests

= 0.3 =
* Initial Release

== Upgrade Notice ==

None