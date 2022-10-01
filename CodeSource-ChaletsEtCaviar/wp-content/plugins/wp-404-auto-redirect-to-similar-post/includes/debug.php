<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('WP_404_Auto_Redirect'))
    return;

trait WP_404_Auto_Redirect_Debug {

    function debug($query){
        $title = 'Fallback Redirection disabled. Displaying 404.';
        
        if(isset($query['redirect']['url']) && !empty($query['redirect']['url']))
            $title = 'Redirection: ' . "<a href='" . $query['redirect']['url'] . "'>" . $query['redirect']['url'] . "</a>" . ' (' . $query['settings']['method'] . ' Headers)';
        ?>
        
        <style type="text/css">
            .wp404arsp_debug_page{margin:0 auto; max-width:1150px; font-family: arial, sans-serif;}
            .wp404arsp_debug_page .logo{text-align:center;}
            .wp404arsp_debug_page .logo img{margin:0 auto;}
            .wp404arsp_debug_page h2,
            .wp404arsp_debug_page h4{text-align:center;}
            .wp404arsp_debug_page pre{background:#f4f4f4; padding:15px; overflow:auto;}
            .wp404arsp_debug_page a{color:blue;}
            .wp404arsp_debug_page p{font-size:12px;}
        </style>
        <div class="wp404arsp_debug_page">
        
            <?php if(!$query['preview']){ ?>
                <div class="logo">
                    <img src="<?php echo plugins_url('../assets/logo.png', __FILE__); ?>" class="logo" />
                </div>
                <h2>WP 404 Auto Redirect to Similar Post</h2>
                <p>This is the <strong>debug console</strong> of WP 404 Auto redirect to Similar Post Plugin which is only visible to administrators. Head over your <a href="<?php echo admin_url('options-general.php?page=wp-404-auto-redirect'); ?>">settings page</a> if you would like to disable it.</p>
                <hr />
            <?php } ?>
            
            <h3>Summary:</h3>
            
            <pre>Requested URL: <a href="<?php echo home_url(); ?><?php echo $query['request']['url']; ?>"><?php echo home_url(); ?><?php echo $query['request']['url']; ?></a><br />
<?php echo $title; ?><br />
Engine: <?php echo $query['redirect']['engine']; ?><br />
Details: <?php echo $query['redirect']['why']; ?></pre>
            
            <h3>Advanced:</h3>
            <pre><?php print_r($query); ?></pre>
        </div>
        
        <?php 
        exit;
    }
}