<?php

$plugin_schedule 	= wp_get_schedule( 'wp_update_plugins' );
$theme_schedule 	= wp_get_schedule( 'wp_update_themes' );
$core_schedule 		= wp_get_schedule( 'wp_version_check' );
$mail_sc 			= wp_get_schedule( 'cau_set_schedule_mail' );
$cs_hooks_p 		= wp_get_schedule( 'cau_custom_hooks_plugins' );
$cs_hooks_t 		= wp_get_schedule( 'cau_custom_hooks_themes' );

if( isset( $_POST['submit'] ) ) {

	check_admin_referer( 'cau_save_schedule' );

	// Set variables
	$plugin_sc 		= sanitize_text_field( $_POST['plugin_schedule'] );
	$theme_sc 		= sanitize_text_field( $_POST['theme_schedule'] );
	$core_sc 		= sanitize_text_field( $_POST['core_schedule'] );
	$schedule_mail 	= sanitize_text_field( $_POST['schedule_mail'] );


	// First clear schedules
	wp_clear_scheduled_hook('wp_update_plugins');
	wp_clear_scheduled_hook('wp_update_themes');
	wp_clear_scheduled_hook('wp_version_check');
	wp_clear_scheduled_hook('cau_set_schedule_mail');
	wp_clear_scheduled_hook('cau_custom_hooks_plugins');
	wp_clear_scheduled_hook('cau_custom_hooks_themes');

	// Then set the new times

	// Plugins
	if( $plugin_sc == 'daily' ) {

		$date 				= date( 'Y-m-d' );
		$hours 				= sanitize_text_field( $_POST['pluginScheduleTimeH'] );
		$minutes 			= sanitize_text_field( $_POST['pluginScheduleTimeM'] );
		$seconds 			= date( 's' );
		$fullDate 			= $date.' '.$hours.':'.$minutes.':'.$seconds;
		$pluginSetTime 		= strtotime( $fullDate );

		wp_schedule_event( $pluginSetTime, $plugin_sc, 'wp_update_plugins' );
		wp_schedule_event( $pluginSetTime, $plugin_sc, 'cau_custom_hooks_plugins' );

	} else {

		wp_schedule_event( time(), $plugin_sc, 'wp_update_plugins' );
		wp_schedule_event( time(), $plugin_sc, 'cau_custom_hooks_plugins' );

	}

	// Themes
	if( $theme_sc == 'daily' ) {

		$dateT 				= date( 'Y-m-d' );
		$hoursT 			= sanitize_text_field( $_POST['ThemeScheduleTimeH'] );
		$minutesT 			= sanitize_text_field( $_POST['ThemeScheduleTimeM'] );
		$secondsT 			= date( 's' );
		$fullDateT 			= $dateT.' '.$hoursT.':'.$minutesT.':'.$secondsT;
		$themeSetTime 		= strtotime( $fullDateT );

		wp_schedule_event( $themeSetTime, $theme_sc, 'wp_update_themes' );
		wp_schedule_event( $themeSetTime, $theme_sc, 'cau_custom_hooks_themes' );

	} else {

		wp_schedule_event( time(), $theme_sc, 'wp_update_themes' );
		wp_schedule_event( time(), $theme_sc, 'cau_custom_hooks_themes' );

	}

	// Core
	if( $core_sc == 'daily' ) {

		$dateC 				= date( 'Y-m-d' );
		$hoursC 			= sanitize_text_field( $_POST['CoreScheduleTimeH'] );
		$minutesC 			= sanitize_text_field( $_POST['CoreScheduleTimeM'] );
		$secondsC 			= date( 's' );
		$fullDateC 			= $dateC.' '.$hoursC.':'.$minutesC.':'.$secondsC;
		$coreSetTime 		= strtotime( $fullDateC );

		wp_schedule_event( $coreSetTime, $core_sc, 'wp_version_check' );

	} else {

		wp_schedule_event( time(), $core_sc, 'wp_version_check' );

	}

	wp_schedule_event( time(), $schedule_mail, 'cau_set_schedule_mail' );

	header( "Location: ".cau_menloc()."?page=cau-settings&tab=schedule&showmessage=true" );

}

