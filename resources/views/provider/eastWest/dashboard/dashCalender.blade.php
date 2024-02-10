<div id="calendar"></div>

<script type="text/javascript">
$(document).ready(function(){
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $("#calendar").fullCalendar({
        header: {
            left: "title",
            center: "agendaDay,agendaWeek,month",
            right: "prev,today,next"
        },
        buttonText: {
            prev: '<span class="fc-icon fc-icon-left-single-arrow"></span>',
            next: '<span class="fc-icon fc-icon-right-single-arrow"></span>'
        },
        timeFormat: "",
        editable: 0,
        droppable: 0,
        events: [@foreach($crmActivitiesMeeting as $meeting)
            <?php
            $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $meeting->start_date);
            $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $meeting->end_date);
            $title = str_replace('"', '\"', $meeting->subject);
            $title = $title.' ('.$meeting->table_type.')'.'\n('.$start_date->format('h:ia').'-'.$end_date->format('h:ia').')';
            ?>
            {
            title: "<?php echo $title; ?>",
            start: new Date({{$start_date->format('Y')}}, {{$start_date->format('m')-1}}, {{$start_date->format('d')}}, {{$start_date->format('H')}}, {{$start_date->format('i')}}),
            end: new Date({{$end_date->format('Y')}}, {{$end_date->format('m')-1}}, {{$end_date->format('d')}},{{$end_date->format('H')}}, {{$end_date->format('i')}}),
            url: "{{$meeting->table_type.'-'.$meeting->id}}",
            @if($meeting->table_type!='Meeting')color: "#9FC569",@endif
            allDay: @if($start_date->format('h:ia')=='12:00am' && $end_date->format('h:ia')=='12:00am') true @else false @endif
        },
            @endforeach]
    });

    $("#calendar").on("click", ".fc-event", function(e){
        e.preventDefault();
        var data = $(this).attr("href");
        var title = $(this).find(".fc-event-title").html();
        var detailsUrl = 'activities/calendar/details';
        showDetails(title, detailsUrl, data);
    });
});
    
</script>