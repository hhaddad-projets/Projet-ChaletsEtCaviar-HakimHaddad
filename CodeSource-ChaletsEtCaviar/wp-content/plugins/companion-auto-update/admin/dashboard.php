<?php 

	if ( !wp_next_scheduled ( 'cau_set_schedule_mail' ) ) {
		echo '<div id="message" class="error"><p><b>'.__('Companion Auto Update was not able to set the event for sending you emails, please re-activate the plugin in order to set the event', 'companion-auto-update').'.</b></p></div>';
	}

	global $cau_db_version;

	if ( get_site_option( 'cau_db_version' ) != $cau_db_version ) {
		echo '<div id="message" class="error"><p><b>'.__('Database Update', 'companion-auto-update').' &ndash;</b> '.__('It seems like something went wrong while updating the database, please re-activate this plugin', 'companion-auto-update').'.</p></div>';
	}

	if( isset( $_POST['submit'] ) ) {

		check_admin_referer( 'cau_save_settings' );

		global $wpdb;
		$table_name = $wpdb->prefix . "auto_updates"; 

		$plugins 		= sanitize_text_field( $_POST['plugins'] );
		$themes 		= sanitize_text_field( $_POST['themes'] );
		$minor 			= sanitize_text_field( $_POST['minor'] );
		$major 			= sanitize_text_field( $_POST['major'] );
		$translations 	= sanitize_text_field( $_POST['translations'] );
		$send 			= sanitize_text_field( $_POST['cau_send'] );
		$sendupdate 	= sanitize_text_field( $_POST['cau_send_update'] );
		$wpemails 		= sanitize_text_field( $_POST['wpemails'] );
		$email 			= sanitize_text_field( $_POST['cau_email'] );

		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'plugins'", $plugins ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'themes'", $themes ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'minor'", $minor ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'major'", $major ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'translations'", $translations ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'email'", $email ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'send'", $send ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'sendupdate'", $sendupdate ) );
		$wpdb->query( $wpdb->prepare( "UPDATE $table_name SET onoroff = %s WHERE name = 'wpemails'", $wpemails ) );

		echo '<div id="message" class="updated"><p><b>'.__( 'Settings saved.' ).'</b></p></div>';

	}

	if( isset( $_GET['welcome'] ) ) {

		echo '<div class="welcome-to-cau welcome-bg">
			<h2>'.__( 'Welcome to Companion Auto Update', 'companion-auto-update' ).'</h2>
			<div class="welcome-column welcome-column-first welcome-column-half">
				<h3>'.__( 'You\'re set and ready to go', 'companion-auto-update' ).'</h3>
				<p>'.__( 'The plugin is all set and ready to go with the recommended settings, but if you\'d like you can change them below.' ).'</p>
			</div><div class="welcome-column welcome-column-quarter">
				<h3>'.__( 'Get Started' ).'</h3>
				<ul>
					<li><a href="'.admin_url( cau_menloc().'?page=cau-settings&tab=pluginlist&cau_page=advanced' ).'">'.__( 'Select plugins', 'companion-auto-update' ).'</a></li>
					<li><a href="'.admin_url( cau_menloc().'?page=cau-settings&tab=schedule&cau_page=advanced' ).'">'.__( 'Advanced settings', 'companion-auto-update' ).'</a></li>
				</ul>
			</div><div class="welcome-column welcome-column-quarter">
				<h3>'.__( 'More Actions' ).'</h3>
				<ul>
					<li><a href="http://codeermeneer.nl/cau_poll/" target="_blank">'.__('Give feedback', 'companion-auto-update').'</a></li>
					<li><a href="https://translate.wordpress.org/projects/wp-plugins/companion-auto-update/" target="_blank">'.__( 'Help us translate', 'companion-auto-update' ).'</a></li>
		
				</ul>
			</div>
		</div>';
	}

	?>

	<form method="POST">

	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Auto Updater', 'companion-auto-update');?></th>
			<td>
				<fieldset>

					<?php

					global $wpdb;
					$table_name = $wpdb->prefix . "auto_updates"; 

					$cau_configs = $wpdb->get_results( "SELECT * FROM $table_name" );

					echo '<p><input id="'.$cau_configs[0]->name.'" name="'.$cau_configs[0]->name.'" type="checkbox"';
					if( $cau_configs[0]->onoroff == 'on' ) echo 'checked';
					echo '/> <label for="'.$cau_configs[0]->name.'">'.__('Auto update plugins?', 'companion-auto-update').'</label></p>';

					echo '<p><input id="'.$cau_configs[1]->name.'" name="'.$cau_configs[1]->name.'" type="checkbox"';
					if( $cau_configs[1]->onoroff == 'on' ) echo 'checked';
					echo '/> <label for="'.$cau_configs[1]->name.'">'.__('Auto update themes?', 'companion-auto-update').'</label></p>';


					echo '<p><input id="'.$cau_configs[2]->name.'" name="'.$cau_configs[2]->name.'" type="checkbox"';
					if( $cau_configs[2]->onoroff == 'on' ) echo 'checked';
					echo '/> <label for="'.$cau_configs[2]->name.'">'.__('Auto update minor core updates?', 'companion-auto-update').' <code class="majorMinorExplain">4.0.0 > 4.0.1</code></label></p>';


					echo '<p><input id="'.$cau_configs[3]->name.'" name="'.$cau_configs[3]->name.'" type="checkbox"';
					if( $cau_configs[3]->onoroff == 'on' ) echo 'checked';
					echo '/> <label for="'.$cau_configs[3]->name.'">'.__('Auto update major core updates?', 'companion-auto-update').' <code class="majorMinorExplain">4.0.0 > 4.1.0</code></label></p>';

					echo '<p><input id="'.$cau_configs[8]->name.'" name="'.$cau_configs[8]->name.'" type="checkbox"';
					if( $cau_configs[8]->onoroff == 'on' ) echo 'checked';
					echo '/> <label for="'.$cau_configs[8]->name.'">'.__('Auto update translation files?', 'companion-auto-update').'</label></p>';

					?>

				</fieldset>
			</td>
		</tr>
	</table>

	<div class="cau_spacing"></div>

	<h2 class="title"><?php _e( 'Email Notifications', 'companion-auto-update' );?></h2>
	<p><?php _e( 'Email notifications are send once a day, you can choose what notifications to send below.', 'companion-auto-update' );?></p>

	<?php
	if( $cau_configs[4]->onoroff == '' ) $toemail = get_option('admin_email'); 
	else $toemail = $cau_configs[4]->onoroff;
	?>

	<table class="form-table">
		<tr>
			<th scope="row"><?php _e( 'Update available', 'companion-auto-update' );?></th>
			<td>
				<p>
					<input id="cau_send" name="cau_send" type="checkbox" <?php if( $cau_configs[5]->onoroff == 'on' ) { echo 'checked'; } ?> />
					<label for="cau_send"><?php _e('Send me emails when an update is available.', 'companion-auto-update');?></label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e( 'Successful update', 'companion-auto-update' );?></th>
			<td>
				<p>
					<input id="cau_send_update" name="cau_send_update" type="checkbox" <?php if( $cau_configs[6]->onoroff == 'on' ) { echo 'checked'; } ?> />
					<label for="cau_send_update"><?php _e('Send me emails when something has been updated.', 'companion-auto-update');?></label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e( 'Email Address' );?></th>
			<td>
				<p>
					<label for="cau_email"><?php _e('To', 'companion-auto-update');?>:</label>
					<input type="text" name="cau_email" id="cau_email" class="regular-text" placeholder="<?php echo get_option('admin_email'); ?>" value="<?php echo esc_html( $toemail ); ?>" />
				</p>

				<p class="description"><?php _e('Seperate email addresses using commas.', 'companion-auto-update');?></p>
			</td>
		</tr>
	</table>

	<div class="cau_spacing"></div>

	<h2 class="title"><?php _e('Core notifications', 'companion-auto-update');?></h2>
	<p><?php _e('Core notifications are handled by WordPress and not by this plugin. You can only disable them, changing your email address in the settings above will not affect these notifications.', 'companion-auto-update');?></p>

	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Core notifications', 'companion-auto-update');?></th>
			<td>
				<p>
					<input id="wpemails" name="wpemails" type="checkbox" <?php if( $cau_configs[9]->onoroff == 'on' ) { echo 'checked'; } ?> />
					<label for="wpemails"><?php _e('By default wordpress sends an email when a core update happend. Uncheck this box to disable these emails.', 'companion-auto-update');?></label>
				</p>
			</td>
		</tr>
	</table>

	<?php wp_nonce_field( 'cau_save_settings' ); ?>	

	<?php submit_button(); ?>

	</form>
