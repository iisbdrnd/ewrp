<?php $panelTitle = "Project Configuration"; ?>
<div class="row">
    <div class="col-lg-12 col-md-12 sortable-layout ui-sortable">
      <div class="panel panel-default chart">
        <div class="panel-body pt0 pb0">
          <div class="simple-chart">
            <div class="row mt10">
              <div class="col-sm-12">
                <div class="lead-details pb0 mb10">
                  <ul>
                    <li><strong>
                        <!-- Project Name: --></strong> <a class="ajax-popover ajax-link hand" href="accounts/61" menu-active="accounts" data-title="" load-popover="1" data-original-title="" title=""></a></li>
                    <li><strong>Project Name: </strong> <span class="opportunity-closed-date">{{ @Helper::projects($project_id)->project_name }}</span></li>
                    <li><strong>Status:</strong> <span class="opportunity-amount"><span class="{{ @Helper::projects($project_id)->status == 1?'text-success':(@Helper::projects($project_id)->status == 2?'text-danger':'') }}">{{ @Helper::projects($project_id)->status == 1?'Running':(@Helper::projects($project_id)->status == 2?'Close':'') }}</span>
                      </span></li>
                    <li class="pull-right"> <a href="{{ url('recruitment#project-registration') }}" class="ajax-link"><i class="fa fa-arrow-left"></i> Back</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="row mt20">
    <div class="dhe-example-section-content">
        <div id="example-1-2">
            <div class="column left first col-lg-3 col-lg-offset-3">
                <h4 class="text-center text-info">Default Mobilize List</h4><hr>
                <ul class="sortable-list" id="defaultItem">
                    <?php $i=0; ?>
                    @foreach($mobilizations as $mobilization)
                       <?php $i++ ?>
                        <li class="sortable-item">
                            <input type="hidden" name="mobilization_id[]" value="{{ $mobilization->id }}">
                            {{ $mobilization->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="column left col-lg-3">
                <h4 class="text-center text-info">Configured Mobilize List</h4><hr>
                <form  type="configure" id="form-data" panelTitle="{{$panelTitle}}" class="form-load">
                    {{csrf_field()}}
                    <ul class="sortable-list" id="configureItem"> 
                      <input type="hidden" name="project_id" value="{{ $project_id }}">
                      @if(!empty($configurations))
                        @foreach($configurations as $configure)
                            @foreach( Helper::mobilization($configure) as $mobilization)
                                <li class="sortable-item">
                                    <input type="hidden" name="mobilization_id[]" value="{{ $mobilization->id }}">
                                    {{ $mobilization->name }}
                                </li> 
                            @endforeach
                        @endforeach
                        @endif
                    </ul>
                    <div class="panel-footer col-lg-12">
                        <button type="submit" id="save-button" class="btn btn-success btn-md pull-right form-control">Save</button>
                    </div>
                </form>   
            </div>
            <div class="clearer">&nbsp;</div>
        </div>
        <!-- END: XHTML for example 1.2 -->
    </div>
</div>
<br><br>
<script type="text/javascript">
    $(document).ready(function() {
        // Example 1.2: Sortable and connectable lists
        $('#example-1-2 .sortable-list').sortable({
            connectWith: '#example-1-2 .sortable-list'
        });
    });

    $('#save-button').on('click',function(){

    });
</script>

