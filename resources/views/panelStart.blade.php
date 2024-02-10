@if(empty($inputData['takeContent']))
@if(!isset($onlyPanel) || (!$onlyPanel))
<!-- Start .row -->
<div class=row>
    <div class="col-lg-12">
@endif
        <!-- col-lg-12 start here -->
        <div @if(!empty($panelId))id="{{$panelId}}"@endif @if(!empty($refreshUrl))refresh-url="{{$refreshUrl}}"@endif @if(!empty($refreshCallBack))refresh-callback="{{$refreshCallBack}}"@endif @if(!empty($dataPrefix))data-prefix="{{$dataPrefix}}"@endif @if(!empty($urlParameter))url-parameter="{{$urlParameter}}"@endif header-load="@if(@$headerLoad===false){{'false'}}@else{{'true'}}@endif" class="panel panel-default @if(@$panelMove===false){{''}}@else{{'panelMove'}}@endif showControls toggle panelClose @if(@$panelRefresh===false){{''}}@else{{'panelRefresh'}}@endif @if(!empty($class)){{$class}}@endif" @if(!empty($attr)) @foreach($attr as $atk=>$atv){{$atk.'='.$atv.' '}}@endforeach @endif>
            <!-- Start .panel -->

            
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $panelTitle; ?></h4>
            </div>
            <div class="panel-body @if(!empty($panelBodyClass)){{$panelBodyClass}}@endif">
@endif