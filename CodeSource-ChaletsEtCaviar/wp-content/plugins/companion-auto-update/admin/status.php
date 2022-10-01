<?php

	$dateFormat = get_option( 'date_format' );
	$dateFormat .= ' '.get_option( 'time_format' );

	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 

	// Minor updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'minor'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' && wp_get_schedule( 'wp_version_check' ) ) {
			$minorUpdates 	= true;
			$minorStatus 	= 'enabled';
			$minorIcon		= 'yes';
			$minorInterval 	= wp_get_schedule( 'wp_version_check' );
			$minorNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_version_check' ) );
		} else {
			$minorUpdates 	= false;
			$minorStatus 	= 'disabled';
			$minorIcon		= 'no';
			$minorInterval 	= '&dash;';
			$minorNext 		= '&dash;';
		}

	}

	// Major updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'major'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' && wp_get_schedule( 'wp_version_check' ) ) {
			$majorUpdates 	= true;
			$majorStatus 	= 'enabled';
			$majorIcon		= 'yes';
			$majorInterval 	= wp_get_schedule( 'wp_version_check' );
			$majorNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_version_check' ) );
		} else {
			$majorUpdates 	= false;
			$majorStatus 	= 'disabled';
			$majorIcon		= 'no';
			$majorInterval 	= '&dash;';
			$majorNext 		= '&dash;';
		}

	}

	// Plugin updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'plugins'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' && wp_get_schedule( 'wp_update_plugins' ) ) {
			$pluginsUpdates 	= true;
			$pluginsStatus 		= 'enabled';
			$pluginsIcon		= 'yes';
			$pluginsInterval 	= wp_get_schedule( 'wp_update_plugins' );
			$pluginsNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_update_plugins' ) );
		} else {
			$pluginsUpdates 	= false;
			$pluginsStatus 		= 'disabled';
			$pluginsIcon		= 'no';
			$pluginsInterval 	= '&dash;';
			$pluginsNext 		= '&dash;';
		}

	}

	// Themes updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'themes'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' && wp_get_schedule( 'wp_update_plugins' ) ) {
			$themesUpdates 		= true;
			$themesStatus 		= 'enabled';
			$themesIcon			= 'yes';
			$themesInterval 	= wp_get_schedule( 'wp_update_plugins' );
			$themesNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_update_plugins' ) );
		} else {
			$themesUpdates 		= false;
			$themesStatus 		= 'disabled';
			$themesIcon			= 'no';
			$themesInterval 	= '&dash;';
			$themesNext 		= '&dash;';
		}

	}

	if ( wp_next_scheduled ( 'cau_set_schedule_mail' ) ) {
		$setScheduleStatus  	= 'enabled';
		$setScheduleIcon  		= 'yes';
		$setScheduleInterval 	= wp_get_schedule( 'cau_set_schedule_mail' );
		$setScheduleNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'cau_set_schedule_mail' ) );
	} else {
		$setScheduleStatus  	= 'disabled';
		$setScheduleIcon  		= 'no';
		$setScheduleInterval 	= '&dash;';
		$setScheduleNext 		= '&dash;';
	}

?>

<h2><?php _e('Status', 'companion-auto-update'); ?></h2>

<div class="cau_status_page">

