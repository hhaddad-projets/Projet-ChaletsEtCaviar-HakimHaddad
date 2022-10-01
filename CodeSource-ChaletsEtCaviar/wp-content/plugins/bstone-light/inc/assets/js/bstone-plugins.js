/**
 * Install Bstone Demo Sites
 *
 *
 * @since 1.1.1
 */

(function($){

	BstoneThemeAdmin = {

		init: function()
		{
			this._bind();
		},


		/**
		 * Binds events for the Bstone Theme.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$( document ).on('click' , '.bst-sites-notinstalled', BstoneThemeAdmin._installNow );
			$( document ).on('click' , '.bst-sites-inactive', BstoneThemeAdmin._activatePlugin);
			$( document ).on('wp-plugin-install-success' , BstoneThemeAdmin._activatePlugin);
			$( document ).on('wp-plugin-installing'      , BstoneThemeAdmin._pluginInstalling);
			$( document ).on('wp-plugin-install-error'   , BstoneThemeAdmin._installError);
		},

		/**
		 * Plugin Installation Error.
		 */
		_installError: function( event, response ) {

			var $card = jQuery( '.bst-sites-notinstalled' );

			$card
				.removeClass( 'button-primary' )
				.addClass( 'disabled' )
				.html( wp.updates.l10n.installFailedShort );
		},

		/**
		 * Installing Plugin
		 */
		_pluginInstalling: function(event, args) {
			event.preventDefault();

			var $card = jQuery( '.bst-sites-notinstalled' );

            $card
                .addClass('updating-message')
                .prepend( '<span class="dashicons dashicons-update rotating"></span>' );

		},
		/**
		 * Activate Success
		 */
		_activatePlugin: function( event, response ) {

			event.preventDefault();

			var $message = $( '.bst-sites-notinstalled' );
			if ( 0 === $message.length ) {
				$message = $( '.bst-sites-inactive' );
			}

			// Transform the 'Install' button into an 'Activate' button.
			var $init = $message.data('init');

			$message.removeClass( 'install-now installed button-disabled disabled updated-message' )
				.addClass('updating-message')
				.html( bstone.btnActivating );

			// WordPress adds "Activate" button after waiting for 1000ms. So we will run our activation after that.
			setTimeout( function() {

				$.ajax({
					url: bstone.ajaxUrl,
					type: 'POST',
					data: {
						'action'            : 'one-click-demo-import-plugin-activate',
						'init'              : $init,
					},
				})
				.done(function (result) {

					if( result.success ) {
						var output = '<a href="'+ bstone.bstoneSitesLink +'" aria-label="'+ bstone.bstoneSitesLinkTitle +'">' + bstone.bstoneSitesLinkTitle +' </a>'
						$message.removeClass( 'bst-sites-inactive bst-sites-notinstalled button button-primary install-now activate-now updating-message' )
							.html( output );

					} else {

						$message.removeClass( 'updating-message' );

					}

				});

			}, 1200 );

		},

		/**
		 * Install Now
		 */
		_installNow: function(event)
		{
			event.preventDefault();

			var $button 	= jQuery( event.target ),
				$document   = jQuery(document);

			if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
				return;
			}

			if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
				wp.updates.requestFilesystemCredentials( event );

				$document.on( 'credential-modal-cancel', function() {
					var $message = $( '.bst-sites-notinstalled.updating-message' );

					$message
						.addClass('bst-sites-inactive')
						.removeClass( 'updating-message bst-sites-notinstalled' )
						.text( wp.updates.l10n.installNow );

					wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
				} );
			}

			wp.updates.installPlugin( {
				slug:    $button.data( 'slug' )
			} );
		},
	};

	/**
	 * Initialize BstoneThemeAdmin
	 */
	$(function(){
		BstoneThemeAdmin.init();
	});

})(jQuery);