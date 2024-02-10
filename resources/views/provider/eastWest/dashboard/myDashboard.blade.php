<?php $urlParameter = 'false'; ?>
@include("urlParaMeter")
<input type="hidden" id="leftSide">
<input type="hidden" id="rightSide">
{{csrf_field()}}
<!-- End  / heading-->
<div id="dashboardSorting" class="row">
    <!-- .row start -->
    <div class="col-md-8">
        <div class="leftSide sortable-layout ui-sortable notAutoSortable">
            <div id="before-panel">
                <?php $panelMove=false; $onlyPanel=false; ?>
                @if(!empty($fixedPanel[0]))
                    @foreach($fixedPanel[0] as $panel)
                        <?php
                        $panelTitle = $panelInfo[$panel]['title'];
                        $dataPrefix = $panelInfo[$panel]['dataPrefix'];
                        $refreshUrl = "dashboard/my-dashboard?section=".$panel;
                        $class = $panelInfo[$panel]['class'];
                        $attr = $panelInfo[$panel]['attr'];
                        $panelBodyClass = $panelInfo[$panel]['panelBodyClass'];
                        ?>
                        @include("panelStart")
                            <?php echo $panelData[$panel]; ?>
                        @include("panelEnd")
                    @endforeach
                @endif
            </div>
            <?php $panelMove=true; $onlyPanel=true; ?>
            @if(!empty($sortablePanel[0]))
                @foreach($sortablePanel[0] as $panel)
                    <?php
                    $panelTitle = $panelInfo[$panel]['title'];
                    $dataPrefix = $panelInfo[$panel]['dataPrefix'];
                    $refreshUrl = "dashboard/my-dashboard?section=".$panel;
                    $class = $panelInfo[$panel]['class'].((!empty($panelInfo[$panel]['class'])) ? ' ' : '').'dashPanelMove';
                    $attr = array_merge($panelInfo[$panel]['attr'], array('sort-id'=>$panel));
                    $panelBodyClass = $panelInfo[$panel]['panelBodyClass'];
                    ?>
                    @include("panelStart")
                    <?php echo $panelData[$panel]; ?>
                    @include("panelEnd")
                @endforeach
            @else
            <div class="row"><div class="col-lg-12 sortable-layout ui-sortable"> </div></div>
            @endif
        </div>
    <!-- / .row -->
    </div>
    <!-- col-md-8 end here -->
    <div class="col-md-4">
        <!-- col-md-4 start here -->
        <div class="rightSide sortable-layout ui-sortable notAutoSortable">

            <div id="before-panel">
                <?php $panelMove=false; $onlyPanel=false; ?>
                @if(!empty($fixedPanel[1]))
                    @foreach($fixedPanel[1] as $panel)
                        <?php
                        $panelTitle = $panelInfo[$panel]['title'];
                        $dataPrefix = $panelInfo[$panel]['dataPrefix'];
                        $refreshUrl = "dashboard/my-dashboard?section=".$panel;
                        $class = $panelInfo[$panel]['class'];
                        $attr = $panelInfo[$panel]['attr'];
                        $panelBodyClass = $panelInfo[$panel]['panelBodyClass'];
                        ?>
                        @include("panelStart")
                        <?php echo $panelData[$panel]; ?>
                        @include("panelEnd")
                    @endforeach
                @endif
            </div>
            <?php $panelMove=true; $onlyPanel=true; ?>
            @if(!empty($sortablePanel[1]))
                @foreach($sortablePanel[1] as $panel)
                    <?php
                    $panelTitle = $panelInfo[$panel]['title'];
                    $dataPrefix = $panelInfo[$panel]['dataPrefix'];
                    $refreshUrl = "dashboard/my-dashboard?section=".$panel;
                    $class = $panelInfo[$panel]['class'].((!empty($panelInfo[$panel]['class'])) ? ' ' : '').'dashPanelMove';
                    $attr = array_merge($panelInfo[$panel]['attr'], array('sort-id'=>$panel));
                    $panelBodyClass = $panelInfo[$panel]['panelBodyClass'];
                    ?>
                    @include("panelStart")
                    <?php echo $panelData[$panel]; ?>
                    @include("panelEnd")
                @endforeach
            @else
            <div class="row"><div class="col-lg-12 sortable-layout ui-sortable"> </div></div>
            @endif
        </div>
    <!-- col-md-4 end here -->
