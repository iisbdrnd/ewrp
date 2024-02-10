//Global Variable
var masterCallForward;
var urlSeparatorDataTable = "~";
var urlParameter = true;
var masterTitle = $("title").html();
masterTitle = masterTitle.split(' | ');
masterTitle = masterTitle[0];

//Function//
function dataFilter(data, type) {
    if(type=='json') {
        if(parseInt(data.auth)===0) {
            redirectLoginPage();
            return false;
        }
    } else {
        if(parseInt(data)===0) {
            redirectLoginPage();
            return false;
        }
    }
}

//Only for Form
function panelRefresh(that, refreshUrl, data, refreshCallBack) {
    preLoader(that);
    $.ajax({
        mimeType: 'text/html; charset=utf-8',
        url : appUrl.getSiteAction('/'+refreshUrl),
        data: data,
        type: 'GET',
        dataType: "html",
        success: function(data) {
            if(parseInt(data)===0) {
                redirectLoginPage();
            } else {
                that.find(".panel-body").first().html(data);
                loadForm(that, refreshUrl);
                if(typeof refreshCallBack!==typeof undefined && refreshCallBack!==false) {
                    callBackFunction(refreshCallBack);
                }
                preLoaderHide(that);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal("Cancelled", errorThrown, "error");
        },
        async: false
    });
}
//Only for pagination
function paginateRefresh(that, url) {
    var refreshUrl = that.attr('refresh-url');
    var refreshCallBack = that.attr('refresh-callback');
    var dataPrefix = ($(this).attr('data-prefix')) ? $(this).attr('data-prefix') : false;

    if(refreshUrl) {
        var inputClass = (dataPrefix) ? dataPrefix + "-data-input" : "data-input";
        var inputArray = {};
        $("." + inputClass).each(function () {
            var input = $(this).attr("id");
            var input = input.split(urlSeparatorDataTable);
            var input = (input.length > 1) ? input[1] : input;
            inputArray[input] = $(this).val();
        });
        loadAjaxPaginateContent(that, url, refreshUrl, inputArray, dataPrefix, '', refreshCallBack);
    }
}
function PreLoadEvent() {
    $('#ajax-content').on('click', '.panel-controls a', function(e){
        e.preventDefault();
        var pageSelf = $(this), pageThisIcon = pageSelf.find("i"), pageThisPanel = pageSelf.closest(".panel"), pageThisPanelBody = pageThisPanel.find(".panel-body"), pageThisPanelFooter = pageThisPanel.find(".panel-footer"), pageThisPanelHeading = pageThisPanel.find(".panel-heading");
        pageSelf.hasClass("panel-close") && setTimeout(function() { pageThisPanel.remove() }, 500),
        pageSelf.hasClass("toggle") && (pageSelf.toggleClass("panel-minimize panel-maximize"), pageThisIcon.toggleClass("icomoon-icon-plus"), pageThisPanelBody.slideToggle(200), pageThisPanelFooter.slideToggle(200), pageThisPanelHeading.toggleClass("min"));
    });
}
function afterLoading(url) {
    //ToolTip
    $(".tip").tooltip();
    var b = $(".panel");
    b.each(function(b) {
        var pageSelf = $(this);
		var headerLoad = $(this).attr('header-load');
        if(typeof headerLoad===typeof undefined || headerLoad=='true') { panelHeaderLoad(pageSelf, url); }
    });
    b.hover(function() {
            $(this).find(".panel-controls").hasClass("panel-controls-hide") && $(this).find(".panel-controls").fadeIn(300)
        },
        function() {
            $(this).find(".panel-controls").hasClass("panel-controls-hide") && $(this).find(".panel-controls").fadeOut(300)
        });
    var c = "panels_position_" + h;
    if (!$(".contentwrapper").hasClass("notSortable")) {
        $(".panelMove").each(function() { $(this).parent().addClass("sortable-layout ui-sortable"); });
        var d = $(".contentwrapper").find(".sortable-layout").not(".notAutoSortable"),
            e = d.find(".panelMove"),
            g = d.find(".panelMove>.panel-heading"),
            h = window.location.href,
            i = localStorage.getItem(c);
        if (i) {
            var j = JSON.parse(i);
            for (var k in j.grid) {
                var l = d.eq(k);
                for (var m in j.grid[k].section) l.append($("#" + j.grid[k].section[m].id))
            }
        }
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
            update: function(a, b) { panelSavePosition(b.item) }
        }).sortable("option", "connectWith", d),
        panelSavePosition = function() {
            var b = [];
            d.each(function() {
                var c = [];
                $(this).children(".panelMove").each(function() {
                    var b = {};
                    b.id = $(this).attr("id"), c.push(b)
                });
                var d = {
                    section: c
                };
                b.push(d)
            });
            var e = JSON.stringify({
                grid: b
            });
            i != e && localStorage.setItem(c, e, null)
        }
    }
    if ($(".data-table").length>0) { loadCustomDataTable(url); }
    if ($(".form-load").length>0) { loadForm('', url); }
}

function panelHeaderLoad(pageSelf, url) {
    var pagePanelHeading = pageSelf.find(".panel-heading").first(), pagePanelControlsRight;
    (pageSelf.hasClass("toggle") || pageSelf.hasClass("panelClose") || pageSelf.hasClass("panelRefresh")) &&
    (pagePanelHeading.find(".panel-controls-right").length ? pagePanelControlsRight = pagePanelHeading.find(".panel-controls-right") : (pagePanelHeading.append('<div class="panel-controls panel-controls-right">'), pagePanelControlsRight = pagePanelHeading.find(".panel-controls-right"))),
    pageSelf.hasClass("panelRefresh") && !pagePanelControlsRight.find("a.panel-refresh").length && pagePanelControlsRight.append('<a href="#" class="panel-refresh"><i class="brocco-icon-refresh s12"></i></a>'),
    pageSelf.hasClass("toggle") && !pagePanelControlsRight.find("a.toggle").length && (pageSelf.hasClass("panel-closed") ? (pagePanelControlsRight.append('<a href="#" class="toggle panel-maximize"><i class="icomoon-icon-plus"></i></a>'), pageSelf.find(".panel-body").slideToggle(0), pageSelf.find(".panel-footer").slideToggle(0), pageSelf.find(".panel-heading").toggleClass("min")) : pagePanelControlsRight.append('<a href="#" class="toggle panel-minimize"><i class="icomoon-icon-minus"></i></a>')),
    pageSelf.hasClass("panelClose") && !pagePanelControlsRight.find("a.panel-close").length && pagePanelControlsRight.append('<a href="#" class="panel-close"><i class="icomoon-icon-close"></i></a>'),
        pageSelf.hasClass("showControls") ? (pageSelf.find(".panel-controls-left").first().addClass("panel-controls-show"), pageSelf.find(".panel-controls-right").first().addClass("panel-controls-show")) : (pageSelf.find(".panel-controls-left").first().addClass("panel-controls-hide"), pageSelf.find(".panel-controls-right").first().addClass("panel-controls-hide"));
    scolling($(this));
    //Panel Refresh
    if(pageSelf.hasClass("panelRefresh") && !pageSelf.hasClass('data-table')) {
        pagePanelHeading.find('a.panel-refresh').click(function() {
            var refreshUrl = pageSelf.attr('refresh-url');
            var refreshCallBack = pageSelf.attr('refresh-callback');
            if (typeof refreshUrl===typeof undefined || refreshUrl===false) { var refreshUrl = url; }
            panelRefresh(pageSelf, refreshUrl, {takeContent:1}, refreshCallBack);
        });
    }
}

function scolling(that) {
    var c = that.find(".scroll"), d = c.data("height");
    c.slimScroll({
        position: "right",
        height: "100%",
        distance: "0",
        railVisible: !1,
        size: "3px",
        color: "#777",
        railOpacity: "1",
        railColor: "#fff",
        height: d
    });
    var e = that.find(".scroll-horizontal");
    e.slimScrollHorizontal({
        size: "3px",
        color: "#777",
        railOpacity: "1",
        railColor: "#fff",
        width: "100%",
        positon: "bottom",
        start: "left",
        railVisible: !0
    });
    that.find(".responsive").each(function(){
        $(this).wrap('<div class="table-responsive" />');
        $(this).parent().slimScrollHorizontal({
            size: "3px",
            color: "#f3f3f3",
            railOpacity: "0.3",
            width: "100%",
            positon: "bottom",
            start: "left",
            railVisible: !0,
            distance: "3px"
        })
    });
}

function urlParameterSet(parameterKey, value) {
    var parameter = $(".url-pMeter").find("input[id='"+parameterKey+"']");
    var parameterLength = parameter.length;
    var urlHash = false;
    if(parameterLength>0) {
        if(value) {
            if(parameter.val() !== value) {
                parameter.val(value);
                urlHash = true;
            }
        } else {
            parameter.remove();
            urlHash = true;
        }
    } else {
        if(value) {
            var parameterArray = parameterKey.split(urlSeparatorDataTable);
            var parameterClass = (parameterArray.length>1) ? parameterArray[0]+"-data-input" : "data-input";
            $(".url-pMeter").append('<input id="'+parameterKey+'" class="'+parameterClass+'" type="hidden" value="'+value+'">');
            urlHash = true;
        }
    }
    if(urlHash) {
        var parameterKeyArray = parameterKey.split(urlSeparatorDataTable);
        var parameterPrefix = (parameterKeyArray.length>1) ? parameterKeyArray[0]+urlSeparatorDataTable : "";
        var parameterKey = (parameterKeyArray.length>1) ? parameterKeyArray[1] : parameterKey;
        if(parameterKey!="page") { $(".url-pMeter").find("input[id='"+parameterPrefix+"page']").remove(); }
    }
    return urlHash;
}

