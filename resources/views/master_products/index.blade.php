@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="bd">
                <div class="row">
                    <div class="col-md-12">
                        <div class="after-filter"></div>
                    </div>
                </div>

                <div class="row" style="border-bottom: 1px solid #dee2e6;">

                    <div class="col-md-7">

                        <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14 tab-hash tab-ajax" role="tablist" data-href="master_products/get" data-method="get" data-default-tab="tab_2">
                            <li class="nav-item active" style="border-bottom: 1px solid #dee2e6;">
                                <a class="nav-link" data-toggle="tab" data-tabid="20" href="#tab_20" role="tab" aria-selected="true">
                                    <span class="hidden-sm-up"></span>
                                    <span class="hidden-xs-down">Master Products</span>
                                </a>
                            </li>

                        </ul>
                    </div>

                    <div class="col-md-5">
                        <div class="input-group">
                            <div class="all-pagination" style="vertical-align: middle;margin: 10px;"></div>
                            <input type="text" class="form-control ajax-search search-btn" placeholder="Search" aria-label="" id="filtersearch">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btnGroupAddon" onclick="$('.ajax-search').trigger('keyup');"><i class="ti-search"></i></button>
                            </div>
                            <button type="button" title="Filters" data-toggle="modal" data-target="#filtersModel" class="btn btn-outline-secondary btn-sm ajax-Link" data-href="{{ url('/master_products/add/0') }}">
                                <i class="ti-plus"></i>
                            </button>
                        </div>

                        {{--<div class="btn-group pull-right" role="group" aria-label="Basic example">
                            <input type="text" id="filtersearch" class="form-control ajax-search search-btn btn-outline-secondary" placeholder="Search" aria-label="Input group example" aria-describedby="btnGroupAddon">
                            <div class="input-group-append search-btn btn-outline-secondary">
                                <div class="input-group-text border-left-0" title="Search" id="btnGroupAddon" onclick="$('.ajax-search').trigger('keyup');">
                                    <i class="ti-search"></i>
                                </div>

                            </div>
                        </div>--}}

                            {{--<button type="button" title="Filters" data-toggle="modal" data-target="#filtersModel" class="btn btn-outline-secondary">
                                <i class="ti-filter"></i>
                            </button>
                            <div class="c-btn" style="display: none;"></div>
                            <button type="button" onclick="downloadTNLink($(this))" data-href="taxonomy/download" data-tab="level2" aria-expanded="false" class="btn btn-outline-secondary">
                                <i class="ti-download"></i>
                            </button>--}}

                    </div>
                </div>
                <div class="tab-content br-n pn">
                    <div class="tab-pane customtab active" id="tab_20" role="tabpanel"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal bs-example-modal-sm" id="filtersModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title pl-1" id="myModalLabel">Filters</h6>
                        <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
                    </div>
                    <div class="modal-body p-1">
                        <div class="card mb-1">
                            <div class="card-body p-1">
                                <form id="filter_form" class=" filter-inner respo-filter-myticket" data-title="tickets#24#536" autocomplete="off">
                                    <input type="hidden" name="searchterm" class="form-control form-control-sm" placeholder="" data-placeholder="">

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    <script>
        $(document).ready(function () {
            var autosearch_field = $('.ajax-search');
            autosearch_field.on('keyup change paste',function(e) {
                if((e.type == 'keyup' && e.target.tagName == 'INPUT') || (e.type == 'change' && e.target.tagName == 'SELECT')){
                    var obj = $(this);
                    var oldVal = obj.val();
                    delay(function(){
                        var fvalue = $.trim(obj.val());
                        $('[name="searchterm"]').val(fvalue);
                        $('.tab-ajax li a.active').trigger('show.bs.tab');
                    }, 1000 );
                }
            });

            ACFn.ajax_master_product_load = function (F, R) {
                if(R.success){
                    $('#modal-popup').modal('hide');
                    ACFn.display_message(R.messageTitle, '', 'success');
                    $('.tab-ajax li.active a[data-toggle="tab"]').trigger('show.bs.tab');
                }
            }
        })

        function getFilters(F) {
            if (typeof F == 'undefined') {
                if($("#filter_milestone_form").length > 0){
                    F = $("#filter_milestone_form");
                }else{
                    F = $("#filter_form");
                }

            }
            var filters = [];
            var filtersFlag = false;
            if (F.length) {
                $.each(F.serializeArray(), function (index, element) {
                    console.log(element);
                    if (typeof filters[element.name] == 'undefined') {
                        filters[element.name] = [];
                    }
                    if (element.value) {
                        filters[element.name].push(element.value);
                        filtersFlag = true;
                    }
                });
            }
            var obj = $.extend({}, filters);
            if(filtersFlag == true){
                filtersApplied(obj, F);

            }else{
                if($("#filtersApplied").length > 0){
                    $('#filtersApplied').remove();
                    $('.clear-btn').remove();
                }
            }
            console.log('Form elements');
            console.log(obj);
            console.log('Form elements end');
            return obj;
        }

        function filtersApplied(filters, $form) {
            if (typeof $form == 'undefined') {
                $form = $("#filter_form");
            }
            var key = null;
            for (var prop in filters) {
                if (filters.hasOwnProperty(prop)) {
                    key++;
                }
            }
            if (key > 0 && $("#filtersApplied").length == 0) {
                //$("#collapseFilters").after('<ul id="filtersApplied" class="selected-filters" ></ul>');
                $(".after-filter").html('<ul id="filtersApplied" class="selected-filters" ></ul>'); //<button type="button" class="btn clear-btn" onclick="clearFilters()"><i class="fa fa-refresh" aria-hidden="true"></i> Clear Filter</button>
            }
            var fouter = $("#filtersApplied");
            fouter.empty();
            $.each(filters, function (name, element) {
                var elselect = $form.find("select[name='" + name + "']");
                var elinput = $form.find("input[name='" + name + "']");
                $.each(element, function (key, value) {
                    if (value == '') {
                        return;
                    }
                    var long_name = value;
                    var elcheckbox = $form.find("[name='" + name + "'][value='" + value + "'][type='checkbox']");
                    var elradio = $form.find("[name='" + name + "'][value='" + value + "'][type='radio']");
                    if (elcheckbox.length && elcheckbox.next('label').length) {
                        long_name = elcheckbox.next('label').html();
                    } else if (elradio.length && elradio.next('label').length) {
                        long_name = elradio.next('label').html();
                    } else if (elselect.length) {
                        var opt = elselect.find('option[value="' + value + '"]');
                        if (opt.length) {
                            long_name = opt.html();
                        }
                    } else if (elinput.length) {
                        var opr = $form.find("select[name='" + name + "_op']").length ?  $form.find("select[name='" + name + "_op']").val() : '';
                        long_name = elinput.attr('data-placeholder') + ' '+ opr + ' ' + elinput.val();
                    }
                    //console.log("not allowed----",elselect.data('notallowed'));
                    if(elselect.data('notallowed') == false || elselect.data('notallowed') == undefined){
                        fouter.append('<li class="selected-filter mr-1"><span>' + long_name + '</span><a href="#" class="removeFilter" data-name="' + name + '" data-value="' + value + '" ><i class="fa fa-times-circle"></i></a></li>');
                    }

                });

            });
        }

        function downloadTNLink(obj){
            var url = obj.data('href');
            var tab = obj.attr('data-tab');
            var filters = getFilters($('#filter_form'));
            var table = $('#basic_table_without_dynamic_pagination');
            var downloadableColumns = table.attr('data-columns-visible') ? table.attr('data-columns-visible') : '';
            ACFn.sendAjax(url,'GET',{
                tab : tab,
                filters : filters,
                downloadableColumns : downloadableColumns
            },obj);
        }

        function setlevel(level,alllevels) {
            $('#DownloadBtn').attr('data-tab',level);

            //clearFilters();
            $.each(alllevels,function (index,value) {
                if($.trim(level) == value){
                    $('.' + value).removeAttr('style');
                }else{
                    $('.' + value).hide();
                }
            })
        }

        function blankMergeData(){}

        function ajax_field_update(obj) {
            var primary_column = obj.data('primary_column');
            var primary_column_value = obj.data('primary_column_value');
            var fieldname = obj.data('field');
            var fieldvalue = obj.val();
            delay(function(){
                ACFn.sendAjax('taxonomy/quickupdate','GET',{
                    primary_colum : primary_column,
                    primary_column_value : primary_column_value,
                    fieldname : fieldname,
                    fieldvalue : fieldvalue,
                })
            }, 1000 );
        }
    </script>

@stop
