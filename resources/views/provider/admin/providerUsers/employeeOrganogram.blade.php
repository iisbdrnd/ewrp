<?php $panelTitle = "Employee Organogram"; ?>
@include("panelStart")
<div id="main" class="pl0">
    <div id="example">
        <div id="editme"></div>
        <div id="d3view"></div>
    </div>
</div>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function(){
        demo.run({
            data: flare2treed(<?php echo $organogram; ?>),
            ctrlOptions: {
                noCollapseRoot: false
            },
            el: 'editme',
            noTitle: true
        }, initD3);
        @if($highestStep>8)
            $("#d3view").find("svg").attr("width", "{{$highestStep*105+120}}");
        @endif
    });
</script>