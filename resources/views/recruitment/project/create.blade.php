<style type="text/css">
  .modal-dialog-center
  {
    width: 95%;
    margin-left: -47% !important;
  }
</style>
<?php $panelTitle = "Project Create"; ?>
<form id="projectForm" type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
  {{csrf_field()}}

  <div class="col-lg-6 col-md-6" style="border-right: 3px solid #ccc">
    <div class="panel panel-default chart">
      <div class="panel-body" style="border: none">
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label required">Country Name</label>
          <div class="col-lg-9 col-md-8">
             {{--  <input autofocus required name="country_name" id="country_name" placeholder="Country Name" class="form-control"> --}}
            <select  required name="country_name" id="country_name" class="select2 form-control">
              <option>Choose Country</option>
              @foreach($countries as $country)
                <option value="{{ $country->id }}" countryName="{{ $country->iso3 }}">{{ $country->iso3 }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label required">Company Name</label>
          <div class="col-lg-9 col-md-8">
            <input autofocus required name="company_name" id="company_name" placeholder="Company Name" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label required">Month </label>
          <div class="col-lg-9 col-md-8">
            <select name="month" id="month" class="form-control select2">
              <option>Select Month</option>
              <?php for($m=1; $m<=12; ++$m){
                $month =  date('F', mktime(0, 0, 0, $m, 1));
                // strtoupper(mb_substr($month, 0, 3))
                ?>
                <option value="<?php  echo $result = strtoupper($month);?>"><?php  echo $result = strtoupper($month);?></option>
              <?php }?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label required">Year</label>
          <div class="col-lg-9 col-md-8">
            <?php $years = array_combine(range(date('Y'), date('Y')+10), range(date('Y'), date('Y')+10));
            // dd($years); 
            ?> 
            <select name="year" id="year" class="select2 form-control">
              <option>Select Year</option>
              @foreach($years as $year)
              <option value="{{ $year }}">{{ $year }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default chart">
      <div class="panel-body" style="border: none;">
        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label required">Project Name</label>
          <div class="col-lg-9 col-md-8">
            <textarea autofocus required name="project_name" id="project_name" placeholder="Project Name" class="form-control" id="" cols="30" rows="2"></textarea>
          </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label">Start Date</label>
            <div class="col-lg-9 col-md-9">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" id="start_date" name="start_date" class="form-control dateTimeFormat"
                  placeholder="e.g. 15-12-2019" autocomplete="off">
              </div>
            </div>
        </div>


        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label"></label>
          <div id="">
            <div class="">
              <div class="col-lg-3 col-md-3">
                  <p style="text-align:center"><b>Select Trade</b></p>
              </div>
              <div class="col-lg-2 col-md-2">
                    <p style="text-align:center"><b>Quantity</b></p>
              </div>
              <div class="col-lg-2 col-md-2">
                    <p style="text-align:center"><b>Salary</b></p>
              </div>
              <div class="col-lg-2 col-md-2">
                   <p style="text-align:center"><b>Others</b></p>
              </div>
              <div class="col-lg-1 col-md-1 pl0 pr0">

              </div>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>

        <div class="form-group" style="margin-top:-10px">
          <label class="col-lg-2 col-md-2 control-label required">Select Trades</label>
          <div id="trade_plus">
            <div class="trade_top">

              <div class="col-lg-3 col-md-3" id="trade_list">
                <select required name="trade_id[]" id="trade_id" data-fv-icon="false" class="select2 form-control ml0"> 
                  <option value=""></option>
                  @foreach($trades as $trade)
                  <option value="{{$trade->id}}">{{$trade->trade_name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-2">
                   <input type="text" required id="quantity" class="form-control" name="trade_qty[]" placeholder="qty">
                   <div class="qtyFeedback"></div>
              </div>
              <div class="col-lg-2 col-md-2">
                   <input type="text" required id="salary" class="form-control" name="trade_salary[]" placeholder="salary">
              </div>
              <div class="col-lg-2 col-md-2">
                   <input type="text" id="others" class="form-control" name="others[]" placeholder="others">
              </div>
              <div class="col-lg-1 col-md-1 pl0 pr0" id="first_row">
                <i class="fa fa-plus-square hand pub-plus"></i>
              </div>

            </div>
            <div class="clearfix"></div>
          </div>
        </div>


        <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label required">Required Qty.</label>
          <div class="col-lg-9 col-md-8">
            <input type="number" name="required_quantity" id="required_quantity" class="form-control" placeholder="e.g. 5">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6 col-md-6">
    {{-- agency --}}
    <div class="form-group">
      <label class="col-lg-1 col-md-1 control-label">Agency</label>
      <div id="agency_plus">
        <div class="agency_top">
          <div class="col-lg-3 col-md-3" id="agency_list">
            <select name="agency_id[]" id="agency_id" data-fv-icon="false" class="select2 form-control ml0"> 
              <option value=""></option>
              @foreach($agency as $agency)
              <option value="{{$agency->id}}">{{$agency->agency_name}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-2">
               <input type="text" id="akama" class="form-control" name="akama_no[]" placeholder="akama no">
          </div>
          <div class="col-lg-3 col-md-3">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" name="akama_rec_date[]" class="form-control dateTimeFormat"
                  placeholder="15-12-2019" autocomplete="off">
            </div>
          </div>
          <div class="col-lg-2 col-md-2">
               <input type="text" id="akama_quantity" class="form-control" name="akama_qty[]" placeholder="qty" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
               <div class="akamaqtyFeedback"></div>
          </div>
          <div class="col-lg-1 col-md-1 pl0 pr0" id="agency_first_row">
            <i class="fa fa-plus-square hand agency-pub-plus"></i>
          </div>
        </div>
      </div>
    </div>
    {{-- end agency --}}
  </div>
  
  {{-- agency clone --}}
  <div id="agency_plus_clone" style="display: none;">
    <div class="agency_top agency_top_firstRow">
      <div class="col-lg-offset-1 col-md-offset-1 col-lg-3 col-md-3" id="agency_list_view" style="margin-top: 1%">
        <select data-fv-icon="false" class="select2 form-control ml0"></select>
      </div>
      <div class="col-lg-2 col-md-2" style="margin-top: 1%">
          <input type="text" id="akama" class="form-control" name="akama_no[]" placeholder="akama no">
      </div>
      <div class="col-lg-3 col-md-3" style="margin-top: 1%">
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
          <input type="text" name="akama_rec_date[]" class="form-control dateTimeFormat"
                  placeholder="15-12-2019" autocomplete="off">
        </div>
      </div>
      <div class="col-lg-2 col-md-2" style="margin-top: 1%">
        <input type="text" id="akama_quantity" class="form-control" name="akama_qty[]" placeholder="qty" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
        <div class="akamaqtyFeedback"></div>
      </div>
      <div class="col-lg-1 col-md-1 agency-pub-plus-minus" style="margin-top:1%">
          <i class="fa fa-minus-square hand agency-pub-minus"></i>
          <i class="fa fa-plus-square hand agency-pub-plus"></i>
      </div>
    </div>
  </div>
  {{-- end agency clone --}}
  
  {{-- trade clone --}}
  <div id="trade_plus_clone" style="display: none;">
    <div class="trade_top trade_top_firstRow">
      <div class="col-lg-3 col-md-3 col-lg-offset-2 col-md-offset-2" id="trade_list_view" style="margin-top: 1%">
        <select data-fv-icon="false" class="select2 form-control ml0">
          
        </select>
      </div>
      <div class="col-lg-2 col-md-2" style="margin-top: 1%">
           <input required type="text" class="form-control" id="quantity" name="trade_qty[]" placeholder="quantity">
           <div class="qtyFeedback"></div>
      </div>
      <div class="col-lg-2 col-md-2" style="margin-top: 1%">
          <input type="text" required id="salary" class="form-control" name="trade_salary[]" placeholder="salary">
      </div>
      <div class="col-lg-2 col-md-2" style="margin-top: 1%">
            <input type="text" id="others" class="form-control" name="others[]" placeholder="others">
      </div>
      <div class="col-lg-1 col-md-1 pub-plus-minus" style="margin-top:1%">
          <i class="fa fa-minus-square hand pub-minus"></i>
          <i class="fa fa-plus-square hand pub-plus"></i>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
  {{-- end trade clone --}}

</form>


<script type="text/javascript">

$('#country_name').on('change',function(){
  getProjectTitle();
});

$('#company_name').on('keyup',function(){
  getProjectTitle();
});

/*$('.dateTimeFormat').datepicker({
      format: "dd-mm-yyyy"
});*/

$('.dateTimeFormat').each(function(){
    $(this).datepicker({format: "dd-mm-yyyy"});
});

$('#month').on('change',function(){
  getProjectTitle();
});

$('#year').on('change',function(){
  getProjectTitle();
});

function getProjectTitle(){
  var country_name       = $('option:selected', '#country_name').attr('countryName');
  var countryName        = country_name

  var company_name = $('#company_name').val().toUpperCase();
  var month        = $('#month').val();
  var year         = $('#year').val();
  var projectTitle = countryName+"."+company_name+"."+month+"."+year;
  $('#project_name').val(projectTitle);
}


$(document).ready(function() {
    $(".select2").select2({
      placeholder: "Select"
    });

    $('#trade_plus').on('click', '.pub-plus', function(){
       tradeTrAdd('trade_plus');
    });

    $('#trade_plus').on('click', '.pub-minus', function(){
        tradeTrRemove('trade_plus', $(this));
    });

    $('#agency_plus').on('click', '.agency-pub-plus', function(){
       agencyTrAdd('agency_plus');
    });

    $('#agency_plus').on('click', '.agency-pub-minus', function(){
        agencyTrRemove('agency_plus', $(this));
    });

});

/*---------------------------------
  DATE PICKER FOR ALL CALENDAR
-----------------------------------*/
$('.datePicker').datepicker({
  format: "dd-mm-yyyy",
});


 /*start trade tr*/

  function tradeTrAdd(selector)
  {
    var sn = parseInt($('#'+selector).find('.td-sn').last().html())+1;
    $('#'+selector).append($('#'+selector+'_clone').html());
    var $lastChild = $('#'+selector).find('.trade_top_firstRow').last();
    $('#'+selector).find('.pub-plus').not($('#'+selector+' .pub-plus').last()).remove();
    
    var length = $('#trade_plus .trade_top').length;
    var trade_get_id = new Array();

    $('#trade_plus .trade_top').each(function(index)
    {
      var $parentTr = $(this).first();
      trade_get_id[index] = $parentTr.find("#trade_id").val();

      if (index == (length - 1)) 
      {
          $.ajax({        
              type: 'GET',
              dataType: 'html',
              url: appUrl.baseUrl('/recruitment/get-trade-filter'),
              data: {trade_get_id:trade_get_id},
              success: function(response) 
              {
                  $parentTr.find("#trade_list_view").html(response);
                  $parentTr.find("#trade_id").select2({placeholder:"Select Trade"});

              }
          });
      }
          
    });

  }

  function tradeTrRemove(selector, $that)
  {
    var $row = $that.parents('.trade_top_firstRow').remove();
    $row.remove();
    if($('#'+selector+' .pub-plus-minus').length==1) {
        $('#'+selector+' .pub-plus-minus').html('<i class="fa fa-minus-square hand pub-minus"></i> <i class="fa fa-plus-square hand pub-plus"></i>');
    } else if($('#'+selector+' .pub-plus-minus').length > 1) {
    $('#'+selector+' .pub-plus-minus').last().html('<i class="fa fa-minus-square hand pub-minus"></i> <i class="fa fa-plus-square hand pub-plus"></i>');
    }
    else
    {
        $('#first_row').html('<i class="fa fa-plus-square hand pub-plus"></i>');
    }

  }

  $('#projectForm').on("keyup", "#quantity", function () 
  {
    var $parentTr = $(this).parents('.trade_top').first();
    var elmQty = $(this).val();
    if(Number(elmQty) > Number(50))
    {
        $(".modal-footer .btn-success").prop('disabled', true);
        $('.pub-plus').hide();
        $parentTr.find('.qtyFeedback').html('<strong class="text-danger">out of 50!</strong>');
    } 
    else 
    {
        $('.pub-plus').show();
        $parentTr.find('.qtyFeedback').html("");
        $(".modal-footer .btn-success").prop('disabled', false);
    }

  });

 /* end trade tr*/

/* start agency tr*/
function agencyTrAdd(selector)
{
  $('#'+selector).append($('#'+selector+'_clone').html());
  var $lastChild = $('#'+selector).find('.agency_top_firstRow').last();
  $('#'+selector).find('.agency-pub-plus').not($('#'+selector+' .agency-pub-plus').last()).remove();
  
  var length = $('#agency_plus .agency_top').length;
  var agency_get_id = new Array();

  $('#agency_plus .agency_top').each(function(index)
  {
    var $parentTr = $(this).first();
    agency_get_id[index] = $parentTr.find("#agency_id").val();

    if (index == (length - 1)) 
    {
      $.ajax({        
          type: 'GET',
          dataType: 'html',
          url: appUrl.baseUrl('/recruitment/get-agency-filter'),
          data: {agency_get_id:agency_get_id},
          success: function(response) 
          {
              $parentTr.find("#agency_list_view").html(response);
              $parentTr.find("#agency_id").select2({placeholder:"Select Agency"});
              $parentTr.find(".dateTimeFormat").datepicker({format: "dd-mm-yyyy"});

          }
      });
    }
        
  });

}

function agencyTrRemove(selector, $that)
{

  var value =$that.parents('.agency_top_firstRow').find('#agency_id option:selected').val();
  var text = $that.parents('.agency_top_firstRow').find('#agency_id option:selected').text();

  $('#agency_plus .agency_top').each(function(index)
  {
    if(value != '')
    {
        var optionExists = ($(this).find("#agency_id option[value="+value+"]").length > 0);
        if(!optionExists)
        {
            $(this).find('#agency_id').append($('<option>').val(value).text(text));
        }
    }

  });


  var $row = $that.parents('.agency_top_firstRow').remove();
  $row.remove();
  if($('#'+selector+' .agency-pub-plus-minus').length==1) {
      $('#'+selector+' .agency-pub-plus-minus').html('<i class="fa fa-minus-square hand agency-pub-minus"></i> <i class="fa fa-plus-square hand agency-pub-plus"></i>');
  } else if($('#'+selector+' .agency-pub-plus-minus').length > 1) {
  $('#'+selector+' .agency-pub-plus-minus').last().html('<i class="fa fa-minus-square hand agency-pub-minus"></i> <i class="fa fa-plus-square hand agency-pub-plus"></i>');
  }
  else
  {
      $('#agency_first_row').html('<i class="fa fa-plus-square hand agency-pub-plus"></i>');
  }

}
/*end agency tr*/

</script>