function urlParameterReset(url, parameterKey, value) {
    var parameter = $(".url-pMeter").find("input[id='"+parameterKey+"']");
    var parameterLength = parameter.length;
    var urlHash = false;
    if(parameterLength>0) {
        if(value) {
            if(parameter.val() !== value) {
                parameter.val(value);
                urlHash = true;
            }
        } else {
            parameter.remove();
            urlHash = true;
        }
    } else {
        if(value) {
            var parameterArray = parameterKey.split(urlSeparatorDataTable);
            var parameterClass = (parameterArray.length>1) ? parameterArray[0]+"-data-input" : "data-input";
            $(".url-pMeter").append('<input id="'+parameterKey+'" class="'+parameterClass+'" type="hidden" value="'+value+'">');
            urlHash = true;
        }
    }
    if(urlHash) {
        var parameterKeyArray = parameterKey.split(urlSeparatorDataTable);
        var parameterPrefix = (parameterKeyArray.length>1) ? parameterKeyArray[0]+urlSeparatorDataTable : "";
        var parameterKey = (parameterKeyArray.length>1) ? parameterKeyArray[1] : parameterKey;
        if(parameterKey!="page") { $(".url-pMeter").find("input[id='"+parameterPrefix+"page']").remove(); }
    }
    return urlHash;
}

function urlParameterDestroy(dataPrefix) {
    var parameterClass = (typeof dataPrefix===typeof undefined || dataPrefix=='') ? "data-input" : dataPrefix+"-data-input";
    $(".url-pMeter").find("."+parameterClass).remove();
}

function seqPaginateParameterDestroy(dataPrefix) {
    var seqPaginate = "seq-paginate";
    $(".seqPaginate-pMeter").find("."+seqPaginate).remove();
}

function urlGenerate(url) {
    if(urlParameter) {
        var url = url.split("?");
        var url = url[0];
        var i = 1, mainUrl = url;

        $(".url-pMeter").find("input").each(function(){
            var dataKey = $(this).attr("id");
            var data = $(this).val();
            if((data) && (dataKey)) {
                mainUrl += (i==1) ? "?" : "&";
                mainUrl += dataKey+"="+data;
                i++;
            }
        });
        window.location.hash = mainUrl;
    }
}

function loadCustomDataTable(url) {
    var url = url.split('?');
    var url = url[0];
    $(".data-table").each(function(){
        loadDataTable($(this), url);
    });
}