<table class="cau_status_list widefat striped">

	<thead>
		<tr>
			<th width="300" class="cau_status_name"><strong><?php _e('Updaters', 'companion-auto-update'); ?></strong></th>
			<th class="cau_status_active_state"><strong><?php _e('Active?', 'companion-auto-update'); ?></strong></th>
			<th class="cau_status_interval"><strong><?php _e('Interval', 'companion-auto-update'); ?></strong></th>
			<th class="cau_status_next"><strong><?php _e('Next', 'companion-auto-update'); ?></strong></th>
		</tr>
	</thead>

	<tbody id="the-list">
		<tr>
			<td class="cau_status_name"><?php _e('Plugins', 'companion-auto-update'); ?></td>
			<td class="cau_status_active_state"><span class='cau_<?php echo $pluginsStatus; ?>'><span class="dashicons dashicons-<?php echo $pluginsIcon; ?>"></span></span></td>
			<td class="cau_status_interval"><?php echo $pluginsInterval; ?></td>
			<td class="cau_status_next"><span class="cau_mobile_prefix"><?php _e( 'Next', 'companion-auto-update' ); ?>: </span><?php echo $pluginsNext; ?></td>
		</tr>
		<tr>
			<td class="cau_status_name"><?php _e('Themes', 'companion-auto-update'); ?></td>
			<td class="cau_status_active_state"><span class='cau_<?php echo $themesStatus; ?>'><span class="dashicons dashicons-<?php echo $themesIcon; ?>"></span></span></td>
			<td class="cau_status_interval"><?php echo $themesInterval; ?></td>
			<td class="cau_status_next"><span class="cau_mobile_prefix"><?php _e( 'Next', 'companion-auto-update' ); ?>: </span><?php echo $themesNext; ?></td>
		</tr>
		<tr>
			<td class="cau_status_name"><?php _e('Core (Minor)', 'companion-auto-update'); ?></td>
			<td class="cau_status_active_state"><span class='cau_<?php echo $minorStatus; ?>'><span class="dashicons dashicons-<?php echo $minorIcon; ?>"></span></span></td>
			<td class="cau_status_interval"><?php echo $minorInterval; ?></td>
			<td class="cau_status_next"><span class="cau_mobile_prefix"><?php _e( 'Next', 'companion-auto-update' ); ?>: </span><?php echo $minorNext; ?></td>
		</tr>
		<tr>
			<td class="cau_status_name"><?php _e('Core (Major)', 'companion-auto-update'); ?></td>
			<td class="cau_status_active_state"><span class='cau_<?php echo $majorStatus; ?>'><span class="dashicons dashicons-<?php echo $majorIcon; ?>"></span></span></td>
			<td class="cau_status_interval"><?php echo $majorInterval; ?></td>
			<td class="cau_status_next"><span class="cau_mobile_prefix"><?php _e( 'Next', 'companion-auto-update' ); ?>: </span><?php echo $majorNext; ?></td>
		</tr>
	</tbody>

</table>

<table class="cau_status_list widefat striped">

	<thead>
		<tr>
			<th width="300" class="cau_status_name"><strong><?php _e('Other', 'companion-auto-update'); ?></strong></th>
			<th class="cau_status_active_state"><strong><?php _e('Active?', 'companion-auto-update'); ?></strong></th>
			<th class="cau_status_interval"><strong><?php _e('Interval', 'companion-auto-update'); ?></strong></th>
			<th class="cau_status_next"><strong><?php _e('Next', 'companion-auto-update'); ?></strong></th>
		</tr>
	</thead>

	<tbody id="the-list">
		<tr>
			<td class="cau_status_name"><?php _e( 'Email Notifications', 'companion-auto-update' ); ?></td>
			<td class="cau_status_active_state"><span class='cau_<?php echo $setScheduleStatus; ?>'><span class="dashicons dashicons-<?php echo $setScheduleIcon; ?>"></span></span></td>
			<td class="cau_status_interval"><?php echo $setScheduleInterval; ?></td>
			<td class="cau_status_next"><span class="cau_mobile_prefix"><?php _e( 'Next', 'companion-auto-update' ); ?>: </span><?php echo $setScheduleNext; ?></td>
		</tr>
	</tbody>

</table>

<?php 

if( get_option( 'blog_public' ) == 0 ) { ?>

	<table class="cau_status_list widefat striped cau_status_warnings">

		<thead>
			<tr>
				<th class="cau_plugin_issue_name"><strong><?php _e( 'Search Engine Visibility' ); ?></strong></th>
				<th class="cau_plugin_issue_explain"> </th>
				<th class="cau_plugin_issue_fixit"><strong><?php _e( 'Fix it', 'companion-auto-update' ); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">
			<tr>
				<td class="cau_plugin_issue_name"><span class='cau_warning'><span class="dashicons dashicons-warning"></span> <?php _e( 'Warning', 'companion-auto-update' ); ?></span></td>
				<td class="cau_plugin_issue_explain">
					<?php _e( 'You’ve chosen to disscourage Search Engines from indexing your site. Auto-updating works best on sites with more traffic, consider enabling indexing for your site.', 'companion-auto-update' ); ?>
				</td>
				<td class="cau_plugin_issue_fixit">
					<a href="<?php echo admin_url( 'options-reading.php' ); ?>" class="button button-alt"><?php _e( 'Fix it', 'companion-auto-update' ); ?></a>
				</td>
			</tr>
		</tbody>

	</table>
    
<?php }

