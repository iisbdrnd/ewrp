<div class="row files">
<div class="col-md-12 text-center">
	<button id="attachment_upload" file-path="public/uploads/opportunity_files" _token="SLyCXUJ3XXKYTcL0mkLQAoloNSa9VTjsKTBzTQbU" auto-remove="false" callback="save_opportunities_file" callback-related_to="3" callback-related_item="28" type="button" data-loading-text="Uploading..." class="btn btn-info btn-block   "> Attach Files</button>
	<div id="status_attachment_upload" style="padding-top: 10px">
	</div>
	<hr class="mt0 mb10">
</div>
<div class="col-md-12 text-center">
	<div id="attachment_area_attachment_upload" class="attachment_area">
		<div class="attachments-box no-attachments">No attachments</div>
	</div>
</div>
</div>

<script type="text/javascript">
	multipleFileUpload("attachment_upload");
	activitiesFileUnlink("attachment_upload", "crm/crmFile/delete");
	
	function save_opportunities_file(callback_param) {
		//console.log(callback_param);
		callback_param._token = "SLyCXUJ3XXKYTcL0mkLQAoloNSa9VTjsKTBzTQbU";
		$.ajax({
			url: appUrl.getSiteAction('/crmFile/save'),
			type: "POST",
			dataType: "json",
			data: callback_param,
			success: function(data) {
				var file_id = data.file_id;
				var file_name = data.file_name;
				var file_name_array = file_name.split('.');
				$("#attachment_area_attachment_upload #"+file_name_array[0]).find(".remove_files").attr("file_id", file_id);
			}
		});
	}

	$(document).ready(function () {
        var viewer = ImageViewer();
        $('.attachment_area').on('click', 'a.igniterImg', function (e) {
            e.preventDefault();
            var imgSrc = 'http://server/apps'+'/public/uploads/opportunity_files/thumb/'+$(this).parents('.attachment-content').find('input').val(),
                highResolutionImage = $(this).attr('href');

            viewer.show(imgSrc, highResolutionImage);
        });
    });	
</script>