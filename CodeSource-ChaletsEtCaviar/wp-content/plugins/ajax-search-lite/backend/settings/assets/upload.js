/*jQuery(document).ready(function() { 
  var _self = this;
  jQuery('.upload_image_button').click(function() {
   _self.field = jQuery(this).prev();
   tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true', false);
   return false;
  });
   
  window.send_to_editor = function(html) {
   imgurl = jQuery('img',html).attr('src');
   _self.field.val(imgurl);
   tb_remove();
   _self.createPreview();
  } 
  
  this.createPreview = function() {
    node = _self.field.next();
    
  }
  
});  */

function wpdreamsuploader(args)  {  
  this.constructor = function() {
    this.buttons = jQuery('.wpdreamsUpload_button');
    if (this.buttons.length==0) return;
    this.currentInput = null;
    this.init();
    jQuery('input.wpdreamsUpload').change(function() {
       var _parent = this;
       jQuery('img', this.parentNode).each(function(){
         jQuery(this).css("display", "inline");
         this.src = jQuery(_parent).val()+"?"+Math.random();
       });
    });
    jQuery("img[rel]").overlay();
  },
  
  this.init = function() {
    this.buttons.bind('click', jQuery.proxy(this.openTb, this));
    jQuery('input[name="default"]').click(function(){
      jQuery(this).prev().prev().val(jQuery(this).next().val());
      jQuery('input.wpdreamsUpload').trigger("change");
    });
    window.send_to_editor = jQuery.proxy(function(html) {
        imgurl = jQuery('img',html).attr('src');
        if (this.currentInput==null) return;
        this.currentInput.val(imgurl);
        tb_remove();
        jQuery('input.wpdreamsUpload').trigger("change");
    }, this);
  },
  
  this.openTb = function(e) {
   this.currentInput = jQuery(e.target).prev();
   tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true', false);
   return false;
  }
  this.constructor();
}



jQuery(document).ready(function() { 
  if (x==null)
    var x = new wpdreamsuploader();
}); 