if( isset( $_GET['showmessage'] ) ) {

	echo '<div id="message" class="updated"><p>'.__( 'Settings saved.' ).'</p></div>';

} 

?>
<div style="clear: both;"></div>

<div class="cau-column-wide">
	<form method="POST">

		<p>
			<?php _e( 'How often should the auto updater kick in? (Default twice daily)', 'companion-auto-update' ); ?>.<br />
			<i><?php _e( 'Changing these settings may affect your sites perfomance.', 'companion-auto-update' ); ?></i>
		</p>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( 'Plugin update interval', 'companion-auto-update' );?></th>
				<td>
					<p>
						<select name='plugin_schedule' id='plugin_schedule'>
							<option value='hourly' <?php if( $plugin_schedule == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
							<option value='twicedaily' <?php if( $plugin_schedule == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
							<option value='daily' <?php if( $plugin_schedule == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
						</select>
					</p>
					<div class='timeSchedulePlugins' <?php if( $plugin_schedule != 'daily' ) { echo "style='display: none;'"; } ?> >

						<?php 

						$setTimePlugins 	= wp_next_scheduled( 'wp_update_plugins' );
						$setTimePluginsHour = date( 'H' , $setTimePlugins );
						$setTimePluginsMin 	= date( 'i' , $setTimePlugins ); 

						?>

						<div class='cau_schedule_input'>
							<input type='text' name='pluginScheduleTimeH' value='<?php echo $setTimePluginsHour; ?>' maxlength='2' >
						</div><div class='cau_schedule_input_div'>
							:
						</div><div class='cau_schedule_input'>
							<input type='text' name='pluginScheduleTimeM' value='<?php echo $setTimePluginsMin; ?>' maxlength='2' > 
						</div><div class='cau_shedule_notation'>
							<b><?php _e('Time notation: 24H', 'companion-auto-update'); ?></b>
						</div>
						
						<p class='description'><?php _e('At what time should the updater run? Only works when set to <u>daily</u>.', 'companion-auto-update'); ?> </p>

					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Theme update interval', 'companion-auto-update' );?></th>
				<td>
					<p>

						<select name='theme_schedule' id='theme_schedule'>
							<option value='hourly' <?php if( $theme_schedule == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
							<option value='twicedaily' <?php if( $theme_schedule == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
							<option value='daily' <?php if( $theme_schedule == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
						</select>
					</p>
					<div class='timeScheduleThemes' <?php if( $theme_schedule != 'daily' ) { echo "style='display: none;'"; } ?> >

						<?php 

						$setTimeThemes 		= wp_next_scheduled( 'wp_update_themes' );
						$setTimeThemesHour 	= date( 'H' , $setTimeThemes );
						$setTimeThemesMins 	= date( 'i' , $setTimeThemes );

						?>

						<div class='cau_schedule_input'>
							<input type='text' name='ThemeScheduleTimeH' value='<?php echo $setTimeThemesHour; ?>' maxlength='2' >
						</div><div class='cau_schedule_input_div'>
							:
						</div><div class='cau_schedule_input'>
							<input type='text' name='ThemeScheduleTimeM' value='<?php echo $setTimeThemesMins; ?>' maxlength='2' > 
						</div><div class='cau_shedule_notation'>
							<b><?php _e('Time notation: 24H', 'companion-auto-update'); ?></b>
						</div>
						
						<p class='description'><?php _e( 'At what time should the updater run? Only works when set to <u>daily</u>.', 'companion-auto-update' ); ?> </p>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Core update interval', 'companion-auto-update' );?></th>
				<td>
					<p>
						<select name='core_schedule' id='core_schedule'>
							<option value='hourly' <?php if( $core_schedule == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
							<option value='twicedaily' <?php if( $core_schedule == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
							<option value='daily' <?php if( $core_schedule == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
						</select>
					</p>
					<div class='timeScheduleCore' <?php if( $core_schedule != 'daily' ) { echo "style='display: none;'"; } ?> >

						<?php 

						$setTimeCore 		= wp_next_scheduled( 'wp_version_check' );
						$setTimeCoreHour 	= date( 'H' , $setTimeCore );
						$setTimeCoreMins 	= date( 'i' , $setTimeCore );

						?>

						<div class='cau_schedule_input'>
							<input type='text' name='CoreScheduleTimeH' value='<?php echo $setTimeCoreHour; ?>' maxlength='2' >
						</div><div class='cau_schedule_input_div'>
							:
						</div><div class='cau_schedule_input'>
							<input type='text' name='CoreScheduleTimeM' value='<?php echo $setTimeCoreMins; ?>' maxlength='2' > 
						</div><div class='cau_shedule_notation'>
							<b><?php _e('Time notation: 24H', 'companion-auto-update'); ?></b>
						</div>
						
						<p class='description'><?php _e( 'At what time should the updater run? Only works when set to <u>daily</u>.', 'companion-auto-update' ); ?> </p>
					</div>
				</td>
			</tr>		
		</table>

		<div class="cau_spacing"></div>

		<h2 class="title"><?php _e( 'Email Notifications', 'companion-auto-update' );?></h2>
		<?php _e( 'How often should notifications be send? (Default daily)', 'companion-auto-update' ); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( 'Email Notifications', 'companion-auto-update' );?></th>
				<td>
					<p>
						<select name='schedule_mail'>
							<option value='hourly' <?php if( $mail_sc == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
							<option value='twicedaily' <?php if( $mail_sc == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
							<option value='daily' <?php if( $mail_sc == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
						</select>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Disable Notifications', 'companion-auto-update' );?></th>
				<td>
					<p>
						<?php _e('To disable email notifications go to the dashboard and uncheck everything under "Email Notifications".', 'companion-auto-update');?>
					</p>
				</td>
			</tr>
		</table>

		<?php wp_nonce_field( 'cau_save_schedule' ); ?>

		<div class="cau_spacing"></div>
		<?php submit_button(); ?>

	</form>

</div><div class="cau-column-small">

	<div class="welcome-to-cau love-bg cau-show-love cau-dashboard-box">
		<h3><?php _e( 'Like our plugin?', 'companion-auto-update' ); ?></h3>
		<p><?php _e('Companion Auto Update is free to use. It has required a great deal of time and effort to develop and you can help support this development by making a small donation.<br />You get useful software and we get to carry on making it better.', 'companion-auto-update'); ?></p>
		<a href="<?php echo cau_donateUrl(); ?>" target="_blank" class="cau-button donate-button">
			<span class="dashicons dashicons-heart"></span> 
			<?php _e('Donate to help development', 'companion-auto-update'); ?>
		</a>
		<a href="https://wordpress.org/support/plugin/companion-auto-update/reviews/#new-post" target="_blank" class="cau-button rate-button">
			<span class="dashicons dashicons-star-filled"></span> 
			<?php _e('Rate us (5 stars?)', 'companion-auto-update'); ?>
		</a>
	</div>

</div>

<script type="text/javascript">
	
	jQuery( '#plugin_schedule' ).change( function() {

		var selected = jQuery(this).val();

		if( selected == 'daily' ) {
			jQuery('.timeSchedulePlugins').show();
		} else {
			jQuery('.timeSchedulePlugins').hide();
		}

	});
	
	jQuery( '#theme_schedule' ).change( function() {

		var selected = jQuery(this).val();

		if( selected == 'daily' ) {
			jQuery('.timeScheduleThemes').show();
		} else {
			jQuery('.timeScheduleThemes').hide();
		}

	});
	
	jQuery( '#core_schedule' ).change( function() {

		var selected = jQuery(this).val();

		if( selected == 'daily' ) {
			jQuery('.timeScheduleCore').show();
		} else {
			jQuery('.timeScheduleCore').hide();
		}

	});

</script>