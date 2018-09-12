
var updateSelect2Element = function ($oElm, request, cache) {
    if('undefined' === typeof cache) {
       cache = true;
    }
    if($('option', $oElm).length < 20) {
        $oElm.select2({
            minimumInputLength: 2,
            allowClear: true,
            minimumResultsForSearch: 10,
            ajax: {
                url: '/' + locale + request,
                cache: cache,
                dataType: 'json',
                params: { // extra parameters that will be passed to ajax
                    contentType: "application/json; charset=utf-8"
                },
                delay: 250,
                data: function (params) {
                    return {
                        name: params.term, // search term
                        locale: locale,
                        token: $('meta[name="csrf-token"]').attr('content')
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    } else {
        $oElm.select2({allowClear: true, minimumResultsForSearch: 10});
    }
};

$(document).ready(function () {

    var $SearchQuery         = $('input[name="query"]');
    var $AdstypesId         = $('#adstypes_id');
    var $CategoriesIds      = $('#categories_ids');
    var $SubcategoriesIds   = $('#subcategories_ids');

    var $ManufacturersId    = $('#manufacturers_id');
    var $ModelsId           = $('#models_id');

    var $MinLength          = $('#min_length');
    var $MaxLength          = $('#max_length');

    var $MinWidth           = $('#min_width');
    var $MaxWidth           = $('#max_width');

    var $MinYearBuilt       = $('#min_year_built');
    var $MaxYearBuilt       = $('#max_year_built');

    var $SellType           = $('#sell_type');

    var $CountriesId           = $('#ci_countries_id, #countries_id, #country_id').not('.noselect2');

    /*
     * checkAdstype
     */
    var checkAdstype = function (adstypes_id) {
        //@#console.info("checkAdstype");
        var url = '/ajax-adstype_detail';
        var params = {adstypes_id: adstypes_id, locale:locale};
        //var $CategoriesIds = $('#categories_ids');
        //var $SubcategoriesIds = $('#subcategories_ids');
        var $ManufacturersId = $('#manufacturers_id');
        var $ModelsId = $('#models_id');

        var $MinLength = $('#min_length');
        var $MaxLength = $('#max_length');

        var $MinWidth = $('#min_width');
        var $MaxWidth = $('#max_width');

        var $MinYearBuilt = $('#min_year_built');
        var $MaxYearBuilt = $('#max_year_built');

        var token = $('meta[name="csrf-token"]').attr('content');
        $.extend({'token':token}, params);

        var checkType = function (data) {
            data = data.responseJSON;
            if (data.length != 0) {
                //@#console.log('data', data);
                if (/engine/.test(data.rewrite_url)) {
                    //console.log('engine');

                    unsetHasSuccess($MinLength);
                    unsetHasSuccess($MaxLength);

                    unsetHasSuccess($MinWidth);
                    unsetHasSuccess($MaxWidth);

                    unsetHasSuccess($MinYearBuilt, true);
                    unsetHasSuccess($MaxYearBuilt, true);

                    unsetHasSuccess($ModelsId);

                    unsetHasSuccess($ManufacturersId, true);
                } else if (/pontoon-mooring/.test(data.rewrite_url)) {
                    //console.log('pontoon-mooring');

                    unsetHasSuccess($MinLength, true);
                    unsetHasSuccess($MaxLength, true);

                    unsetHasSuccess($MinWidth, true);
                    unsetHasSuccess($MaxWidth, true);

                    unsetHasSuccess($MinYearBuilt);
                    unsetHasSuccess($MaxYearBuilt);

                    unsetHasSuccess($ModelsId);

                    unsetHasSuccess($ManufacturersId);
                } else if (/other/.test(data.rewrite_url)) {
                    //console.log('other');

                    unsetHasSuccess($MinLength, true);
                    unsetHasSuccess($MaxLength, true);

                    unsetHasSuccess($MinWidth);
                    unsetHasSuccess($MaxWidth);

                    unsetHasSuccess($MinYearBuilt, true);
                    unsetHasSuccess($MaxYearBuilt, true);

                    unsetHasSuccess($ModelsId);

                    unsetHasSuccess($ManufacturersId, true);
                } else if (/boat-trailer/.test(data.rewrite_url)) {
                    //console.log('boat-trailer');

                    unsetHasSuccess($MinLength, true);
                    unsetHasSuccess($MaxLength, true);

                    unsetHasSuccess($MinWidth);
                    unsetHasSuccess($MaxWidth);

                    unsetHasSuccess($MinYearBuilt, true);
                    unsetHasSuccess($MaxYearBuilt, true);

                    unsetHasSuccess($ModelsId);

                    unsetHasSuccess($ManufacturersId, true);
                } else {
                    //console.log('boats');

                    unsetHasSuccess($MinLength, true);
                    unsetHasSuccess($MaxLength, true);

                    unsetHasSuccess($MinWidth);
                    unsetHasSuccess($MaxWidth);

                    unsetHasSuccess($MinYearBuilt, true);
                    unsetHasSuccess($MaxYearBuilt, true);

                    //unsetHasSuccess($ModelsId);

                    unsetHasSuccess($ManufacturersId, true);
                }
            } else {
                //@#console.log('no datas');
            }
        };

        $.ajax({
            method: "GET",
            url: '/' + locale + url,
            data: params,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: true,
            beforeSend: function () {
                if (localCache.exist('/' + locale + url + '?' + $.param(params))) {
                    checkType(localCache.get('/' + locale + url + '?' + $.param(params)));
                    return false;
                }
                return true;
            },
            complete: function (jqXHR, textStatus) {
                localCache.set('/' + locale + url + '?' + $.param(params), jqXHR, checkType);
            }
        });
    };

    $('select.selectpicker').on('change', function() {
        var $This = $(this);
        $This.selectpicker('refresh');
    });

    ////////////////////////////
    ////////////////////////////

    var dataAjaxUrl = '';

    var adstypes_id = $AdstypesId.length && $AdstypesId.val() ? $AdstypesId.val() : 0;
    var category_id = $CategoriesIds.length && $CategoriesIds.val() ? $CategoriesIds.val() : 0;
    var subcategory_id = $SubcategoriesIds.length && $SubcategoriesIds.val() ? $SubcategoriesIds.val() : 0;

    var manufacturer_id = $ManufacturersId.length && $ManufacturersId.val() ? $ManufacturersId.val() : 0;
    var model_id = $ModelsId.length && $ModelsId.val() ? $ModelsId.val() : 0;

    /*
     * Ads's Types
     */
    if ($AdstypesId.length) {

        if ($AdstypesId.val()) {
            //checkAdstype($AdstypesId.val());
        }
        dataAjaxUrl = '';
        if (!adstypes_id) {
            dataAjaxUrl = $AdstypesId[0].hasAttribute("data-ajax--url") ? $AdstypesId.attr('data-ajax--url') : 'adstypes';
            ajaxFill(dataAjaxUrl, $AdstypesId, {
                locale: locale
            });
        }

        $AdstypesId.on('change', function (event) {
           //@#console.info("$AdstypesId.on('change'");
            //var $This = $(this);
            var dataAjaxUrl = '';

            var adstypes_id = event.target.value;
            if (adstypes_id.length > 0 && 0 != adstypes_id) {
                setHasSuccess($AdstypesId);
                checkAdstype($AdstypesId.val());
            } else  {
                unsetHasSuccess($AdstypesId, true);
            }
            unsetHasSuccess($SubcategoriesIds);

            //dataAjaxUrl = $CategoriesIds[0].hasAttribute("data-ajax--url") ? $CategoriesIds.attr('data-ajax--url') : 'gateway_categories';
            dataAjaxUrl = $CategoriesIds[0].hasAttribute("data-ajax--url") ? $CategoriesIds.attr('data-ajax--url') : 'categories';
            ajaxFill(dataAjaxUrl, $CategoriesIds, {
                adstypes_id: adstypes_id,
                locale: locale
            });

            unsetHasSuccess($SubcategoriesIds);

            var category_id = $CategoriesIds.val() ? $CategoriesIds.val() : 0;
            var subcategory_id = $SubcategoriesIds.val() ? $SubcategoriesIds.val() : 0;

            //dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : 'manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $ManufacturersId, {
                    adstypes_id: adstypes_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        //request = '/ajax-gateway_manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($ManufacturersId, request);
                });
            }
            $ManufacturersId.val(null).trigger('change');
        });
    }

    /*
     * Ads's Categories
     */
    if ($CategoriesIds.length) {
        adstypes_id = $AdstypesId.val();
        category_id = $CategoriesIds.val();
        dataAjaxUrl = '';
        if (!adstypes_id && !category_id) {
            unsetHasSuccess($CategoriesIds, true);
        } else if (!category_id) {
            //dataAjaxUrl = $CategoriesIds[0].hasAttribute("data-ajax--url") ? $CategoriesIds.attr('data-ajax--url') : 'gateway_categories';
            dataAjaxUrl = $CategoriesIds[0].hasAttribute("data-ajax--url") ? $CategoriesIds.attr('data-ajax--url') : 'categories';
            ajaxFill(dataAjaxUrl, $CategoriesIds, {
                adstypes_id: adstypes_id,
                locale: locale
            });
        }
        $CategoriesIds.on('change', function (event) {
           //@#console.info('$CategoriesIds change', $(this).val());
            var $This = $(this);
            var dataAjaxUrl = '';
            var category_id = event.target.value;
            if (category_id.length > 0 && 0 != category_id) {
                setHasSuccess($CategoriesIds);

                //@#console.log('category_id.length');
                var selectedText = $(':selected', $This).text();
                if (selectedText.search(/pontoon/i) != -1 || selectedText.search(/jetty/i) != -1 || selectedText.search(/storage/i) != -1 ) {
                    //console.log('!!!! pontoon !!!');
                } else if (selectedText.search(/trailer/i) != -1) {
                    //console.log('!!!! trailer !!!');
                }  else if (selectedText.search(/other/i) != -1) {
                    //console.log('!!!! other !!!');
                }   else if (selectedText.search(/ribs/i) != -1) {
                    //console.log('!!!! ribs !!!');
                } else if (selectedText.search(/engine/i) != -1) {
                    //console.log('!!!! engine !!!');
                    $('option',$AdstypesId).each(function() {
                        var text = $(this).text();

                        if (text.search(/engine/i) != -1) {
                            //console.log('>> engine <<');
                            $AdstypesId.val($(this).val());
                            setHasSuccess($AdstypesId);
                        } else {
                            unsetHasSuccess($AdstypesId, true);
                        }
                    });
                    unsetHasSuccess($MinLength);
                    unsetHasSuccess($MaxLength);
                    unsetHasSuccess($MinWidth);
                    unsetHasSuccess($MaxWidth);
                    unsetHasSuccess($MinYearBuilt, true);
                    unsetHasSuccess($MaxYearBuilt, true);
                    unsetHasSuccess($ModelsId);
                    unsetHasSuccess($ManufacturersId, true);
                } else {
                    // boats
                }

                if($SubcategoriesIds.length) {
                    //dataAjaxUrl = $SubcategoriesIds[0].hasAttribute("data-ajax--url") ? $SubcategoriesIds.attr('data-ajax--url') : 'gateway_subcategories';
                    dataAjaxUrl = $SubcategoriesIds[0].hasAttribute("data-ajax--url") ? $SubcategoriesIds.attr('data-ajax--url') : 'subcategories';
                    ajaxFill(dataAjaxUrl, $SubcategoriesIds, {
                        category_id: category_id,
                        locale: locale
                    });
                    unsetHasSuccess($SubcategoriesIds);
                }
            } else {
                unsetHasSuccess($CategoriesIds, true);

                if($SubcategoriesIds.length) {
                    unsetHasSuccess($SubcategoriesIds);
                    $SubcategoriesIds.val(null);
                    $SubcategoriesIds.trigger('change');
                }

                $ManufacturersId.val(null);
                $ManufacturersId.trigger('change');
            }

            var adstypes_id = $AdstypesId.val() ? $AdstypesId.val() : 0;
            var subcategory_id = $SubcategoriesIds.val() ? $SubcategoriesIds.val() : 0;

            //dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : 'manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $ManufacturersId, {
                    adstypes_id: adstypes_id,
                    category_id: category_id,
                    subcategory_id: subcategory_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        //request = '/ajax-gateway_manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($ManufacturersId, request);
                });
            }
            $ManufacturersId.val(null).trigger('change');
        });
    }

    /*
     * Ads's Subcategories
     */
    if ($SubcategoriesIds.length) {
        dataAjaxUrl = '';
        category_id = $CategoriesIds.val();
        subcategory_id = $SubcategoriesIds.val();

        if (!category_id) {
            unsetHasSuccess($SubcategoriesIds);
        } else if (category_id && subcategory_id) {
            setHasSuccess($SubcategoriesIds);
        } else if ('undefined' != category_id && !subcategory_id) {
            //dataAjaxUrl = $SubcategoriesIds[0].hasAttribute("data-ajax--url") ? $SubcategoriesIds.attr('data-ajax--url') : 'gateway_subcategories';
            dataAjaxUrl = $SubcategoriesIds[0].hasAttribute("data-ajax--url") ? $SubcategoriesIds.attr('data-ajax--url') : 'subcategories';
            ajaxFill(dataAjaxUrl, $SubcategoriesIds, {
                category_id: category_id,
                locale: locale
            });
        }

        $SubcategoriesIds.on('change', function (event)     {
           //@#console.info('$SubcategoriesIds change', $(this).val());
            //var $This = $(this);
            var  dataAjaxUrl = '';
            var adstypes_id = $AdstypesId.val() ? $AdstypesId.val() : 0;
            var category_id = $CategoriesIds.val() ? $CategoriesIds.val() : 0;
            var subcategory_id = event.target.value;

            if (subcategory_id.length > 0 && 0 != subcategory_id) {
                setHasSuccess($SubcategoriesIds);
            } else if(!category_id && !subcategory_id){
                unsetHasSuccess($SubcategoriesIds);
            } else if( !subcategory_id){
                unsetHasSuccess($SubcategoriesIds, true);
            }

            //dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : '_manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $ManufacturersId, {
                    adstypes_id: adstypes_id,
                    category_id: category_id,
                    subcategory_id: subcategory_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        //request = '/ajax-gateway_manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($ManufacturersId, request);
                });
            }
            $ManufacturersId.val(null).trigger('change');
        });
    }

    /*
     * Ads's Manufacturers
     */
    if ($ManufacturersId.length) {
        dataAjaxUrl = '';
        adstypes_id = $AdstypesId.val() ? $AdstypesId.val() : 0;
        category_id = $CategoriesIds.val() ? $CategoriesIds.val() : 0;
        subcategory_id = $SubcategoriesIds.val() ? $SubcategoriesIds.val() : 0;
        manufacturer_id = $ManufacturersId.val();
        var request = '';
        if (!manufacturer_id) {
            //dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : 'manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $ManufacturersId, {
                    adstypes_id: adstypes_id,
                    category_id: category_id,
                    subcategory_id: subcategory_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        //request = '/ajax-gateway_manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($ManufacturersId, request);
                });
            } else {
                request = dataAjaxUrl + '?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
                //updateSelect2Element($ManufacturersId, request, false);
                updateSelect2Element($ManufacturersId, request, true);
            }
        } else {
            //dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : '/ajax-gateway_manufacturer';
            dataAjaxUrl = $ManufacturersId[0].hasAttribute("data-ajax--url") ? $ManufacturersId.attr('data-ajax--url') : '/ajax-manufacturer';
            request = dataAjaxUrl + '?adstypes_id=' + adstypes_id + '&category_id=' + category_id + '&subcategory_id=' + subcategory_id;
            updateSelect2Element($ManufacturersId, request, false);
            $ManufacturersId.val(manufacturer_id).trigger('change');
        }

        $ManufacturersId.on('change', function (event) {
            //@#console.info("$ManufacturersId.on('change'");
            //var $This = $(this);
            var dataAjaxUrl = '';
            var manufacturers_id = event.target.value;
            if (manufacturers_id.length > 0 && 0 != manufacturers_id) {
                $SearchQuery.val(null);
                setHasSuccess($ManufacturersId);
                //dataAjaxUrl = $ModelsId[0].hasAttribute("data-ajax--url") ? $ModelsId.attr('data-ajax--url') : 'gateway_models';
                //dataAjaxUrl = $ModelsId[0].hasAttribute("ajax-url") ? $ModelsId.attr('ajax-url') : 'gateway_models';
                dataAjaxUrl = $ModelsId[0].hasAttribute("ajax-url") ? $ModelsId.attr('ajax-url') : 'models';
                ajaxFill(dataAjaxUrl, $ModelsId, {
                    manufacturers_id: manufacturers_id,
                    locale: locale
                }, function () {
                    $ModelsId.select2({allowClear: true, minimumResultsForSearch: 10});
                    if (/ajax-/.test(dataAjaxUrl)) {
                        $ModelsId.val(null).trigger('change');
                    }
                });
            } else {
                unsetHasSuccess($ModelsId);
                unsetHasSuccess($ManufacturersId, true);
            }
        });
    }

    /*
     * Ads's Models
     */
    if ($ModelsId.length) {
        model_id = $ModelsId.val();
        manufacturer_id = $ManufacturersId.val();
        adstypes_id = $AdstypesId.val();
        if (!manufacturer_id) {
            unsetHasSuccess($ModelsId);
            $ModelsId.val(null).trigger('change');
        } else {
            $ModelsId.select2({allowClear: true, minimumResultsForSearch: 10});
            $ModelsId.val(model_id).trigger('change');
        }

        $ModelsId.on('change', function (event) {
            //@#console.info("$ModelsId.on('change'");
            //var $This = $(this);

            var model_id = event.target.value;
            var manufacturer_id = $ManufacturersId.val();
            if (model_id.length > 0 && 0 != model_id) {
                $SearchQuery.val(null);
                setHasSuccess($ModelsId);
            } else if(!manufacturer_id && !model_id){
                unsetHasSuccess($ModelsId);
            } else if(manufacturer_id && !model_id) {
                if ($ModelsId.find('option').length) {
                    unsetHasSuccess($ModelsId, true);
                } else {
                    unsetHasSuccess($ModelsId);
                }
            } else {
                unsetHasSuccess($ModelsId);
            }
        });
    }

    /*
     * Countries Id
     */
    if ($CountriesId.length) {
        $CountriesId.select2({allowClear: true, minimumResultsForSearch: 10});
    }

    ////////////////////////////
    ////////////////////////////

    function MinDisabled (selectedVal, $oElmMax) {
        $('option', $oElmMax).each(function(index, element) {
            var $This = $(element);
            if ($This.val() <= parseInt(selectedVal)) {
                $This.attr('disabled','disabled')
            } else {
                if(Number.isInteger($This.val())) {
                    $This.removeAttr('disabled');
                }
            }
        });
    }
    function MaxDisabled (selectedVal, $oElmMin) {
        $('option', $oElmMin).each(function(index, element) {
            var $This = $(element);
            if ($This.val() >= parseInt(selectedVal)) {
                $This.attr('disabled','disabled')
            } else {
                if(Number.isInteger($This.val())) {
                    $This.removeAttr('disabled');
                }
            }
        });
    }

    if ($MinYearBuilt.length) {
        if($MinYearBuilt.val()) {
            setHasSuccess($MinYearBuilt);
            MinDisabled ($MinYearBuilt.val(), $MaxYearBuilt);
        }
        $MinYearBuilt.on('change', function(event){
            var selectedVal = event.target.value;
            if (selectedVal.length > 0) {
                MinDisabled (selectedVal, $MaxYearBuilt);
                setHasSuccess($MinYearBuilt);
            } else if(!selectedVal){
                unsetHasSuccess($MinYearBuilt, true);
            }
        });
    }

    if ($MaxYearBuilt.length) {
        if($MaxYearBuilt.val()) {
            setHasSuccess($MaxYearBuilt);
            MaxDisabled ($MaxYearBuilt.val(), $MinYearBuilt);
        }
        $MaxYearBuilt.on('change', function (event) {
            var selectedVal = event.target.value;
            if (selectedVal.length > 0) {
                MaxDisabled (selectedVal, $MinYearBuilt);
                setHasSuccess($MaxYearBuilt);
            } else if(!selectedVal){
                unsetHasSuccess($MaxYearBuilt, true);
            }
        });
    }

    if ($MinLength.length) {
        if($MinLength.val()) {
            setHasSuccess($MinLength);
            MinDisabled ($MinLength.val(), $MaxLength);
        }
        $MinLength.on('change', function(event){
            var selectedVal = event.target.value;
            if (selectedVal.length > 0) {
                MinDisabled (selectedVal, $MaxLength);
                setHasSuccess($MinLength);
            } else if(!selectedVal){
                unsetHasSuccess($MinLength, true);
            }
        });
    }

    if ($MaxLength.length) {
        if($MaxLength.val()) {
            setHasSuccess($MaxLength);
            MaxDisabled ($MaxLength.val(), $MinLength);
        }
        $MaxLength.on('change', function (event) {
            var selectedVal = event.target.value;
            if (selectedVal.length > 0) {
                MaxDisabled (selectedVal, $MinLength);
                setHasSuccess($MaxLength);
            } else if(!selectedVal){
                unsetHasSuccess($MaxLength, true);
            }
        });
    }

    if ($MinWidth.length) {
        if($MinWidth.val()) {
            setHasSuccess($MinWidth);
            MinDisabled ($MinWidth.val(), $MaxWidth);
        } else {
            unsetHasSuccess($MinWidth);
        }
        $MinWidth.on('change', function(event){
            var selectedVal = event.target.value;
            if (selectedVal.length > 0) {
                MinDisabled (selectedVal, $MaxWidth);
                setHasSuccess($MinWidth);
            } else if(!selectedVal){
                unsetHasSuccess($MinWidth, true);
            }
        });
    }

    if ($MaxWidth.length) {
        if($MaxWidth.val()) {
            setHasSuccess($MaxWidth);
            MaxDisabled ($MaxWidth.val(), $MinWidth);
        } else {
            unsetHasSuccess($MaxWidth);
        }
        $MaxWidth.on('change', function (event) {
            var selectedVal = event.target.value;
            if (selectedVal.length > 0) {
                MaxDisabled (selectedVal, $MinWidth);
                setHasSuccess($MaxWidth);
            } else if(!selectedVal){
                unsetHasSuccess($MaxWidth, true);
            }
        });
    }

    if ($SellType.length) {
        if($SellType.val()) {
            setHasSuccess($SellType);
        }
        $SellType.on('change', function (event) {
            var selectedVal = event.target.value;
            if (selectedVal.length > 0) {
                setHasSuccess($SellType);
            } else if(!selectedVal){
                unsetHasSuccess($SellType, true);
            }
        });
    }
});


