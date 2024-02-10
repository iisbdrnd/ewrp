<div class="tabs mb20">
    <ul id=myTab2 class="nav nav-tabs nav-justified">
        <li class="active"><a href="#modules" data-toggle="tab">Modules</a></li>
        <li><a href="#charts" data-toggle="tab">Charts</a></li>
    </ul>

    <div id="myTabContent2" class="tab-content">
        <div class="tab-pane fade active in" id="modules">
            <div class="row">
                <div class="col-sm-12">
                    <div class="reminder mb25">
                        <h4>Modules</h4>
                        <ul>
                            @foreach($modulePanelList as $panel)
                                <li class="clearfix">
                                    <div class="icon"><span class="s32 {{$panel->icon}} color-dark"></span></div>
                                    <span class="txt mb10 ml15">{{$panel->panel_name}}</span> <button sort-id="{{$panel->id}}" sort-position="{{$panel->position}}" class="btn btn-warning panelAdd" @if(in_array($panel->id, $userPanels)){{"disabled"}}@endif>Add</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade fade in" id="charts">
            <div class="row">
                <div class="col-sm-12">
                    <div class="reminder mb25">
                        <h4>Charts</h4>
                        <ul>
                            @foreach($chartPanelList as $panel)
                                <li class="clearfix">
                                    <div class="icon"><span class="s32 {{$panel->icon}} color-dark"></span></div>
                                    <span class="txt mb10 ml15">{{$panel->panel_name}}</span> <button sort-id="{{$panel->id}}" sort-position="{{$panel->position}}" class="btn btn-warning panelAdd" @if(in_array($panel->id, $userPanels)){{"disabled"}}@endif>Add</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>