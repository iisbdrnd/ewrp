<select required id="reference_id" name="reference_id" data-fv-icon="false" class="select2 form-control ml0 ffff">
    <option value=0>All Reference</option>
    @foreach($references as $reference)
    <option value="{{$reference->id}}">{{$reference->reference_name}}</option>
    @endforeach
</select>
<script type="text/javascript">
    $(document).ready(function() {
        $("#reference_id").select2({
            placeholder: "Select"
        });
        // $('#reference_id').attr('required', true);
        $('#amountReceiveForm').formValidation('addField', $('#reference_id'));
    });
</script>
