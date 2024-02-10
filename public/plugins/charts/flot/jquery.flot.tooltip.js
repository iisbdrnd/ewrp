(function($){var defaultOptions={tooltip:!1,tooltipOpts:{content:"%s | X: %x | Y: %y",xDateFormat:null,yDateFormat:null,shifts:{x:10,y:20},defaultTheme:!0,onHover:function(flotItem,$tooltipEl){}}};var FlotTooltip=function(plot){this.tipPosition={x:0,y:0};this.init(plot)};FlotTooltip.prototype.init=function(plot){var that=this;plot.hooks.bindEvents.push(function(plot,eventHolder){that.plotOptions=plot.getOptions();if(that.plotOptions.tooltip===!1||typeof that.plotOptions.tooltip==='undefined')return;that.tooltipOptions=that.plotOptions.tooltipOpts;var $tip=that.getDomElement();$(plot.getPlaceholder()).bind("plothover",function(event,pos,item){if(item){var tipText;tipText=that.stringFormat(that.tooltipOptions.content,item);$tip.html(tipText);that.updateTooltipPosition({x:pos.pageX,y:pos.pageY});$tip.css({left:that.tipPosition.x+that.tooltipOptions.shifts.x,top:that.tipPosition.y+that.tooltipOptions.shifts.y}).show();if(typeof that.tooltipOptions.onHover==='function'){that.tooltipOptions.onHover(item,$tip)}}
else{$tip.hide().html('')}});eventHolder.mousemove(function(e){var pos={};pos.x=e.pageX;pos.y=e.pageY;that.updateTooltipPosition(pos)})})};FlotTooltip.prototype.getDomElement=function(){var $tip;if($('#flotTip').length>0){$tip=$('#flotTip')}
else{$tip=$('<div />').attr('id','flotTip');$tip.appendTo('body').hide().css({position:'absolute'});if(this.tooltipOptions.defaultTheme){$tip.css({'background':'#fff','z-index':'100','padding':'0.4em 0.6em','border-radius':'0.5em','font-size':'0.8em','border':'1px solid #111','display':'inline-block','white-space':'nowrap','color':'#000'})}}
return $tip};FlotTooltip.prototype.updateTooltipPosition=function(pos){var totalTipWidth=$("#flotTip").outerWidth()+this.tooltipOptions.shifts.x;var totalTipHeight=$("#flotTip").outerHeight()+this.tooltipOptions.shifts.y;if((pos.x-$(window).scrollLeft())>($(window).innerWidth()-totalTipWidth)){pos.x-=totalTipWidth}
if((pos.y-$(window).scrollTop())>($(window).innerHeight()-totalTipHeight)){pos.y-=totalTipHeight}
this.tipPosition.x=pos.x;this.tipPosition.y=pos.y};FlotTooltip.prototype.stringFormat=function(content,item){var percentPattern=/%p\.{0,1}(\d{0,})/;var seriesPattern=/%s/;var xPattern=/%x\.{0,1}(?:\d{0,})/;var yPattern=/%y\.{0,1}(?:\d{0,})/;if(typeof(content)==='function'){content=content(item.series.label,item.series.data[item.dataIndex][0],item.series.data[item.dataIndex][1])}
if(typeof(item.series.percent)!=='undefined'){content=this.adjustValPrecision(percentPattern,content,item.series.percent)}
if(typeof(item.series.label)!=='undefined'){content=content.replace(seriesPattern,item.series.label)}
if(this.isTimeMode('xaxis',item)&&this.isXDateFormat(item)){content=content.replace(xPattern,this.timestampToDate(item.series.data[item.dataIndex][0],this.tooltipOptions.xDateFormat))}
if(this.isTimeMode('yaxis',item)&&this.isYDateFormat(item)){content=content.replace(yPattern,this.timestampToDate(item.series.data[item.dataIndex][1],this.tooltipOptions.yDateFormat))}
if(typeof item.series.data[item.dataIndex][0]==='number'){content=this.adjustValPrecision(xPattern,content,item.series.data[item.dataIndex][0])}
if(typeof item.series.data[item.dataIndex][1]==='number'){content=this.adjustValPrecision(yPattern,content,item.series.data[item.dataIndex][1])}
if(typeof item.series.xaxis.tickFormatter!=='undefined'){content=content.replace(xPattern,item.series.xaxis.tickFormatter(item.series.data[item.dataIndex][0],item.series.xaxis))}
if(typeof item.series.yaxis.tickFormatter!=='undefined'){content=content.replace(yPattern,item.series.yaxis.tickFormatter(item.series.data[item.dataIndex][1],item.series.yaxis))}
return content};FlotTooltip.prototype.isTimeMode=function(axisName,item){return(typeof item.series[axisName].options.mode!=='undefined'&&item.series[axisName].options.mode==='time')};FlotTooltip.prototype.isXDateFormat=function(item){return(typeof this.tooltipOptions.xDateFormat!=='undefined'&&this.tooltipOptions.xDateFormat!==null)};FlotTooltip.prototype.isYDateFormat=function(item){return(typeof this.tooltipOptions.yDateFormat!=='undefined'&&this.tooltipOptions.yDateFormat!==null)};FlotTooltip.prototype.timestampToDate=function(tmst,dateFormat){var theDate=new Date(tmst);return $.plot.formatDate(theDate,dateFormat)};FlotTooltip.prototype.adjustValPrecision=function(pattern,content,value){var precision;var matchResult=content.match(pattern);if(matchResult!==null){if(RegExp.$1!==''){precision=RegExp.$1;value=value.toFixed(precision);content=content.replace(pattern,value)}}
return content};var init=function(plot){new FlotTooltip(plot)};$.plot.plugins.push({init:init,options:defaultOptions,name:'tooltip',version:'0.6.1'})})(jQuery)