function loadDataTable(selector, url, urlParameterManualPermission) {
    if (typeof urlParameterManualPermission===typeof undefined || urlParameterManualPermission==="") { urlParameterManualPermission=true; }
    var panelTitle = selector.find('.panel-title').html();
    var csrf = selector.find("input[name='_token']").val();
    var loadUrl = (selector.attr('load-url')) ? selector.attr('load-url') : false;
    if(!loadUrl) { loadUrl = (selector.attr('refresh-url')) ? selector.attr('refresh-url') : false; }
    var updateLink = (selector.attr('update-link')) ? selector.attr('update-link') : false;
    var deleteLink = (selector.attr('delete-link')) ? selector.attr('delete-link') : false;
    var dataPrefix = (selector.attr('data-prefix')) ? selector.attr('data-prefix') : false;
    var autoLoad = (selector.attr('auto-load') && selector.attr('auto-load')=='false') ? false : true;
    var urlParameterInListPermission = (selector.attr('url-parameter') && selector.attr('url-parameter')=='false') ? false : true;
    var updateBack = (selector.attr('update-back') && selector.attr('update-back')=='false') ? false : true;
    var updateBackString = (updateBack) ? 'true' : 'false';
    var mainCallBack = selector.attr('refresh-callback');

    urlParameterManualPermission = (!urlParameterInListPermission) ? false : urlParameterManualPermission;

    if(loadUrl) {
        selector.removeAttr('refresh-url').attr('refresh-url', loadUrl);
        selector.removeAttr('load-url').removeAttr('update-link').removeAttr('delete-link').removeAttr('url-parameter').removeAttr('update-back').removeAttr('callback');
        selector.find("input[name='_token']").remove();
        var inputClass = (dataPrefix) ? dataPrefix+"-data-input" : "data-input";
        var inputArray = {};
        $("."+inputClass).each(function(){
            var input = $(this).attr("id");
            var input = input.split(urlSeparatorDataTable);
            var input = (input.length>1) ? input[1] : input;
            inputArray[input] = $(this).val();
        });
        var that = selector;
        if(selector.hasClass('panelRefresh')) {
            selector.find('.panel-refresh').click(function() {
                var refreshUrl = that.attr('refresh-url');
                var refreshBack = ((that.attr('refresh-back')) && (that.attr('refresh-back'))=='false') ? false : true;
                var refreshCallBack = that.attr('refresh-callback');
                if (typeof refreshUrl===typeof undefined && refreshUrl===false) { var refreshUrl = loadUrl; }
                if(refreshUrl==loadUrl) {
                    $("."+inputClass).remove();
                    if(urlParameterManualPermission) { urlGenerate(url); }
                    loadAjaxPaginateContent(that, url, loadUrl, {}, dataPrefix, urlParameterManualPermission, refreshCallBack);
                } else {
                    loadDataListForm(that, refreshUrl, {takeContent:1}, 'inPage', {}, refreshBack, refreshCallBack);
                }
            });
        }

        selector.find(".data-list")
            //Pagination
            .on('click', ".pagination li a", function(e) {
                if($(this).parents(".notDataList").length<=0) {
                    e.preventDefault();
                    var paginateUrl = $(this).attr("href");
                    var paginateUrl = paginateUrl.split("page=");
                    var page = (paginateUrl.length==2) ? paginateUrl[1] : "";
                    var parameter = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+"page" : "page";
                    inputArray["page"] = page;
                    var urlHash = urlParameterSet(parameter, page);
                    if(urlHash) { if(urlParameterManualPermission) { urlGenerate(url); } }
                    loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);
                }
            })//PerPage
            .on('change', "#perPage", function(e) {
                e.preventDefault();
                var perPage = $(this).val();
                var parameter = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+"perPage" : "perPage";
                inputArray["perPage"] = perPage;
                inputArray["page"] = "";
                var urlHash = urlParameterSet(parameter, perPage);
                if(urlHash) { if(urlParameterManualPermission) { urlGenerate(url); } }
                loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);
            })//AscDesc
            .on('click', ".data-sorting", function(e) {
                e.preventDefault();
                var parameterAscVal = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+"asc" : "asc";
                var parameterDescVal = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+"desc" : "desc";
                var parameterAsc = $(".url-pMeter").find("input[id='"+parameterAscVal+"']");
                var curAsc = (parameterAsc.length>0) ? parameterAsc.val() : "";
                var ascDescData = $(this).attr("data");
                var asc = ""; var desc = "";
                if(ascDescData==curAsc) { desc = ascDescData; }
                else { asc = ascDescData; }

                inputArray["asc"] = asc;
                inputArray["desc"] = desc;
                inputArray["page"] = "";
                var urlHashAsc = urlParameterSet(parameterAscVal, asc);
                var urlHashDesc = urlParameterSet(parameterDescVal, desc);
                if((urlHashAsc) || (urlHashDesc)) { if(urlParameterManualPermission) { urlGenerate(url); } }
                loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);
            })
            //Add New
            .on('click', ".add-btn", function(e) {
                e.preventDefault();
                var callBack = $(this).attr('callback');
                var addUrl = $(this).attr('url');
                var addBack = ($(this).attr('addBack') && $(this).attr('addBack')=='false') ? false : true;
                var viewType = ($(this).attr('view-type')) ? $(this).attr('view-type') : 'inPage';

                var addBackString = (addBack) ? 'true' : 'false';
                if (typeof addUrl===typeof undefined || addUrl===false) { var addUrl = url+'/create'; }

                var modalData = {};
                if(viewType=='modal') {
                    modalData['data'] = {takeContent:1};
                    if($(this).attr('data')) { modalData['data']['data'] = $(this).attr('data'); }
                    modalData['title'] = ($(this).attr('title')) ? $(this).attr('title') : $(this).html();
                    modalData['modalSize'] = ($(this).attr('modal-size')) ? $(this).attr('modal-size') : "";
					var addBack = false;
					var returnUrl = $(this).closest(".panel").attr("refresh-url");
					var extraParameter = {
                        returnUrl: returnUrl,
                        returnSelector: $(this).closest(".panel").find(".data-list"),
                        returnDataPrefix: dataPrefix,
                        returnUrlParameterPermission: urlParameterManualPermission
                    };
                } else {
                    that.removeAttr('refresh-url').attr('refresh-url', addUrl);
                    if(typeof callBack!==typeof undefined && callBack!==false) {
                        that.removeAttr('refresh-callback').attr('refresh-callback', callBack);
                    }
                    that.removeAttr('refresh-back').attr('refresh-back', addBackString);
					var extraParameter = {};
                }

                loadDataListForm(that, addUrl, {takeContent:1}, viewType, modalData, addBack, callBack, extraParameter);
            })//Edit
            .on('click', "#edit", function(e) {
                e.preventDefault();
                var callBack = $(this).attr('callback');
				var updateUrl = $(this).attr('url');
                var data = $(this).attr('data');
                var viewType = ($(this).attr('view-type')) ? $(this).attr('view-type') : 'inPage';

				if (typeof updateUrl===typeof undefined) {
					var updateLinkUrl = (updateLink) ? updateLink+'/'+data+'/edit' : url+'/'+data+'/edit';
				} else {
					var updateLinkUrl = updateUrl;
				}

                var modalData = {};
                if(viewType=='modal') {
                    modalData['data'] = {takeContent:1};
                    if($(this).attr('data')) { modalData['data']['data'] = $(this).attr('data'); }
                    modalData['title'] = ($(this).attr('title')) ? $(this).attr('title') : $(this).html();
                    modalData['modalSize'] = ($(this).attr('modal-size')) ? $(this).attr('modal-size') : "";
					var updateBack = false;
					var returnUrl = $(this).closest(".data-table").attr("refresh-url");
					var extraParameter = {
                        returnUrl: returnUrl,
                        returnSelector: $(this).closest(".data-table").find(".data-list"),
                        returnDataPrefix: dataPrefix,
                        returnUrlParameterPermission: urlParameterManualPermission
                    };
                } else {
					that.removeAttr('refresh-url').attr('refresh-url', updateLinkUrl);
                    if(typeof callBack!==typeof undefined && callBack!==false) {
                        that.removeAttr('refresh-callback').attr('refresh-callback', callBack);
                    }
                    that.removeAttr('refresh-back').attr('refresh-back', updateBackString);
                    var updateBack = true;
					var extraParameter = {};
                }

                loadDataListForm(that, updateLinkUrl, {}, viewType, modalData, updateBack, callBack, extraParameter);
            })//Delete
            .on('click', "#delete", function(e) {
                e.preventDefault();
                var data = $(this).attr('data');
                var dataType = ($(this).attr('data-type')) ? $(this).attr('data-type') : 'html';
                var callBack = ($(this).attr('callback')) ? $(this).attr('callback') : false;
                var deleteLinkUrl = (deleteLink) ? deleteLink+'/'+data : url+'/'+data;
                    // console.log(deleteLinkUrl);
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this data!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: !0
                }, function() {
                    preLoader(that);
                    $.ajax({
                        url : appUrl.getSiteAction('/'+deleteLinkUrl),
                        type: "DELETE",
                        data: {'_token': csrf},
                        dataType: dataType,
                        success:function(data){
                            if(dataType=="html" && parseInt(data)===0) {
                                redirectLoginPage();
                            } else if(dataType=="json" && data.auth===0) {
                                redirectLoginPage();
                            } else {
                                var dataError = (dataType=="html") ? data.trim() : data.error;
                                if((typeof dataError!==typeof undefined) && (dataError)) {
                                    swal("Cancelled", dataError, "error");
                                } else {
                                    swal("Deleted!", "This data has been deleted!", "success")
                                }
                                preLoaderHide(that);
                                loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);
                                if(callBack) {
                                    callBackFunction(callBack, [data]);
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            swal({
                                title: "Opps!!",
                                text: "Seems you couldn't submit form for a longtime. Please refresh your form & try again",
                                type: "error",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok!",
                                closeOnConfirm: false
                            },
                            function(){
                                var $parPanel = that.parents('.panel');
                                $parPanel.find(".panel-refresh").trigger("click");
                                swal("Your form has refreshed");
                            });
                        }
                    });
                });
            })//Go Button
            .on('click', ".go-btn", function(e) {
                e.preventDefault();
                var goUrl = $(this).attr('url');
                if (typeof goUrl===typeof undefined || goUrl===false) { alert('Url undefined.') }
                else {
                    var callBack = $(this).attr('callback');
                    var data = $(this).attr('data');
                    var refreshUrl = (typeof data===typeof undefined || data===false) ? goUrl : goUrl+'?data='+data;
                    var data = (typeof data===typeof undefined || data===false) ? {} : {data: data};
                    data['takeContent'] = 1;
                    that.removeAttr('refresh-url').attr('refresh-url', refreshUrl);
                    if(typeof callBack!==typeof undefined && callBack!==false) {
                        that.removeAttr('refresh-callback').attr('refresh-callback', callBack);
                    }
                    that.removeAttr('refresh-back').attr('refresh-back', 'false');
                    preLoader(that);
                    $.ajax({
                        url: appUrl.getSiteAction('/'+goUrl),
                        data: data,
                        type: 'GET',
                        dataType: "html",
                        success: function(data) {
                            if(parseInt(data)===0) {
                                redirectLoginPage();
                            } else {
                                that.find(".data-list").html(data);
                                if (that.find(".data-list .form-load").length>0) {
                                    that.find(".panel-title").first().html(that.find(".data-list form[paneltitle]").attr('paneltitle'));
                                    that.find(".data-list form[paneltitle]").removeAttr('paneltitle');
                                    loadForm(that, goUrl);
                                }
                                if(typeof callBack!==typeof undefined && callBack!==false) {
                                    callBackFunction(callBack);
                                }
                                preLoaderHide(that);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            swal("Cancelled", errorThrown, "error");
                        },
                        async: false
                    });
                }
            })//Back to List
            .on('click', ".back-btn", function(e) {
                e.preventDefault();
                that.removeAttr('refresh-url').attr('refresh-url', loadUrl);
                if(typeof mainCallBack!==typeof undefined && mainCallBack!==false) {
                    that.removeAttr('refresh-callback').attr('refresh-callback', mainCallBack);
                } else {
                    that.removeAttr('refresh-callback');
                }
                that.removeAttr('refresh-back');
                that.find(".panel-title").first().html(panelTitle);
                loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission, mainCallBack);
            });
            //Show Details
            selector.on('click', ".show-details", function(e) {
                e.preventDefault();
                var data = ($(this).attr('data')) ? $(this).attr('data') : false;
                var title = ($(this).attr('title')) ? $(this).attr('title') : $(this).html();
                var modalSize = ($(this).attr('modal-size')) ? $(this).attr('modal-size') : "";
                var detailsUrl = $(this).attr("url");
                showDetails(title, detailsUrl, data, modalSize);
            })//Sequence Forward
            .on('click', ".seq-forward", function(e) {
                e.preventDefault();
                var seqForwardUrl = $(this).attr('url');
                var mainSelector = $(this).attr('main-selector');
                var cloneSelector = $(this).attr('clone-selector');
                var seqDataPrefix = $(this).attr('data-prefix');
                var panelTitle = ($(this).attr('panel-title')) ? $(this).attr('panel-title') : false;
                if (typeof seqForwardUrl===typeof undefined || seqForwardUrl=='') { alert('Url undefined.') }
                else if (typeof mainSelector===typeof undefined || mainSelector=='') { alert('Selector undefined.') }
                else if (typeof cloneSelector===typeof undefined || cloneSelector=='') { alert('Selector undefined.') }
                else {
                    if(typeof seqDataPrefix!==typeof undefined) {
                        $("#"+cloneSelector).find(".panel").attr('data-prefix', seqDataPrefix);
                    } else {
                        var seqDataPrefix = $("#"+cloneSelector).find(".panel").attr('data-prefix');
                        seqDataPrefix = (typeof seqDataPrefix===typeof undefined) ? "" : seqDataPrefix;
                    }
                    var seqPaginate = "seq-paginate";
                    var inputHtml = '<input panel-title="'+that.find(".panel-title").html()+'" data-prefix="'+dataPrefix+'" main-selector="'+mainSelector+'" clone-selector="'+cloneSelector+'" class="'+seqPaginate+'" type="hidden" value="'+that.attr('refresh-url');
                    inputHtml += '">';
                    $(".seqPaginate-pMeter").append(inputHtml);
                    urlParameterDestroy(seqDataPrefix);
                    if(urlParameterManualPermission) { urlGenerate(url); }

                    $("#"+mainSelector).html($("#"+cloneSelector).html());
                    if(panelTitle) { $("#"+mainSelector).find(".panel-title").first().html(panelTitle); }
                    $("#"+mainSelector).find(".panel").attr("load-url", seqForwardUrl);
                    loadDataTable($("#"+mainSelector).find(".panel"), url, urlParameterManualPermission);
                }
            })//Sequence Back
            .on('click', ".seq-back", function(e) {
                e.preventDefault();
                var seqPaginate = "seq-paginate";
                var $seqBackUrl = $(".seqPaginate-pMeter").find("."+seqPaginate).last();
                var seqBackUrl = ($seqBackUrl.length>0) ? $seqBackUrl.val() : false;
                if (typeof seqBackUrl===typeof undefined || seqBackUrl===false  || seqBackUrl=='') { alert('Url undefined.') }
                else {
                    var panelTitle = ($seqBackUrl.attr('panel-title')) ? $seqBackUrl.attr('panel-title') : false;
                    var seqDataPrefix = $seqBackUrl.attr('data-prefix');
                    var mainSelector = $seqBackUrl.attr('main-selector');
                    var cloneSelector = $seqBackUrl.attr('clone-selector');
                    if (typeof mainSelector===typeof undefined || mainSelector=='') { alert('Selector undefined.') }
                    else if (typeof cloneSelector===typeof undefined || cloneSelector=='') { alert('Selector undefined.') }
                    else {
                        $seqBackUrl.remove();
                        urlParameterDestroy(dataPrefix);
                        if(urlParameterManualPermission) { urlGenerate(url); }

                        $("#"+mainSelector).html($("#"+cloneSelector).html());
                        preLoader($("#"+mainSelector));
                        if(panelTitle) { $("#"+mainSelector).find(".panel-title").first().html(panelTitle); }
                        if(seqDataPrefix) { $("#"+mainSelector).find(".panel").attr("data-prefix", seqDataPrefix); }
                        $("#"+mainSelector).find(".panel").attr("load-url", seqBackUrl);
                        loadDataTable($("#"+mainSelector).find(".panel"), url, urlParameterManualPermission);
                    }
                }
            });
        if(autoLoad) {
            loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);
        } else {
            loadAjaxPaginateData(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);
        }
    } else { alert("loadUrl is not defined."); }
}

function loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission, callBack){
    if (typeof urlParameterManualPermission===typeof undefined || urlParameterManualPermission==="") { urlParameterManualPermission=true; }
    preLoader(that);
    $.ajax({
        mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
        url: appUrl.getSiteAction('/'+loadUrl),
        data: inputArray,
        type: 'GET',
        dataType: "html",
        success: function(data) {
            if(parseInt(data)===0) {
                redirectLoginPage();
            } else {
                that.find(".data-list").html(data);
                loadAjaxPaginateData(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);

                //Sequence Paginate
                var seqBackPermission = that.attr('seq-back');
                if(typeof seqBackPermission===typeof undefined || seqBackPermission=="true") {
                    var seqPaginate = "seq-paginate";
                    var $seqBackUrl = $(".seqPaginate-pMeter").find("."+seqPaginate);
                    if($seqBackUrl.length>0) { that.find(".datatables_header>div:last").append('<button style="margin-right:12px;" type="button" class="seq-back btn btn-default pull-right btn-sm"><i class="glyphicon glyphicon-hand-left mr5"></i>Back</button>'); }
                }

                if(typeof callBack!==typeof undefined && callBack!==false) {
                    callBackFunction(callBack);
                }
                preLoaderHide(that);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            swal("Cancelled", errorThrown, "error");
        },
        dataType: "html",
        async: false
    });
    return false;
}

function loadAjaxPaginateData(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission) {
    scolling(that.find(".data-list"));
    //Data Search
    that.find(".data-search").each(function() {
        var eventVal = $(this).attr("event");
        var valueFrom = $(this).attr("valueFrom");
        $(this).removeAttr("event").removeAttr("valueFrom");
        var event=(eventVal=="enter")?"keydown":eventVal;
        $(this).on(event, function(e) {
            if((eventVal!="enter") || (eventVal=="enter" && e.keyCode == 13)) {
                if(valueFrom) {
                    var valueFromAr = valueFrom.split(',');
                    for(var i=0; i<valueFromAr.length; i++) {
                        var valueFrom_s = valueFromAr[i];
                        valueFrom_s = valueFrom_s.trim();

                        var searchName = that.find(valueFrom_s).attr("name");
                        var searchValue = that.find(valueFrom_s).val();
                        var parameter = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+searchName : searchName;
                        inputArray[searchName] = searchValue;
                        var urlHash = urlParameterSet(parameter, searchValue);
                    }
                } else {
                    var searchName = $(this).attr("name");
                    var searchValue = $(this).val();
                    var parameter = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+searchName : searchName;
                    inputArray[searchName] = searchValue;
                    var urlHash = urlParameterSet(parameter, searchValue);
                }

                inputArray["page"] = "";
                if(urlHash) { if(urlParameterManualPermission) { urlGenerate(url); } }
                loadAjaxPaginateContent(that, url, loadUrl, inputArray, dataPrefix, urlParameterManualPermission);
                e.preventDefault();
            }
        });
    });
    //AscDesc
    var parameterAsc = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+"asc" : "asc";
    var parameterDesc = (dataPrefix) ? dataPrefix+urlSeparatorDataTable+"desc" : "desc";
    var parameterAsc = $(".url-pMeter").find("input[id='"+parameterAsc+"']");
    var parameterDesc = $(".url-pMeter").find("input[id='"+parameterDesc+"']");
    var curAsc = (parameterAsc.length>0) ? parameterAsc.val() : "";
    var curDesc = (parameterDesc.length>0) ? parameterDesc.val() : "";

    that.find("table th[data]").each(function() {
        var ascDescData = $(this).attr("data");
        if(ascDescData) {
            if(ascDescData==curAsc) { $(this).addClass("sorting_asc"); }
            else if(ascDescData==curDesc) { $(this).addClass("sorting_desc"); }
            else { $(this).addClass("sorting"); }
            $(this).addClass("data-sorting");
        }
    });
}

function loadDataListForm(that, url, data, viewType, modalData, backPermit, callBack, extraParameter) {

    if(viewType=='modal') {
        var title = modalData.title;
        var modalSize = modalData.modalSize;
        data = modalData.data;
        data = (typeof data!==typeof undefined && data!=="" && data!==false) ? data : {};

		if(typeof extraParameter!==typeof undefined && typeof extraParameter.select2AddSet!==typeof undefined) {
			var advSearchClose = extraParameter.select2AddSet.advSearchClose;
			if(typeof advSearchClose!==typeof undefined && advSearchClose) {
				$("#advance_search_modal").parents(".modal").modal('hide');
			}
		}

        bootbox.dialog({
            message: '<div id="add-details"><div class="text-center"><img src="'+appUrl.baseUrl('/public/img/loaders/horizontal/006.gif')+'" /></div></div>',
            title: title,
            size: modalSize,
            buttons: {
                close: {
                    label: "Close",
                    className: "btn-default"
                },
				success: {
					label: "Save",
                    className: "btn-success",
					"callback": function() {
                        var $lastModal = $(".modal #add-details").last();
						$lastModal.find("button[data-bb-handler='success']").addClass('disabled').attr('disabled', 'disabled');
                        $lastModal.find("form").submit();
						return false;

					}

				}
            }
        }).on('hidden.bs.modal', function(){modalScroll()});
    } else if(viewType=='inPage') {
        preLoader(that);
    }

    $.ajax({
        mimeType: 'text/html; charset=utf-8',
        url: appUrl.getSiteAction('/'+url),
        data: data,
        type: 'GET',
        dataType: "html",
        success: function(data) {
            if(parseInt(data)===0) {
                redirectLoginPage();
            } else {
                if(viewType=='modal') {
                    var $lastModal = $('.modal').last();
					extraParameter['viewType'] = 'modal';
                    var modalHeight = $lastModal.find('.modal-content').height();
                    $lastModal.find('#add-details').html(data);
                    centerModal(modalHeight);
                    loadForm($lastModal, url, extraParameter);
                } else if(viewType=='inPage') {
                    that.find(".data-list").html(data);
                    that.find(".panel-title").first().html(that.find(".data-list form[paneltitle]").attr('paneltitle'));
                    that.find(".data-list form[paneltitle]").removeAttr('paneltitle');
                    if(backPermit) { that.find("button:last").parent().append('<button type="button" class="back-btn btn btn-default ml15">Back to List</button>'); }
                    loadForm(that, url);
                }

                if(typeof callBack!==typeof undefined && callBack!==false) {
                    callBackFunction(callBack);
                }

                if(viewType=='inPage') {
                    preLoaderHide(that);
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal("Cancelled", errorThrown, "error");
        },
        async: false
    });
}

function loadForm(preThat, url, extraParameter) {
    var that = (preThat) ? preThat.find('.form-load') : $(".form-load");
    that.each(function(){
        var type = ($(this).attr('type')) ? $(this).attr('type') : false;
        var callBack = ($(this).attr('callback')) ? $(this).attr('callback') : false;
        if(type) {
            $(this).removeAttr('type').removeAttr('callback');

            $(this).find('input[type=submit]').attr('data-loading-text', 'Loading...').attr('autocomplete', 'off');
            $(this).find('button[type=submit]').attr('data-loading-text', 'Loading...').attr('autocomplete', 'off');

            if($(this).find('.file-upload').length>0) {
                $(this).find('.file-upload').each(function(){
                    fileUpload($(this));
                });
            }

			if($(this).find('.advance-search').length>0) {
                $(this).find('.advance-search').each(function(){
                    advanceSearch($(this));
                });
            }

            var that = $(this);
            formSetup(that, type, callBack, url, extraParameter);
        } else { alert("type is not defined."); }
    });
}

function validationTooltip(that, direction){
    that.on('err.field.fv', function(e, data) {
        // Get the tooltip
        var $parent = data.element.parents('.form-group'),
            $icon   = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]'),
            title   = $icon.data('bs.tooltip').getTitle();

        if(that.find('[name="'+data.field+'"]').attr("vicon")) {
            var vicon = parseInt($('#'+selector).find('[name="'+data.field+'"]').attr("vicon"));
            if(vicon==0) { $icon.removeClass('glyphicon-remove'); }
            else if(vicon==2) { $icon.css('right', '-15px'); }
        }
        // Destroy the old tooltip and create a new one positioned to the right
        $icon.tooltip('destroy').tooltip({
            html: true,
            placement: direction,
            title: title,
            container: 'body'
        });
    });

    that.on('success.field.fv', function(e, data) {
        // Get the tooltip
        var $parent = data.element.parents('.form-group'),
            $icon   = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');

        if(that.find('[name="'+data.field+'"]').attr("vicon")) {
            var vicon = parseInt($('#'+selector).find('[name="'+data.field+'"]').attr("vicon"));
            if(vicon==0) { $icon.removeClass('glyphicon-ok'); }
            else if(vicon==2) { $icon.css('right', '-15px'); }
        }
    });
}

function formSetup(that, type, callBack, url, extraParameter) {
    var method = (type=='update') ? 'PUT' : 'POST';
    that.formValidation({
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    }).on('success.field.fv', function(e)
    {
        if(typeof extraParameter!==typeof undefined && extraParameter.viewType=='modal') {
            that.parents(".modal").find("button[data-bb-handler='success']").removeClass('disabled').removeAttr('disabled');
        }
    }).on('err.field.fv', function(e)
    {
        if(typeof extraParameter!==typeof undefined && extraParameter.viewType=='modal') {
            that.parents(".modal").find("button[data-bb-handler='success']").addClass('disabled').attr('disabled', 'disabled');
        }
    }).on('success.form.fv', function(e)
    {
        e.preventDefault();
        if(typeof extraParameter!==typeof undefined && extraParameter.viewType=='modal') {
            that.parents(".modal").find("button[data-bb-handler='success']").addClass('disabled').attr('disabled', 'disabled');
            var $btn1 = that.parents(".modal").find("button[data-bb-handler='success']").button('loading');
        } else {
            var $btn1 = $(this).find('input[type=submit]').button('loading');
            var $btn2 = $(this).find('button[type=submit]').button('loading');
        }
        var postData = $(this).serializeArray();
        var formURL_attr = $(this).attr("action");
        if(typeof formURL_attr!==typeof undefined && formURL_attr!==false) {
            var formURL = formURL_attr;
        } else {
            var urlSplit = url.split("/create");
            var mainUrl = urlSplit[0];
            urlSplit = mainUrl.split("/edit");
            mainUrl = urlSplit[0];
            var formURL = appUrl.getSiteAction('/'+mainUrl);
        }

        $.ajax({
            url: formURL,
            type: method,
            data: postData,
            dataType: "json",
            success: function(data) {
                if(parseInt(data.auth)===0) {
                    redirectLoginPage();
                } else {
                    if(data.msgType=='success') {
                        if(data.output) {
                            that.find('.form-output').remove();
                            var responseOutput = jQuery.parseJSON(data.output);
                            $.each(responseOutput, function(key, value){
                                that.prepend('<input type="hidden" class="form-output" name="'+key+'" value="'+value+'"/>');
                            });
                        }

                        if(type=='create') {
                            that.trigger('reset').data('formValidation').resetForm();
                            //Select2 Reset
                            if(that.find('.select2-container').length>0) {
                                that.find('.select2-container').each(function(){
                                    var refreshValue = $(this).parent().find($('select')).attr('refresh-value');
                                    if(typeof refreshValue===typeof undefined) { refreshValue = ''; }
                                    $(this).parent().find($('select')).select2("val", refreshValue);
                                });
                            }

                            if(that.find('.file-upload').length>0) {
                                that.find('.file-upload').each(function(){
                                    $(this).find($('.file-remove')).trigger('click');
                                });
                            }
                        }

						if(typeof extraParameter!==typeof undefined && extraParameter.viewType=='modal' && typeof extraParameter.returnSelector!==typeof undefined) {
							if(type=='update') {
								bootbox.hideAll();
							}
							$.ajax({
								url: appUrl.getSiteAction('/'+extraParameter.returnUrl),
								type: "GET",
								dataType: "html",
								success: function(data) {
									extraParameter.returnSelector.html(data);
                                    var inputClass = (extraParameter.returnDataPrefix) ? extraParameter.returnDataPrefix + "-data-input" : "data-input";
                                        var returnInputArray = {};
                                        $("." + inputClass).each(function () {
                                            var input = $(this).attr("id");
                                            var input = input.split(urlSeparatorDataTable);
                                            var input = (input.length > 1) ? input[1] : input;
                                            returnInputArray[input] = $(this).val();
                                        });
                                        loadAjaxPaginateData(extraParameter.returnSelector.parents(".data-table").first(), url, extraParameter.returnUrl, returnInputArray, extraParameter.returnDataPrefix, extraParameter.returnUrlParameterPermission);
								},
								error: function(jqXHR, textStatus, errorThrown) {
									swal("Cancelled", errorThrown, "error");
								}
							});
						}

                        if(callBack) {
                            callBackFunction(callBack, [data]);
                        }

                        if(typeof extraParameter!==typeof undefined && typeof extraParameter.select2AddSet!==typeof undefined) {
							var select2Value = data.value;
							var select2Text = data.text;
							if(typeof select2Value!==typeof undefined && select2Value!==false) {
								var $select2 = extraParameter.select2AddSet.select2;
								var $select2Option = $select2.find("option[value="+select2Value+"]");
								var multipleChk = $select2.attr("multiple");
								if($select2Option.length>0) {
									if(typeof multipleChk===typeof undefined) { $select2.find("option[selected]").removeAttr("selected"); }
									$select2Option.attr("selected", "selected");
								} else {
									if(typeof multipleChk===typeof undefined) { $select2.find("option[selected]").removeAttr("selected"); }
									$select2.append('<option selected="selected" value="'+select2Value+'">'+select2Text+'</option>');
								}
								$select2.trigger("change");
								if(typeof extraParameter.select2CallBack!==typeof undefined) {
									callBackFunction(extraParameter.select2CallBack, [data]);
								}
							} else {
								alert("Value is not defined");
							}
							$(".modal #add-details").last().parents(".modal").modal('hide');
                        }
                    }

                    if(data.msgType=='success') {
                        $.gritter.add({
                            title: "Done !!!",
                            text: data.messege,
                            time: "",
                            close_icon: "entypo-icon-cancel s12",
                            icon: "icomoon-icon-checkmark-3",
                            class_name: "success-notice"
                        });
                    } else if(data.msgType=='danger') {
                        $.gritter.add({
                            title: "Sorry !!!",
                            text: data.messege,
                            time: "",
                            close_icon: "entypo-icon-cancel s12",
                            icon: "icomoon-icon-close",
                            class_name: "error-notice"
                        });
                    } else if(data.msgType=='validationError') {
                        that.parents('.panel-body').prepend('<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.messege+'</div>');
                    }
					if(typeof extraParameter!==typeof undefined && extraParameter.viewType=='modal') {
						$btn1.button('reset');
                        that.parents(".modal").find("button[data-bb-handler='success']").removeClass('disabled').removeAttr('disabled');
					} else {
						$btn1.button('reset');
						$btn2.button('reset');
					}
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal({
                    title: "Opps!!",
                    text: "Seems you couldn't submit form for a longtime. Please refresh your form & try again",
                    type: "error",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Ok!",
                    closeOnConfirm: false
                },
                function(){
                    var $parPanel = that.parents('.panel');
                    $parPanel.find(".panel-refresh").trigger("click");
                    swal("Your form has refreshed");
                });
            },
            async: false
        });
    });
}

function preLoader(that) {
    that.closest(".panel").waitMe({
        effect: "rotateplane",
        text: "",
        bg: "rgba(255,255,255,0.7)",
        color: "#616469"
    });
}

function preLoaderHide(that) {
    that.closest(".panel").waitMe("hide");
}

function callBackFunction(callBack, paraMeterArray) {
	if(typeof paraMeterArray===typeof undefined) { paraMeterArray = []; }
    if ('()'===callBack.substring(callBack.length-2)) {
        callBack = callBack.substring(0, callBack.length-2);
    }
    var ns      = callBack.split('.'),
        func    = ns.pop(),
        context = window;
    for (var i = 0; i < ns.length; i++) {
        context = context[ns[i]];
    }
    context[func].apply(null, paraMeterArray);
}

function breadCrumbCreator($selectedNav, output) {
    if($selectedNav.length>0) {
        var breadTitle = $selectedNav.find('span').html();
        var breadIcon = $selectedNav.find('i').not('.hasDrop').attr('class');

        var $parent = $selectedNav.parents('.hasSub').first();
        if($parent.length>0) {
            if(output=="") {
                output = '<li class="active">'+breadTitle+'</li>';
            } else {
                output = '<li><a style="text-decoration: none;" href="#">'+breadTitle+'</a> <span class="divider"><i class="s16 icomoon-icon-arrow-right-3"></i></span></li>'+output;
            }
            return breadCrumbCreator($parent, output);
        } else {
            if(output=="") {
                output = '<li class="active">'+breadTitle+'</li>';
            }
            var breadCrumb = '<li><a title="'+breadTitle+'" class="tip" href="#"><i class="'+breadIcon+'"></i></a> <span class="divider"><i class="s16 icomoon-icon-arrow-right-3"></i></span></li>';
            output = breadCrumb+output;
            return output;
        }
    } else {
        return '<li><span class="divider"><i class="s16 icomoon-icon-arrow-right-3"></i></span></li><li class="active">'+output+'</li>';
    }
}

//function img_upload(btn, img_v, inputID, img_type)
function fileUpload(selector)
{
    var inputName = selector.attr('input');
    var filePath = selector.attr('filepath');
    var extArray = '';
    if(selector.attr('ext')) {
        extArray = new Array();
        var extAr = selector.attr('ext');
        extAr = extAr.split(',');
        var j=0;
        for(var i=0; i<extAr.length; i++) { var extBl=extAr[i].replace(/\s+/g,''); if(extBl) {extArray[j]=extBl; j++;} }
    }
    var preFile = ''; if(selector.attr('prefile')) { preFile = selector.attr('prefile'); }
    var btnName = 'Upload'; if(selector.attr('btnname')) { btnName = selector.attr('btnname'); }
    var btnSide = ''; if(selector.attr('btnside')) { btnSide = ' <span style="font-size:12px;">'+selector.attr('btnside')+'</span>'; }
    var maxWidth = ''; if(selector.attr('maxwidth')) { maxWidth = ' style="max-width:'+selector.attr('maxwidth')+';"'; }
    var reqWidth = ''; if(selector.attr('reqwidth')) { reqWidth = selector.attr('reqwidth'); }
    var reqHeight = ''; if(selector.attr('reqheight')) { reqHeight = selector.attr('reqheight'); }

    var resizeWidth =selector.attr('resizewidth');
    var resizeHeight =selector.attr('resizeheight');





    // var imgData = {reqWidth:reqWidth, reqHeight:reqHeight};
    var btnId = inputName+'_btn';
    var imgViewId = inputName+'_view';
    var statusId = inputName+'_status';
    selector.removeAttr('input').removeAttr('filepath').removeAttr('ext').removeAttr('prefile').removeAttr('btnname').removeAttr('btnside').removeAttr('maxwidth').removeAttr('reqWidth').removeAttr('reqHeight').removeAttr('resizeWidth').removeAttr('resizeHeight')
        .html('<div id="'+imgViewId+'"></div><div class="status text-danger" id="'+statusId+'"></div><input name="'+inputName+'" type="hidden" value="'+preFile+'" />');
   


    if(preFile) {
        var file_name_array = preFile.split(".");
        var file_ext = file_name_array[1];
        if(file_ext == 'doc' || file_ext == 'docx') {
            selector.find($('#'+imgViewId)).html('<div class="single-item" style="width: 150px;"><div class="img"><img src="'+appUrl.baseUrl('/public/uploads/doc_icon.jpg')+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div></div>');
        } else if(file_ext == 'pdf'){
            selector.find($('#'+imgViewId)).html('<div class="single-item" style="width: 150px;"><div class="img"><img src="'+appUrl.baseUrl('/public/uploads/pdf_icon.jpg')+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div></div>');
        } else if(file_ext == 'html') {
            selector.find($('#'+imgViewId)).html('<div class="single-item" style="width: 150px;"><div class="img"><img src="'+appUrl.baseUrl('/public/uploads/html_icon.png')+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div></div>');
        } else {
            selector.find($('#'+imgViewId)).html('<div class="single-item"'+maxWidth+'><div class="img"><img src="'+appUrl.baseUrl('/'+filePath+'/'+preFile)+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div></div>');
        }

    } else {
        fileUploadBtnSet(selector, imgViewId, btnId, btnName, btnSide, inputName, statusId, filePath, extArray, maxWidth, reqWidth,reqHeight, resizeWidth, resizeHeight);
    }


    selector.on('click', '.file-remove', function() {
        var input=$('input[name='+inputName+']');
        var csrfToken=$('input[name="_token"]').val();
        var status=$('#'+statusId);
        if(!preFile || preFile!=input.val()) {
            selector.find($('#'+imgViewId)).html('<img src="'+appUrl.baseUrl('/public/img/loaders/horizontal/006.gif')+'" alt="" class="mt10" />');
            $.ajax({
                url: appUrl.baseUrl('/customFileUnlink'),
                type: "POST",
                data: {filePath: filePath, image:input.val(), _token: csrfToken}
            });
        }
        status.text("");
        input.val("");
        selector.find($('#'+imgViewId)).html('<button type="button" id="'+btnId+'" class="btn btn-large btn-default"><span>'+btnName+'</span></button>'+btnSide);
        fileUploadBtnSet(selector, imgViewId, btnId, btnName, btnSide, inputName, statusId, filePath, extArray, maxWidth, reqWidth,reqHeight,resizeWidth,resizeHeight);
    });

}

function fileUploadBtnSet(selector, imgViewId, btnId, btnName, btnSide, inputName, statusId, filePath, extArray, maxWidth, reqWidth,reqHeight,resizeWidth,resizeHeight) {

    var acceptExt = '';
    for(var i=0; i<extArray.length; i++) { acceptExt += '.'+extArray[i]+((extArray.length!=(i+1))?',':''); }
    selector.find($('#'+imgViewId)).html('<input type="file" class="filestyle" id="'+btnId+'">'+btnSide);
    $('#'+btnId).filestyle({
        input: !0,
        icon: !0,
        buttonBefore: !1,
        disabled: !1,
        size: "",
        buttonText: "Upload",
        buttonName: "btn-default",
        iconName: "fa fa-upload"
    });
    if(acceptExt!='') { $('#'+btnId).attr('accept', acceptExt); }

    $(function(){

        var img_view=$('#'+imgViewId);
        var input=$('input[name='+inputName+']');
        var csrfToken=$('input[name="_token"]').val();
        if(csrfToken=="") { alert("CSRF Token is missing."); return false; }
        var status=$('#'+statusId);

        $('#'+btnId).change(function () {
            var myfile = $(this).val();
            var extExist=0;
            if (myfile == '') {
                alert('Please enter file name and select file');
                return;
            } else {
                var filenameSlit = myfile.split(".");
                var ext = filenameSlit[filenameSlit.length-1];
                ext = ext.toLowerCase();

                if(extArray) { for(var i=0; i<extArray.length; i++) { if(ext==extArray[i]){extExist=1;} } }
                if(!ext || (extArray && extExist==0)) {
                    var extNames = '';
                    for(var i=0; i<extArray.length; i++) { if(extArray.length>1 && i==extArray.length-1) { extNames+=' or '; } else if(i>0) { extNames+=', '; } extNames+=extArray[i]; }
                    status.text('Only '+extNames+' files are allowed');
                    return;
                } else {
                    status.text('');
                    $("label[for='"+btnId+"']").parents().first().html($("label[for='"+btnId+"']").parents().first().html());
                    $("label[for='"+btnId+"']").append('ing...').addClass('disabled').attr('for', '');
                    img_view.append('<div class="progress"><div class="progress-bar progress-bar-success myprogress" role="progressbar" style="width:0%">0%</div></div>');


                  

                    var formData = new FormData();
                    formData.append('uploadfile', $(this)[0].files[0]);
                    formData.append('filePath', filePath);
                    formData.append('_token', csrfToken);
                    formData.append('reqWidth', reqWidth);
                    formData.append('reqHeight', reqHeight);
                    formData.append('resizeWidth',resizeWidth);
                    formData.append('resizeHeight',resizeHeight);


                    $.ajax({
                        url: appUrl.baseUrl('/customFileUpload'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        // this part is progress bar
                        xhr: function () {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function (evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    img_view.find('.myprogress').text(percentComplete + '%');
                                    img_view.find('.myprogress').css('width', percentComplete + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function (response) {

                            var file = filenameSlit[filenameSlit.length-2];
                            file = file.split("\\");
                            file = file[file.length-1];
                            response=response.split('~');
                            
                            //Add uploaded file to list
                            if(response[1]==="success"){

                                input.val(response[2]);
                                status.text('');
                                img_view.html('');
                                var file_name = response[2];
                                var file_name_array = file_name.split(".");
                                var file_ext = file_name_array[1];
                                var file_size = response[3];
                                if(file_ext == 'doc' || file_ext == 'docx') {
                                    img_view.html('<div class="single-item" style="width: 150px;"><div class="img"><img src="'+appUrl.baseUrl('/public/uploads/doc_icon.jpg')+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div> <input type="hidden" name="attachment_real_name" value="'+file+'"/> <input type="hidden" name="attachment_size" value="'+file_size+'"/></div>');
                                } else if(file_ext == 'pdf') {
                                    img_view.html('<div class="single-item" style="width: 150px;"><div class="img"><img src="'+appUrl.baseUrl('/public/uploads/pdf_icon.jpg')+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div></div> <input type="hidden" name="attachment_real_name" value="'+file+'"/> <input type="hidden" name="attachment_size" value="'+file_size+'"/>');
                                } else if(file_ext == 'html') {
                                    img_view.html('<div class="single-item" style="width: 150px;"><div class="img"><img src="'+appUrl.baseUrl('/public/uploads/html_icon.png')+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div></div> <input type="hidden" name="attachment_real_name" value="'+file+'"/> <input type="hidden" name="attachment_size" value="'+file_size+'"/>');
                                } else {
                                    img_view.html('<div class="single-item"'+maxWidth+'><div class="img"><img src="'+appUrl.baseUrl('/'+filePath+'/'+response[2])+'" class="img-overly" /><div class="overlay upload-img-overly"><h1 title="Remove" class="fa fa-times-circle file-remove hand"></h1></div></div></div> <input type="hidden" name="attachment_real_name" value="'+file+'"/> <input type="hidden" name="attachment_size" value="'+file_size+'"/>');
                                }
                            } else {
                                input.val("");
                                status.text(response[1]);
                                fileUploadBtnSet(selector, imgViewId, btnId, btnName, btnSide, inputName, statusId, filePath, extArray, maxWidth, reqWidth,reqHeight,resizeWidth,resizeHeight);
                            }
                        }
                    });
                }
            }
        });
    });
}

//Advance Search
function advanceSearch(selector) {
    selector.find(".adv-btn").click(function(){
        var searchUrl = (selector.attr('search-url')) ? selector.attr('search-url') : false;
        var searchTitle = (selector.attr('search-title')) ? selector.attr('search-title') : 'Advance Search';
        var searchCallBack = (selector.attr('search-callback')) ? selector.attr('search-callback') : false;

        if(searchUrl) {
            bootbox.dialog({
                message: '<div id="advance_search_modal" data-prefix="advSrcFrame" load-url="'+searchUrl+'" class="data-table"><div class="data-list"></div></div>',
                title: searchTitle,
                size: 'large',
                buttons: {
                    close: {
                        label: "Close",
                        className: "btn-default"
                    }
                }
            }).on('hidden.bs.modal', function(){modalScroll()});

            $(".modal").find(".data-table").on("click", ".set-search", function(){
                var searchValue = $(this).attr("value");
                var searchText = $(this).attr("text");
                if(typeof searchValue!==typeof undefined && searchValue!==false) {
                    var $searchSelect = selector.find("select.adv-search");
                    if($searchSelect.length>0) {
                        var $searchOption = $searchSelect.find("option[value="+searchValue+"]");
                        var multipleChk = $searchSelect.attr("multiple");
                        if($searchOption.length>0) {
                            if(typeof multipleChk===typeof undefined) { $searchSelect.find("option[selected]").removeAttr("selected"); }
                            $searchOption.attr("selected", "selected");
                        } else {
                            if(typeof multipleChk===typeof undefined) { $searchSelect.find("option[selected]").removeAttr("selected"); }
                            $searchSelect.append('<option selected="selected" value="'+searchValue+'">'+searchText+'</option>');
                        }
                        $searchSelect.trigger("change");
                    }
                    if(searchCallBack) {
                        var callBackData = {value: searchValue, text: searchText};
                        $(this).each(function() {
                            $.each(this.attributes, function() {
                                if(this.specified) {
                                    var attrName = this.name;
                                    attrName = attrName.split('adv-data-');
                                    if(attrName.length>1) {
                                        callBackData[attrName[1]] = this.value;
                                    }
                                }
                          });
                        });
                        callBackFunction(searchCallBack, [callBackData]);
                    }
                    $("#advance_search_modal").parents(".modal").modal('hide');
                } else {
                    alert("Search Value is not defined");
                }
            })
			//Add New
            .on('click', ".adv-add-btn", function(e) {
                e.preventDefault();
                var callBack = $(this).attr('callback');
                var addUrl = $(this).attr('url');
                var addBack = ($(this).attr('addBack') && $(this).attr('addBack')=='false') ? false : true;
                var viewType = ($(this).attr('view-type')) ? $(this).attr('view-type') : 'inPage';

                var addBackString = (addBack) ? 'true' : 'false';
                if (typeof addUrl===typeof undefined || addUrl===false) { var addUrl = url+'/create'; }

                var modalData = {};
                if(viewType=='modal') {
                    modalData['data'] = {takeContent:1};
                    if($(this).attr('data')) { modalData['data']['data'] = $(this).attr('data'); }
                    modalData['title'] = ($(this).attr('title')) ? $(this).attr('title') : $(this).html();
                    modalData['modalSize'] = ($(this).attr('modal-size')) ? $(this).attr('modal-size') : "";
					var addBack = false;
					var returnUrl = $(this).closest(".data-table").attr("refresh-url");
                }
				var extraParameter = {select2AddSet: {select2: selector.find("select.adv-search"), advSearchClose: true}};

                loadDataListForm($("#advance_search_modal").last(), addUrl, {takeContent:1}, viewType, modalData, addBack, callBack, extraParameter);
			});

            $(".advSrcFrame-data-input").remove();
            loadDataTable($("#advance_search_modal"), '', false);
        } else { alert("searchUrl is not defined."); }
    });

    selector.find(".adv-reset").click(function(){
        var $searchSelect = selector.find("select.adv-search");
        var $searchOption = $searchSelect.find("option[value='']");
        var multipleChk = $searchSelect.attr("multiple");
        if($searchOption.length>0) {
            $searchSelect.find("option[selected]").removeAttr("selected");
            if(typeof multipleChk===typeof undefined) { $searchOption.attr("selected", "selected"); }
        } else {
            $searchSelect.find("option[selected]").removeAttr("selected");
            if(typeof multipleChk===typeof undefined) { $searchSelect.prepend('<option selected="selected" value="">Select</option>'); }
        }
        $searchSelect.trigger("change");
        $(".modal").modal('hide');
    });
}

//Select2 Add
function select2Add(selector) {
	var select2AddUrl = selector.attr('add-url');
	if(typeof select2AddUrl===typeof undefined) { alert('Add URL is undefined'); }
	else {
		var title = selector.attr('add-title');
		if(typeof title===typeof undefined) { var title = selector.parents('.form-group').first().find('label').html(); }
		title = (title == "" || typeof title===typeof undefined) ? "Add New" : "Add "+title;

		var modalSize = (selector.attr('add-modal-size')) ? selector.attr('add-modal-size') : "";
		var select2CallBack = (selector.attr('callback')) ? selector.attr('callback') : "";

		bootbox.dialog({
			message: '<div id="add-details"><div class="text-center"><img src="'+appUrl.baseUrl('/public/img/loaders/horizontal/006.gif')+'" /></div></div>',
			title: title,
			size: modalSize,
			buttons: {
				close: {
					label: "Close",
					className: "btn-default"
				},
				success: {
					label: "Save",
					className: "btn-success",
					"callback": function() {
						$(".modal").last().find("button[data-bb-handler='success']").addClass('disabled').attr('disabled', 'disabled');
						$(".modal #add-details").last().find("form").submit();
						return false;
					}
				}
			}
		}).on('hidden.bs.modal', function(){modalScroll()});

		$.ajax({
			mimeType: 'text/html; charset=utf-8',
			url: appUrl.getSiteAction('/'+select2AddUrl),
			data: {takeContent:1},
			type: 'GET',
			dataType: "html",
			success: function(data) {
				if(parseInt(data)===0) {
					redirectLoginPage();
				} else {
					var modalHeight = $('.modal').last().find(".modal-content").height();
					$(".modal").last().find('#add-details').html(data);
					centerModal(modalHeight);
					var extParameter = {viewType: 'modal', select2AddSet: {select2: selector}};
					if(select2CallBack!="") { extParameter['select2CallBack'] = select2CallBack; }
					loadForm($(".modal").last(), select2AddUrl, extParameter);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				swal("Cancelled", errorThrown, "error");
			},
			async: false
		});
	}
}

//Details Show
function showDetails(title, detailsUrl, data, modalSize) {
    var dataArray = (typeof data!==typeof undefined && data!=="" && data!==false) ? {data: data} : {};
    bootbox.dialog({
        message: '<div id="event-details"><div class="text-center"><img src="'+appUrl.baseUrl('/public/img/loaders/horizontal/006.gif')+'" /></div></div>',
        title: title,
        size: modalSize,
        buttons: {
            close: {
                label: "Close",
                className: "btn-default"
            }
        }
    }).on('hidden.bs.modal', function(){modalScroll()});
    $.ajax({
        url: appUrl.getSiteAction('/'+detailsUrl),
        data: dataArray,
        type: 'GET',
        dataType: "html",
        success: function(data) {
            if(parseInt(data)===0) {
                redirectLoginPage();
            } else {
                var modalHeight = $('.modal').last().find(".modal-content").height();
                $(".modal").last().find('#event-details').html(data);
                centerModal(modalHeight);
            }
        }
    });
}

//PopOver Load
function loadAjaxPopover($selector) {
	if(!($selector.attr('data-title'))) { $selector.attr('data-title', $selector.html()); }
	var isLoad = ($selector.attr('data-content')) ? false : true;
	if(isLoad) {
		var popoverUrl = ($selector.attr('data-url')) ? $selector.attr('data-url') : false;
		if(popoverUrl) {
			$selector.removeAttr('data-url');
			$selector.attr('load-popover', '1');
			$selector.popover({
				content: '<div class="popover-loader">Loading...</div>',
				placement: 'right',
				html: true,
				trigger: 'manual'
			}).on("mouseenter", function () {
				var _this = this;
				$(this).popover("show");
				$(".popover").on("mouseleave", function () {
					$(_this).popover('hide');
				});
			}).on("mouseleave", function () {
				var _this = this;
				setTimeout(function () {
					if (!$(".popover:hover").length) {
						$(_this).popover("hide");
					}
				}, 200);
			});

			$selector.on('show.bs.popover', function () {
				if($selector.attr('load-popover')) {
					$.ajax({
						url: appUrl.getSiteAction('/'+popoverUrl),
						dataType: 'html',
						success: function(html) {
							$selector.popover('destroy');
							setTimeout(function() {
								$selector.removeAttr('load-popover');
								var $popover = $selector.popover({
									content: html,
									placement: 'right',
									html: true,
									trigger: 'manual'
								}).on("mouseenter", function () {
									var _this = this;
									$(this).popover("show");
									$(".popover").on("mouseleave", function () {
										$(_this).popover('hide');
									});
								}).on("mouseleave", function () {
									var _this = this;
									setTimeout(function () {
										if (!$(".popover:hover").length) {
											$(_this).popover("hide");
										}
									}, 200);
								});
                                if ($selector.is(':hover')) {
                                    $popover.popover('show');
									$(".popover").on("mouseleave", function () {
										$popover.popover('hide');
									});
                                }
							},200);
						}
					});
				}
			})
		}
	} else {
		$selector.popover();
	}
}


function centerModal(modalHeight) {
    var $lastModal = $('.modal').last();
    var $modalHeader = $lastModal.find(".modal-header");
    $modalHeader = $modalHeader.first();
    var $modalFooter = $lastModal.find(".modal-footer");
    $modalFooter = $modalFooter.last();
    var b = $(window).height() - 60,
        c = $modalHeader.outerHeight() || 2,
        d = $modalFooter.outerHeight() || 2;
    var isCenter = (b>=$lastModal.find(".modal-content").height()) ? true : false;

    $lastModal.find(".modal-content").css({
        "max-height": function() {
            return b
        }
    }), $lastModal.find(".modal-body").css({
        "max-height": function() {
            return b - (c + d)
        }
    });

    if(isCenter) {
        modalHeight = $lastModal.find(".modal-content").height()-modalHeight;
        if(modalHeight>0) {
            var curTopMatgin = parseInt($lastModal.find(".modal-dialog").css("margin-top"));
            var topMargin = curTopMatgin-(modalHeight/2);
            if(curTopMatgin<=0 || topMargin>0) {
                $lastModal.find(".modal-dialog").css({
                    "margin-top": function() {
                        return topMargin
                    }
                });
            }
        }
    }
}

function accordions(b) {
    b.collapse(), accPutIcon = function() {
        b.each(function() {
            accExp = $(this).find(".panel-collapse.in"), accExp.prev(".panel-heading").addClass("content-in").find("a.accordion-toggle").append('<i class="icomoon-icon-minus s12"></i>'), accNor = $(this).find(".panel-collapse").not(".panel-collapse.in"), accNor.prev(".panel-heading").find("a.accordion-toggle").append('<i class="icomoon-icon-plus s12"></i>')
        })
    }, accUpdIcon = function() {
        b.each(function() {
            accExp = $(this).find(".panel-collapse.in"), accExp.prev(".panel-heading").find("i").remove(), accExp.prev(".panel-heading").addClass("content-in").find("a.accordion-toggle").append('<i class="icomoon-icon-minus s12"></i>'), accNor = $(this).find(".panel-collapse").not(".panel-collapse.in"), accNor.prev(".panel-heading").find("i").remove(), accNor.prev(".panel-heading").removeClass("content-in").find("a.accordion-toggle").append('<i class="icomoon-icon-plus s12"></i>')
        })
    }, accPutIcon(), $(".accordion").on("shown.bs.collapse", function() {
        accUpdIcon()
    }).on("hidden.bs.collapse", function() {
        accUpdIcon()
    })
}

function modalScroll() {
	if($('.modal:visible').length) {
		var bodyCss = $('body').css('padding-right');
		if(!($('body').hasClass('modal-open')) && (typeof bodyCss!==typeof undefined && bodyCss!='0px')) { $('body').addClass('modal-open'); }
	} else {
		$('body').css('padding-right', '');
	}
}

function redirectLoginPage() {
    swal({
        title: "Sorry!!",
        text: "You have logged out.",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Login Now!",
        closeOnConfirm: false
    },
    function(){
        location.replace(appUrl.loginPage());
    });
}

function menuActive(ajax_url) {
    $menu = $(".mainnav").find("a[href='"+ajax_url+"']");
    if($menu.length>0 && !($menu.hasClass("active"))) {
        $(".mainnav").find(".active").removeClass("active");
        $menu.addClass("active");
        $menu.parents(".sub").addClass("show").css("display", "block");
        $menu.parents(".hasSub").not(":last").addClass("highlight-menu");
        $menu.parents(".hasSub").find(".notExpand:first").removeClass("notExpand").addClass("expand");
    }
}

//// For Page Loading //////////
$(window).load(function() {
    masterCallForward = ($('#ajax-content').attr("callforward")) ? $('#ajax-content').attr("callforward") : false;
    $('#ajax-content').removeAttr("callforward");
    var ajax_url = location.hash.replace(/^#/, '');
    if (ajax_url.length < 1 || ajax_url == '#') {
        ajax_url = appUrl.defaultPage();
        window.location.hash = ajax_url;
    }
    var $selectedNav = $('.mainnav').find('.ajax-link[href="'+ajax_url+'"]');
    var menuActiveLink = ajax_url;
    if($selectedNav.length<=0) {
        var ajax_url_split = ajax_url.split("?");
        var menuActiveLink = ajax_url_split[0];
        var $selectedNav = $('.mainnav').find('.ajax-link[href="'+menuActiveLink+'"]');

        if($selectedNav.length<=0) {
            var ajax_url_split = menuActiveLink.split("/");
            ajax_url_split.pop();
            var menuActiveLink = ajax_url_split.join('/');;
        }
    }
    //Menu Active
    menuActive(menuActiveLink);
    //Url Load
    PreLoadEvent();
    LoadAjaxContent(ajax_url, menuActiveLink, masterCallForward);
});

$(document).on('click', '.ajax-link', function(e) {
    //Menu Active
    $(".mainnav").find(".active").removeClass("active");
    $(this).addClass("active");

    //Url Load
    var url = $(this).attr('href');
    url = url.substring(url.indexOf('#') + 1);
    var menuActiveLink = ($(this).attr('menu-active')) ? $(this).attr('menu-active') : false;
    if(!menuActiveLink) { menuActiveLink = url; }

    if (url != '#' && url != '') {
        window.location.hash = url;
        menuActive(menuActiveLink);
        LoadAjaxContent(url, menuActiveLink, masterCallForward);
    }
    e.preventDefault();
});

//Menu Expand
$(".mainnav").on('click', '.hasSub', function(e) {
    if($(this).parents(".hasSub").length>0 && $(this).hasClass("highlight-menu")) {
        $(this).parent().find(".hasSub").not(this).find(".expand").removeClass("expand").addClass("notExpand");
        $(this).parent().find(".hasSub").not(this).find(".show").removeClass("show").removeAttr("style");
    }
});

function LoadAjaxContent(url, menuActiveLink, callForward, callback) {
    $('#ajax-content').html('<div class="pace-loader"><div></div></div>');
    if(callForward) {
        callBackFunction(callForward);
    }
    var $selectedNav = $('.mainnav').find('.ajax-link[href="'+menuActiveLink+'"]');
    if($selectedNav.length>0) {
        var pageTitle = $selectedNav.find('span').html();
        var breadCrumb = breadCrumbCreator($selectedNav, "");
    } else {
        if(menuActiveLink=="") { menuActiveLink = url; }
        menuActiveLink = menuActiveLink.split('?');
        menuActiveLink = menuActiveLink[0];
        menuActiveLink = menuActiveLink.split('/');
        menuActiveLink = menuActiveLink[menuActiveLink.length-1];
        var pageTitle = "", titleArray = menuActiveLink.split('-');
        $.each(titleArray, function(index, title1){
            var titleSubArray = title1.split('_');
            $.each(titleSubArray, function(index, title2){
                if(title2!="") {
                    var f = title2.charAt(0).toUpperCase();
                    pageTitle += f + title2.substr(1);
                    pageTitle += (pageTitle!="") ? " " : "";
                }
            });
        });
        var breadCrumb = breadCrumbCreator("", pageTitle);
    }
    $(".heading>h3").html(pageTitle);
    $("title").html(pageTitle+' | '+masterTitle);
    $(".breadcrumb").find('li').not('li:first').remove();
    $(".breadcrumb").append(breadCrumb);
    $.ajax({
        mimeType: 'text/html; charset=utf-8',
        url: appUrl.getSiteAction('/'+url),
        type: 'GET',
        dataType: "html",
        success: function(data) {
            if(parseInt(data)===0) {
                redirectLoginPage();
            } else {
                $('#ajax-content').html(data);
                afterLoading(url);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal("Cancelled", errorThrown, "error");
        },
        complete: function() {
            if (typeof(callback) == 'function') {
                callback();
            }
        },
	async: false
    });
}
//// For Page Loading End //////////
