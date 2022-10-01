(function() {
    if (typeof(wpdreams_asl_mce_button_menu)!="undefined") {
      tinymce.PluginManager.add('wpdreams_asl_mce_button', function( editor, url ) {
          eval("var asl_menus = [" + wpdreams_asl_mce_button_menu + "]");
          eval("var asl_res_menus = [" + wpdreams_asl_res_mce_button_menu + "]");
          editor.addButton( 'wpdreams_asl_mce_button', {
              text: 'ASP',
              icon: false,
              type: 'menubutton',
              menu: [
                  {
                      text: 'Search box',
                      menu: asl_menus
                  },
                  {
                      text: 'Result box',
                      menu: asl_res_menus
                  }
              ]
          });
      });
    }
})();