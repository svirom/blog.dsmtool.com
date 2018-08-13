/* jshint asi: true */
jQuery(document).ready(function($){
	console.log ('pixel cat hoooo') //https://www.youtube.com/watch?v=u6gm_V-5VGA
	
	$('.fca_pc_multiselect').select2()
	$('#fca-pc-helptext').tooltipster( {trigger: 'custom', timer: 6000, maxWidth: 350, theme: ['tooltipster-borderless', 'tooltipster-pixel-cat'] } )
	$('#fca_pc_main_form').show()
	
	$('.fca-pc-id').on('input', function(e){
		var value = $(this).val()
		if ( !(/^\d+$/.test(value)) && value !== '' ) {
			$(this).val('')
			$('#fca-pc-helptext').tooltipster('open')
		}
	})
	
	if ( $('.fca-pc-id').val() !== '' ) {
		$('#fca-pc-setup-notice').hide()
	}
	
})
