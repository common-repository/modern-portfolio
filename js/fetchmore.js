jQuery(document).ready(function() {

    jQuery('#modport_loading').hide();

    jQuery('#modport_load').on('click', function(e) {
    e.preventDefault();
    jQuery('#modport_load').hide();
    jQuery('#modport_loading').show();
    var count=jQuery('.modcolumn').length;
	jQuery.ajax({
		type: "POST",                 // use $_POST request to submit data
		url: modport_ajax_url.ajax_url,      // URL to "wp-admin/admin-ajax.php"
		data: {
			action     : 'modportfolio', // wp_ajax_*, wp_ajax_nopriv_*
            security : modport_ajax_url.check_nonce,
			current_count : count,
            load_type : jQuery('#modport_load').data('type'),
            load_count : jQuery('#modport_load').data('count'),
            load_orderby : jQuery('#modport_load').data('orderby'),
            load_sort : jQuery('#modport_load').data('sort'),
            load_attribs : jQuery('#modport_load').data('attribs'),
            load_cats : jQuery('#modport_load').data('cats'),
            load_authors : jQuery('#modport_load').data('authors'),
            load_tags : jQuery('#modport_load').data('tags')
		},
		success:function( data ) {
		  jQuery('.moditems').fadeOut(1).html( data ).fadeIn(500); //
          jQuery('#modportBtnContainer .showall').trigger('click');
          jQuery('#modport_loading').hide();
          jQuery('#modport_load').show();
          var newcount=jQuery('.modcolumn').length;
          if(newcount-count == 0)jQuery('#modport_load').fadeOut(500);
		},
		error: function(){
			console.log(errorThrown); // error
		}
	});

    });

});