</div>
<!-- / .row -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".usernav").prepend('<li class="dashButton"><a id="dashPanelAdd" class="hand"><i class="s16 icomoon-icon-folder-plus-3" style="margin-top: -6px;"></i><span class="txt">Add Dashlets</span></a></li>');

        $("#dashPanelAdd").click(function(e){
            e.preventDefault();
            var title = "Add";
            var detailsUrl = 'dashPanelList?dashType=1';
            showDetails(title, detailsUrl);

            $(".modal").on("click", ".panelAdd", function(){
                $(this).attr("disabled", "disabled");
                var sortId = $(this).attr("sort-id");
                var sortPosition = $(this).attr("sort-position");
                var panel = panelBox(sortId);

                if(sortPosition==2) {
                    $(".rightSide").find("#before-panel").after(panel);
                    panelHeaderLoad($(".rightSide").find(".data-table").first(), "dashboard");
                    loadDataTable($(".rightSide").find(".data-table").first(), "dashboard", false);
                } else {
                    $(".leftSide").find("#before-panel").after(panel);
                    panelHeaderLoad($(".leftSide").find(".data-table").first(), "dashboard");
                    loadDataTable($(".leftSide").find(".data-table").first(), "dashboard", false);
                }
                dashboardPanelUpdate();
                var d = $("#dashboardSorting").find(".sortable-layout"),
                        e = d.find(".dashPanelMove"),
                        g = e.find(".panel-heading");
                dashboardSorting(d, e, g);
            });
        });

        /*var d = $("#dashboardSorting").find(".sortable-layout"),
                e = d.find(".dashPanelMove"),
                g = e.find(".panel-heading");
        dashboardSorting(d, e, g);*/

        var d = $("#dashboardSorting").find(".sortable-layout"),
                        e = d.find(".dashPanelMove"),
                        g = e.find(".panel-heading");
                dashboardSorting(d, e, g);

        $("#dashboardSorting").on("click", ".panel-close", function(){
            dashboardPanelUpdate($(this).parents(".panel"));
        });

        $(function() {
            $(".greenCircle").knob({
                min: 0,
                max: 100,
                readOnly: !0,
                width: 80,
                height: 80,
                fgColor: "#9FC569",
                dynamicDraw: !0,
                thickness: .2,
                tickColorizeValues: !0
            }), $(".redCircle").knob({
                min: 0,
                max: 100,
                readOnly: !0,
                width: 80,
                height: 80,
                fgColor: "#ED7A53",
                dynamicDraw: !0,
                thickness: .2,
                tickColorizeValues: !0
            }), $(".blueCircle").knob({
                min: 0,
                max: 100,
                readOnly: !0,
                width: 80,
                height: 80,
                fgColor: "#88BBC8",
                dynamicDraw: !0,
                thickness: .2,
                tickColorizeValues: !0
            })
        });

        $(".elastic").autosize(),
        $(function() {
            $("#today, #tomorrow").sortable({
                connectWith: ".todo-list",
                placeholder: "placeholder",
                forcePlaceholderSize: !0
            }).disableSelection()
        });
    });

    function dashboardPanelUpdate($notSelector) {
        var leftSide = [];
        var rightSide = [];

        $(".leftSide").find(".dashPanelMove").not($notSelector).each(function() {
            var sortId = $(this).attr("sort-id");
            leftSide.push(sortId);
        });
        $(".rightSide").find(".dashPanelMove").not($notSelector).each(function() {
            var sortId = $(this).attr("sort-id");
            rightSide.push(sortId);
        });

        var preLeftSide = $("#leftSide").val();
        var preRightSide = $("#rightSide").val();

        if(preLeftSide!=leftSide || preRightSide!=rightSide) {
            $("#leftSide").val(leftSide);
            $("#rightSide").val(rightSide);
            $.ajax({
                url: URL.getSiteAction('/dashboardSorting'),
                data: {dashType: 1, leftSide: leftSide, rightSide: rightSide, _token: $("input[name='_token']").val()},
                type: 'POST'
            });
        }
    }

    function panelBox(panel) {
        panel = parseInt(panel);
        switch(panel) {
            case 2:
                //Opportunities
                var tableTitle = "Opportunities", dataPrefix="opportunities", updateLink = "opportunities";
                break;
            case 3:
                //Accounts
                var tableTitle = "Accounts", dataPrefix="accounts", updateLink = "accounts";
                break;
            case 4:
                //Leads
                var tableTitle = "Leads", dataPrefix="leads", updateLink = "leads";
                break;
            case 5:
                //Meeting
                var tableTitle = "Meeting", dataPrefix="meeting", updateLink = "activities/meeting";
                break;
            case 6:
                //Task
                var tableTitle = "Task", dataPrefix="task", updateLink = "activities/task";
                break;
            case 7:
                //Note
                var tableTitle = "Note", dataPrefix="note", updateLink = "activities/note";
                break;
            case 8:
                //Calendar
                var tableTitle = "Calendar", dataPrefix="calendar";
                break;
            case 51:
                //Lead Status
                var tableTitle = "Lead Status", dataPrefix="leadStatus";
                break;
            case 52:
                //Opportunities Stage
                var tableTitle = "Opportunities Stage", dataPrefix="opportunitiesStage";
                break;
            case 53:
                //Lost of Reason
                var tableTitle = "Lost of Reason", dataPrefix="lostReason";
                break;
            case 54:
                //Opportunities by Source
                var tableTitle = "Opportunities by Source", dataPrefix="opportunitiesSource";
                break;
        }
        var dashPanel = '<div load-url="dashboard/my-dashboard?section='+panel+'"';
        dashPanel += (dataPrefix) ? ' data-prefix="'+dataPrefix+'"' : '';
        dashPanel += (urlParameter) ? ' url-parameter="false"' : '';
        dashPanel += (updateLink) ? ' update-link="'+updateLink+'"' : '';
        dashPanel += 'class="';
        dashPanel += 'data-table panel panel-default showControls toggle panelClose panelRefresh dashPanelMove" sort-id="'+panel+'"><div class="panel-heading"><h4 class="panel-title">'+tableTitle+'</h4></div><div class="panel-body data-list';
        dashPanel += '"></div></div>';
        return dashPanel;
    }

    function dashboardSorting(d, e, g) {
        d.sortable({
            items: e,
            handle: g,
            placeholder: "panel-placeholder",
            forcePlaceholderSize: !0,
            helper: "original",
            forceHelperSize: !0,
            cursor: "move",
            delay: 10,
            opacity: .8,
            zIndex: 1e4,
            tolerance: "pointer",
            iframeFix: !1,
            revert: !0,
            update: function() {
                dashboardPanelUpdate();
            }
        }).sortable("option", "connectWith", d);
    }
</script>