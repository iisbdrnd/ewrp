<select required data-fv-icon="false" name="job_area" id="job_area" class="form-control">
    <option value="">Select Job Area</option>
    @foreach($adminJobAreaList as $area)
        <option value="{{$area->id}}">{{$area->area_name}}</option>
    @endforeach
</select>

<script type="text/javascript">
	$(document).ready(function(){
		var job_areas = <?php echo json_encode($adminJobAreaList); ?>;
        $('#job_area').on('change', function() {
            var job_area_id = $(this).val();
            var areaDetails = job_areas[job_area_id];
            if(job_area_id){
                $("#crm_area_details").find("#areaDe").val(areaDetails.area_details);
                $("#crm_area_details").show();
            }
        });
	});
</script>
