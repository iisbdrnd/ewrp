<!-- Start .row -->
<div class=row>
    <div class="col-lg-12">
        <!-- col-lg-12 start here -->
        <div load-url="{{$loadUrl}}" @if(!empty($dataTableId))id="{{$dataTableId}}"@endif @if(!empty($dataPrefix))data-prefix="{{$dataPrefix}}"@endif @if(!empty($urlParameter))url-parameter="{{$urlParameter}}"@endif @if(!empty($updateLink))update-link="{{$updateLink}}"@endif @if(!empty($updateBack))update-back="{{$updateBack}}"@endif @if(!empty($deleteLink))delete-link="{{$deleteLink}}"@endif @if(!empty($refreshUrl))refresh-url="{{$refreshUrl}}"@endif @if(!empty($refreshCallBack))refresh-callback="{{$refreshCallBack}}"@endif class="data-table panel panel-default @if(@$panelMove===false){{''}}@else{{'panelMove'}}@endif showControls toggle panelClose @if(@$panelRefresh===false){{''}}@else{{'panelRefresh'}}@endif @if(!empty($class)){{$class}}@endif" @if(!empty($attr)) @foreach($attr as $atk=>$atv){{$atk.'='.$atv.' '}}@endforeach @endif>
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title">{{$tableTitle}}</h4>
            </div>
            {{csrf_field()}}
            <div class="panel-body data-list"></div>
        </div>
        <!-- End .panel -->
    </div>
</div>
<!-- End .row -->