if( checkAutomaticUpdaterDisabled() ) { ?>

	<table class="cau_status_list widefat striped cau_status_warnings">

		<thead>
			<tr>
				<th class="cau_plugin_issue_name"><strong><?php _e( 'Critical Error', 'companion-auto-update' ); ?></strong></th>
				<th class="cau_plugin_issue_explain"> </th>
				<th class="cau_plugin_issue_fixit"><strong><?php _e( 'How to fix', 'companion-auto-update' ); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">
			<tr>
				<td class="cau_plugin_issue_name"><span class='cau_disabled'><span class="dashicons dashicons-no"></span> <?php _e( 'Critical Error', 'companion-auto-update' ); ?></span></td>
				<td class="cau_plugin_issue_explain">
					<?php _e( 'Updating is globally disabled.', 'companion-auto-update' ); ?>
				</td>
				<td class="cau_plugin_issue_fixit">
					<form method="POST">
						<button type="submit" name="fixit" class="button button-primary"><?php _e( 'Fix it', 'companion-auto-update' ); ?></button>
						<a href="<?php echo admin_url( cau_menloc().'?page=cau-settings&tab=support' ); ?>" class="button"><?php _e( 'Contact for support', 'companion-auto-update' ); ?></a>
					</form>
				</td>
			</tr>
		</tbody>

	</table>

<?php } 

// Remove the line
if( isset( $_POST['fixit'] ) ) {
	cau_removeErrorLine();
	echo "<div id='message' class='updated'><p><strong>".__( 'Error fixed', 'companion-auto-update' )."</strong></p></div>";
}

// Get wp-config location
function cau_configFile() {

	// Config file
	if ( file_exists( ABSPATH . 'wp-config.php') ) {
		$conFile = ABSPATH . 'wp-config.php';
	} else {
		$conFile = dirname(ABSPATH) . '/wp-config.php';
	}

	return $conFile;

}

// Change the AUTOMATIC_UPDATER_DISABLED line
function cau_removeErrorLine() {

	// Config file
	$conFile = cau_configFile();

	// Lines to check and replace
	$revLine 		= "define('AUTOMATIC_UPDATER_DISABLED', false);"; // We could just remove the line, but replacing it will be safer
	$oldLine 		= array( "define('AUTOMATIC_UPDATER_DISABLED', true);", "define('AUTOMATIC_UPDATER_DISABLED', minor);","define('automatic_updater_disabled', true);", "define('automatic_updater_disabled', minor);" );

	// Check for each string if it exists
	foreach ( $oldLine as $key => $string ) {

		if( strpos( file_get_contents( $conFile ), $string ) !== false) {
	        $contents = file_get_contents( $conFile );
			$contents = str_replace( $string, $revLine, $contents );
			file_put_contents( $conFile, $contents );
	    }

	}

}

// If has incomptable plugins
if( cau_incompatiblePlugins() ) { ?>

	<p>&nbsp;</p>
	<h2 style="margin-bottom: 3px"><?php _e('Possible plugin issues', 'companion-auto-update'); ?></h2>
	<span class='cau_disabled'><?php _e("You're using one or more plugins that <i>might</i> cause issues.", "companion-auto-update"); ?></span>

	<table class="cau_status_list widefat striped cau_status_warnings">

		<thead>
			<tr>
				<th class="cau_plugin_issue_name"><strong><?php _e( 'Name', 'companion-auto-update' ); ?></strong></th>
				<th class="cau_plugin_issue_explain"><strong><?php _e( 'Possible issue', 'companion-auto-update' ); ?></strong></th>
				<th class="cau_plugin_issue_fixit"><strong><?php _e( 'How to fix', 'companion-auto-update' ); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">
			<?php
			foreach ( cau_incompatiblePluginlist() as $key => $value ) {
				if( is_plugin_active( $key ) ) {

					echo '<tr>
						<td class="cau_plugin_issue_name">'.$key.'</td>
						<td class="cau_plugin_issue_explain">'.$value.'</td>
						<td class="cau_plugin_issue_fixit"><a href="'.admin_url( cau_menloc().'?page=cau-settings&tab=support' ).'" class="button">'.__( 'Contact for support', 'companion-auto-update' ).'</a></td>
					</tr>';
				
				}
			}
			?>
		</tbody>

	</table>

<?php } ?>

	<table class="cau_status_list widefat striped cau_status_warnings">

		<thead>
			<tr>
				<th colspan="2"><strong><?php _e( 'Systemifo', 'companion-auto-update' ); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">
			<tr>
				<td width="200">WordPress</td>
				<td><?php echo get_bloginfo( 'version' ); ?></td>
			</tr>
			<tr>
				<td>PHP</td>
				<td><?php echo phpversion(); ?></td>
			</tr>
		</tbody>

	</table>

</div>