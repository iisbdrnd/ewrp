<style type="text/css">
  .modal-dialog-center
  {
    width: 95%;
    margin-left: -47% !important;
  }
  .custom_form_group
  {
    margin-left: -10px;
    margin-right: -10px;
    margin-bottom: 10px;
  }
</style>
<?php $panelTitle = "Project Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal stripped" id="project_update_form">
    {{csrf_field()}}

    <div class="col-lg-6 col-md-6" style="border-right: 3px solid #ccc">
      <div class="custom_form_group">
        <label class="col-lg-2 col-md-2 control-label required">Project Name</label>
        <input type="hidden" name="country_name" id="country_name">
        <div class="col-lg-9 col-md-9">
            <input required autofocus name="project_name" id="project_name" placeholder="Project Name" class="form-control" value="{{$project->project_name}}">
        </div>
      </div>

      <div class="clearfix"></div>

    <div class="form-group" style="margin-top:2%;">
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

      <div class="custom_form_group" style="margin-top: -2%;">
          <label class="col-lg-2 col-md-2 control-label required label_trade">Select Trades</label>
          <div id="trade_plus">
              @foreach($selected_trades as $key => $s_trade)
                  <div class="trade_top trade_top_firstRow" style="margin-top: 1%">
                    <div class="col-lg-3 col-md-3" id="trade_list">
                      <select required name="trade_id[]" id="trade_id" data-fv-icon="false" class="select2 form-control ml0"> 
                        <option value=""></option>
                        @foreach($trades as $trade)
                        <option value="{{$trade->id}}" @if($s_trade->trade_id==$trade->id){{'selected'}}@endif>{{$trade->trade_name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-lg-2 col-md-2">
                      <div class="input-group">
                         <input type="number" required class="form-control" id="quantity" placeholder="qty" name="trade_qty[]" value="{{ $s_trade->trade_qty }}">
                         <div class="qtyFeedback"></div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                      <div class="input-group">
                         <input type="number" required class="form-control" id="salary" placeholder="salary" name="trade_salary[]" value="{{ $s_trade->trade_salary }}">
                      </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                      <div class="input-group">
                         <input type="text" class="form-control" id="others" placeholder="others" name="others[]" value="{{ $s_trade->trade_others }}">
                      </div>
                    </div>
                    <div class="col-lg-1 col-md-1 first_row_icon first_row"></div>
             
                  </div>
                  <div class="clearfix"></div>
              @endforeach
          </div>
      </div>

      <?php
         $get_date = $project->start_date;
         if($get_date == "0000-00-00")
         {
            $date_value= date('d-m-Y', strtotime($project->created_at));
         }
         else
         {
            $date_value= date('d-m-Y', strtotime($project->start_date));
         }
      ?>
      <div class="clearfix"></div>

      <div class="form-group">
          <label class="col-lg-2 col-md-2 control-label">Start Date</label>
          <div class="col-lg-9 col-md-9">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" id="start_date" name="start_date" class="form-control dateTimeFormat"
                placeholder="e.g. 15-12-2019" autocomplete="off" value="{{$date_value}}">
            </div>
          </div>
      </div>
      <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Required Qty.</label>
        <div class="col-lg-9 col-md-8">
          <input type="number" name="required_quantity" id="required_quantity" class="form-control" value="{{$project->required_quantity}}">
        </div>
      </div>
    </div>
    
    {{-- agency --}}
    <div class="col-lg-6 col-md-6">
      <div class="custom_form_group">
        <label class="col-lg-1 col-md-1 control-label">Agency</label>
        <div id="agency_plus">
          @if(count($selected_agency) > 0)
            @foreach($selected_agency as $key => $s_agency)
              <div class="agency_top agency_top_firstRow" style="margin-top: 1%">
                <div class="col-lg-3 col-md-3" id="agency_list">
                  <select name="agency_id[]" id="agency_id" data-fv-icon="false" class="select2 form-control ml0"> 
                    <option value=""></option>
                    @foreach($agency_info as $agency)
                      <option value="{{$agency->id}}" @if($s_agency->agency_id==$agency->id){{'selected'}}@endif>{{$agency->agency_name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-2 col-md-2">
                     <input type="text" id="akama" class="form-control" name="akama_no[]" placeholder="akama no" value="{{$s_agency->akama_no}}">
                </div>
                <?php
                   $agency_get_date = $s_agency->akama_rec_date;
                   if($agency_get_date == "0000-00-00")
                   {
                      $agency_date_value= date('d-m-Y', strtotime($project->created_at));
                   }
                   else
                   {
                      $agency_date_value= date('d-m-Y', strtotime($agency_get_date));
                   }
                ?>
                <div class="col-lg-3 col-md-3">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="akama_rec_date[]" class="form-control dateTimeFormat" id="akama_date"
                        placeholder="e.g. 15-12-2019" autocomplete="off" value="{{$agency_date_value}}">
                  </div>
                </div>
                <div class="col-lg-2 col-md-2">
                     <input id="akama_quantity" class="form-control" name="akama_qty[]" placeholder="qty" value="{{$s_agency->quantity}}" type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                </div>
                <div class="col-lg-1 col-md-1 agency_first_row_icon agency_first_row"></div>
              </div>
              <div class="clearfix"></div>
            @endforeach
          @else
            <div class="agency_top agency_top_firstRow" style="margin-top: 1%">
                <div class="col-lg-3 col-md-3" id="agency_list">
                  <select name="agency_id[]" id="agency_id" data-fv-icon="false" class="select2 form-control ml0"> 
                    <option value=""></option>
                    @foreach($agency_info as $agency)
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
                        placeholder="e.g. 15-12-2019" autocomplete="off">
                  </div>
                </div>
                <div class="col-lg-2 col-md-2">
                     <input type="text" id="akama_quantity" class="form-control" name="akama_qty[]" placeholder="qty">
                </div>
                <div class="agency_first_row_icon agency_first_row"></div>
            </div>
          @endif
        </div>
      </div>
    </div>
    {{-- end agency --}}

    {{-- for trade clone --}}
    <div id="trade_plus_clone" style="display: none">
        <div class="trade_top trade_top_firstRow">
          <div class="col-lg-3 col-md-3 col-lg-offset-2 col-md-offset-2" id="trade_list_view" style="margin-top: 1%; margin-bottom:1%">
            <select data-fv-icon="false" class="select2 form-control ml0" id="trade_id">
                <option value=""></option>
                @foreach($trades as $trade)
                <option value="{{$trade->id}}">{{$trade->trade_name}}</option>
                @endforeach
            </select>
          </div>
          <div class="col-lg-2 col-md-2" style="margin-top: 1%">
            <div class="input-group">
               <input type="number" required class="form-control" id="quantity" name="trade_qty[]" placeholder="quantity">
               <div class="qtyFeedback"></div>
            </div>
          </div>
          <div class="col-lg-2 col-md-2" style="margin-top: 1%">
            <div class="input-group">
                <input type="number" required class="form-control" id="salary" name="trade_salary[]" placeholder="salary">
            </div>
          </div>
          <div class="col-lg-2 col-md-2" style="margin-top: 1%">
            <div class="input-group">
                <input type="text" class="form-control" id="others" name="others[]" placeholder="others">
            </div>
          </div>
          <div class="col-lg-1 col-md-1 first_row_icon" style="margin-top:1%">
              <i class="fa fa-minus-square hand pub-minus"></i>
              <i class="fa fa-plus-square hand pub-plus clone_plus"></i>
          </div>
        </div>
        <div class="clearfix"></div>
    </div>
    {{-- end trade clone --}}

    {{-- start agency clone --}}
    <div id="agency_plus_clone" style="display: none;">
      <div class="agency_top agency_top_firstRow">
        <div class="col-lg-offset-1 col-md-offset-1 col-lg-3 col-md-3" id="agency_list_view" style="margin-top: 1%">
          <select data-fv-icon="false" class="select2 form-control ml0">
              <option value=""></option>
              @foreach($agency_info as $agency)
              <option value="{{$agency->id}}">{{$agency->agency_name}}</option>
              @endforeach
          </select>
        </div>
        <div class="col-lg-2 col-md-2" style="margin-top: 1%">
            <input type="text" id="akama" class="form-control" name="akama_no[]" placeholder="akama no">
        </div>
        <div class="col-lg-3 col-md-3" style="margin-top: 1%">
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" name="akama_rec_date[]" class="form-control dateTimeFormatclone"
                    placeholder="15-12-2019" autocomplete="off" id="akama_date">
          </div>
        </div>
        <div class="col-lg-2 col-md-2" style="margin-top: 1%">
          <input type="text" id="akama_quantity" class="form-control" name="akama_qty[]" placeholder="qty" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
          <div class="akamaqtyFeedback"></div>
        </div>
        <div class="col-lg-1 col-md-1 agency_first_row_icon" style="margin-top:1%">
            <i class="fa fa-minus-square hand agency-pub-minus"></i>
            <i class="fa fa-plus-square hand agency-pub-plus"></i>
        </div>
      </div>
    </div>
    {{-- end agency clone --}}

</form>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });

        //trade

        var counter = $('.trade_top').length-1; // 1,2..
        $('.trade_top').each(function(index)
        {
          var parent = $(this).first();
          if(index != 0)
          {
              $(this).find('#trade_list').addClass('col-md-offset-2 col-lg-offset-2');
          }
          if(index == 0 && counter == 1)
          {
             $(this).find('.first_row').html('<i class="fa fa-plus-square hand pub-plus"></i>');
          }
          else if(index == counter-1)  //last
          {
             $(this).find('.first_row').html('<i class="fa fa-minus-square hand pub-minus"></i>&nbsp;<i class="fa fa-plus-square hand pub-plus"></i>');
          }
          else if(index == 0)
          {
            $(this).find('.first_row').html('');
          }
          else
          {
              $(this).find('.first_row').html('<i class="fa fa-minus-square hand pub-minus each_minus"></i>');
          }

        });

        /*end trade*/

        /*agency*/
        var agency_counter = $('.agency_top').length-1; // 1,2..
        $('.agency_top').each(function(index)
        {
          var parent = $(this).first();
          if(index != 0)
          {
              $(this).find('#agency_list').addClass('col-lg-offset-1 col-md-offset-1');
          }
          if(index == 0 && agency_counter == 1)
          {
             $(this).find('.agency_first_row').html('<i class="fa fa-plus-square hand agency-pub-plus"></i>');
          }
          else if(index == agency_counter-1)  //last
          {
             $(this).find('.agency_first_row').html('<i class="fa fa-minus-square hand agency-pub-minus"></i>&nbsp;<i class="fa fa-plus-square hand agency-pub-plus"></i>');

             $(this).find('#agency_list').addClass('m-bottom-0');
          }
          else if(index == 0)
          {
            $(this).find('.agency_first_row').html('');
          }
          else
          {
              $(this).find('.agency_first_row').html('<i class="fa fa-minus-square hand agency-pub-minus agency_each_minus"></i>');
          }

        });
        /*end agency*/

    });

    $('#project_update_form').on('click', '.pub-plus', function() {
       tradeTrAdd('trade_plus');
    });

    $('#project_update_form').on('click', '.pub-minus', function() {
       tradeTrRemove('trade_plus', $(this));
    });

    $('#project_update_form').on('click', '.agency-pub-plus', function() {
       agencyTrAdd('agency_plus');
    });

    $('#project_update_form').on('click', '.agency-pub-minus', function() {
       agencyTrRemove('agency_plus', $(this));
    });

    $('.dateTimeFormat').datepicker({
      format: "dd-mm-yyyy"
    });
    /*----------------------------------------------------------------------------------------
    Country ID updating for 
    QATAR & UNITED ARAB EMIRATES 
    Condition: if we get QATAR in project name then push a country_id value in 
    country_name hidden input box and if we get UNITED ARAB EMIRATES then pushed country_id value 
    -----------------------------------------------------------------------------------------*/
    var str = $('#project_name').val();
    if(!str.search("QATAR") || !str.search("QTR")){
       $("#country_name").val(180);
    }else if(!str.search("KINGDOM OF SAUDI ARABIA") || !str.search("KSA")){
       $("#country_name").val(185);
    } else if (!str.search("CHINA")){
        $("#country_name").val(46);
    }


  /*trade*/
  function tradeTrAdd(selector)
  {
    $('#'+selector).append($('#'+selector+'_clone').html());
    var $lastChild = $('#'+selector).find('.trade_top').last();
    $lastChild.find(".each_minus").addClass('hidden');
    //FV        
    $('#'+selector).find('.pub-plus').not($('#'+selector+' .pub-plus').last()).remove();
    showTradeList();
  }

  function tradeTrRemove(selector, $that)
  {
    var $row = $that.parents('.trade_top_firstRow').remove();
    $row.remove();
    var parent = $(this).first();
    var trade_length = $('#trade_plus .trade_top').length;

    $('#trade_plus .trade_top').each(function(index)
    {
        if(index == trade_length-1)
        {
           $(this).find('.first_row_icon').html('<div class="appen_pub check"><i class="fa fa-minus-square hand pub-minus each_minus"></i>&nbsp;<i class="fa fa-plus-square hand pub-plus"></i></div>');
        }

        if(index == 0 && trade_length == 1)
        {
          $(this).find('.first_row_icon').html('<div class="appen_pub check"><i class="fa fa-plus-square hand pub-plus"></i></div>');
        }

    })

  }

  $('#project_update_form').on("keyup", "#quantity", function () 
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

  function showTradeList()
  {
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
  /*end trade*/


  /*agency*/
  function agencyTrAdd(selector)
  {
    $('#'+selector).append($('#'+selector+'_clone').html());
    var $lastChild = $('#'+selector).find('.agency_top').last();
    $lastChild.find(".agency_each_minus").addClass('hidden');
    //FV        
    $('#'+selector).find('.agency-pub-plus').not($('#'+selector+' .agency-pub-plus').last()).remove();
    showagencyList();
  }

  function showagencyList()
  {
    var length = $('#agency_plus .agency_top').length;
    var agency_get_id = new Array();

    $('#agency_plus .agency_top').each(function(index)
    {
      var parent = $(this).first();
      agency_get_id[index] = parent.find("#agency_id").val();
      if (index == (length - 1)) 
      {
        $.ajax({        
            type: 'GET',
            dataType: 'html',
            url: appUrl.baseUrl('/recruitment/get-agency-filter'),
            data: {agency_get_id:agency_get_id},
            success: function(response) 
            {
                parent.find("#agency_list_view").html(response);
                parent.find("#agency_id").select2({placeholder:"Select Agency"});
                parent.find(".dateTimeFormatclone").datepicker({format: "dd-mm-yyyy"});

            }
        });
      }

    });
  }

  function agencyTrRemove(selector, $that)
  {
    var $row = $that.parents('.agency_top_firstRow').remove();
    $row.remove();
    var parent = $(this).first();
    var agency_length = $('#agency_plus .agency_top').length;

    $('#agency_plus .agency_top').each(function(index)
    {
        if(index == agency_length-1)
        {
           $(this).find('.agency_first_row_icon').html('<div class="appen_pub check"><i class="fa fa-minus-square hand agency-pub-minus agency_each_minus"></i>&nbsp;<i class="fa fa-plus-square hand agency-pub-plus"></i></div>');
        }

        if(index == 0 && agency_length == 1)
        {
          $(this).find('.agency_first_row_icon').html('<div class="appen_pub check"><i class="fa fa-plus-square hand agency-pub-plus"></i></div>');
        }

    });

  }
</script>