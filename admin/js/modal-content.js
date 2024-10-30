jQuery.fn.exists = function() {
	return jQuery(this).length > 0;
}
jQuery(document).ready(function($) {


	// Tells any anchor with only a hash url to do nothing.
	$("a[href='#']" ).on('click', function( e ){ e.preventDefault(); });
	// Tells both the OK and Cancel buttons to close the modal. You'll want to make these your own.
	$('#btn-ok, #btn-cancel' ).on( 'click' , parent.aut0poietic_iframe_modal_close_handler );


	if ($(".plupload-upload-uic").exists()) {

		var pconfig = false;
		$(".plupload-upload-uic").each(function() {

			var $this = $(this);
			var id1 = $this.attr("id");
			var fileId = id1.replace("plupload-upload-ui", "");

			pconfig = $.parseJSON(cbxform_modal_content.base_plupload_config);

			pconfig["browse_button"] = fileId + pconfig["browse_button"];
			pconfig["container"] = fileId + pconfig["container"];
			pconfig["drop_element"] = pconfig["drop_element"];
			pconfig["file_data_name"] = fileId + pconfig["file_data_name"];
			pconfig["multipart_params"]["imgid"] = fileId;
			pconfig["multipart_params"]["_ajax_nonce"] = $this.find(".ajaxnonceplu").attr("id").replace("ajaxnonceplu", "");

			if ($this.hasClass("plupload-upload-uic-multiple")) {
				pconfig["multi_selection"] = true;
			}


			var uploader = new plupload.Uploader(pconfig);

			uploader.bind('Init', function(up,params) {
				if (uploader.features.dragdrop) {
					$('debug').innerHTML = "";

					var target = $("drop-target");
					target.ondragover = function(event) {
						event.dataTransfer.dropEffect = "copy";
					};
					target.ondragenter = function() {
						this.className = "dragover";
					};
					target.ondragleave = function() {
						this.className = "";
					};
					target.ondrop = function() {
						this.className = "";
					};
				}
			});

			uploader.init();

			// a file was added in the queue
			uploader.bind('FilesAdded', function(up, files) {

				up.refresh();
				up.start();
			});

			// upload process
			/*uploader.bind('UploadProgress', function(up, file) {
				$('#' + file.id + " .fileprogress").width(file.percent + "%");
				$('#' + file.id + " span").html(plupload.formatSize(parseInt(file.size * file.percent / 100)));
			});*/

			// a file was uploaded
			uploader.bind('FileUploaded', function(up, file, response) {
				response = $.parseJSON(response["response"]);

				console.log(response.fail);
				if(response.success != null){
					$('.cbx-success').text(response.success).show();
					$('.cbx-failure').hide();
				}else if(response.fail != null){
					$('.cbx-failure').text(response.fail).show();
					$('.cbx-success').hide();
				}
			});
		});
	}
});
