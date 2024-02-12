@if(empty($inputData['takeContent']))
<div class="heading">
    <h3>{{$pageTitle}}</h3>
    <ul class="breadcrumb">
        <li>You are here:</li>
        <li @if(empty($breadCrumb->sub_menu_name) && empty($breadCrumb->child_menu_name))class="active"@endif>@if(!empty($breadCrumb->sub_menu_name) || !empty($breadCrumb->child_menu_name))<a href="{{$breadCrumb->main_menu_link}}" class="tip" title="{{$breadCrumb->main_menu_name}}">@if((empty($breadCrumb->sub_menu_name) && empty($breadCrumb->child_menu_name)) || empty($breadCrumb->main_menu_icon)){{$breadCrumb->main_menu_name}}@else<i class="s16 {{$breadCrumb->main_menu_icon}}"></i>@endif</a> <span class=divider><i class="s16 icomoon-icon-arrow-right-3"></i></span>@else{{$breadCrumb->main_menu_name}}@endif</li>
        @if(!empty($breadCrumb->sub_menu_name))
            <li @if(empty($breadCrumb->child_menu_name))class="active"@endif>@if(!empty($breadCrumb->child_menu_name))<a href="{{$breadCrumb->sub_menu_link}}" style="text-decoration: none;">{{$breadCrumb->sub_menu_name}}</a> <span class=divider><i class="s16 icomoon-icon-arrow-right-3"></i></span>@else{{$breadCrumb->child_menu_name}}@endif</li>
        @endif
        @if(!empty($breadCrumb->child_menu_name))
            <li class="active">{{$breadCrumb->child_menu_name}}</li>
        @endif
    </ul>
</div>
@endif