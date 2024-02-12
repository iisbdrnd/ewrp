<table style="width:100%" class="fc-header mb10">
    <tbody>
    <tr>
        <td class="fc-header-left">
            <span class="fc-header-title">
                <h2>{{$duration->viewDate}}</h2>
            </span>
        </td>
        @if(@$panelSize!="small")
            <td class="fc-header-center">
                <span class="btn fc-button fc-button-agendaDay fc-state-default fc-corner-left btn-day @if($duration->type=="day"){{'fc-state-active'}}@endif">day</span><span class="btn fc-button fc-button-agendaWeek fc-state-default btn-week @if($duration->type=="week"){{'fc-state-active'}}@endif">week</span><span class="btn fc-button fc-button-month fc-state-default fc-corner-right btn-month @if($duration->type=="month"){{'fc-state-active'}}@endif">month</span>
            </td>
            <td class="fc-header-right">
                <span class="btn fc-button fc-button-prev fc-state-default fc-corner-left btn-previous"><span class="fc-icon fc-icon-left-single-arrow"></span></span><span class="btn fc-button fc-button-today fc-state-default btn-today @if($duration->isToday){{'fc-state-disabled'}}@endif">today</span><span class="btn fc-button fc-button-next fc-state-default fc-corner-right btn-next"><span class="fc-icon fc-icon-right-single-arrow"></span></span>
            </td>
        @endif
    </tr>
    @if(@$panelSize=="small")
    <tr>
        <td class="fc-header-center pull-left mr5">
            <span class="btn fc-button fc-button-prev fc-state-default fc-corner-left btn-previous"><span class="fc-icon fc-icon-left-single-arrow"></span></span><span class="btn fc-button fc-button-today fc-state-default btn-today @if($duration->isToday){{'fc-state-disabled'}}@endif">today</span><span class="btn fc-button fc-button-next fc-state-default fc-corner-right btn-next"><span class="fc-icon fc-icon-right-single-arrow"></span></span>
        </td>
        <td class="fc-header-right pull-left mr0">
            <span class="btn fc-button fc-button-agendaDay fc-state-default fc-corner-left btn-day @if($duration->type=="day"){{'fc-state-active'}}@endif">day</span><span class="btn fc-button fc-button-agendaWeek fc-state-default btn-week @if($duration->type=="week"){{'fc-state-active'}}@endif">week</span><span class="btn fc-button fc-button-month fc-state-default fc-corner-right btn-month @if($duration->type=="month"){{'fc-state-active'}}@endif">month</span>
        </td>
    </tr>
    @endif
    </tbody>
</table>