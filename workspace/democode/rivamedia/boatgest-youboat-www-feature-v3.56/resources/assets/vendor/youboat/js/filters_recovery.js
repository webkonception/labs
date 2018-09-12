$(document).ready(function () {

    var $RecoveryAdstypesId         = $('#recovery_adstypes_id');
    var $RecoveryCategoriesIds      = $('#recovery_categories_ids');
    var $RecoverySubcategoriesIds   = $('#recovery_subcategories_ids');

    var $RecoveryManufacturersId    = $('#recovery_manufacturers_id');
    var $RecoveryModelsId           = $('#recovery_models_id');

    var $RecoveryYearBuilt          = $('#recovery_year_built');

    var $RecoveryBudget             = $('#recovery_budget');

    var $RecoveryDescription        = $('#recovery_description');

    var dataAjaxUrl = '';

    var recovery_adstypes_id        = $RecoveryAdstypesId.length && $RecoveryAdstypesId.val() ? $RecoveryAdstypesId.val() : 0;;
    var recovery_category_id        = $RecoveryCategoriesIds.length && $RecoveryCategoriesIds.val() ? $RecoveryCategoriesIds.val() : 0;
    var recovery_subcategory_id     = $RecoverySubcategoriesIds.length && $RecoverySubcategoriesIds.val() ? $RecoverySubcategoriesIds.val() : 0;

    var recovery_manufacturers_id   = $RecoveryManufacturersId.length && $RecoveryManufacturersId.val() ? $RecoveryManufacturersId.val() : 0;
    var recovery_models_id          = $RecoveryModelsId.length && $RecoveryModelsId.val() ? $RecoveryModelsId.val() : 0;

    var recovery_year_built         = $RecoveryYearBuilt.length && $RecoveryYearBuilt.val() ? $RecoveryYearBuilt.val() : 0;

    var recovery_budget             = $RecoveryBudget.length && $RecoveryBudget.val() ? $RecoveryBudget.val() : 0;

    var recovery_description        = $RecoveryDescription.length && $RecoveryDescription.val() ? $RecoveryDescription.val() : 0;

    ////////////////////////////
    ////////////////////////////

    /*
     Collapse Elments states
     */
    var $CollapseRecovery = $('#collapseRecovery');

    if ($CollapseRecovery.length) {

        if (recovery_adstypes_id || recovery_category_id || recovery_subcategory_id || recovery_manufacturers_id || recovery_models_id || recovery_year_built || recovery_budget || recovery_description) {
            jQuery('#btn_recovery').trigger('click');
        }

        $CollapseRecovery.on('show.bs.collapse', function () {
            //setTimeout( function() {$(window).trigger('resize');}, 200 );
            $CollapseRecovery.find('[data-required="required"]').attr('required','required');
            setRequiredInputs($CollapseRecovery);
            //var $Parent = $CollapseRecovery.parents('.step');
            //var $BodBtnNext = $Parent.find('.btn-next');
            //checkBtnsBod($BodBtnNext);
            //##checkBodBtnsNext($Parent);

            //#console.info('call checkBodRequiresInputs');
            //##checkBodRequiresInputs($Parent);
        });
        $CollapseRecovery.on('hide.bs.collapse', function () {
            //#console.log('-----------');
            //#console.log('hide.bs.collapse');
            //#console.log('-----------');
            $CollapseRecovery.find('[data-required="required"]').removeAttr('required');
            unsetRequiredInputs($CollapseRecovery);
            //var $Parent = $CollapseRecovery.parents('.step');
            //var $BodBtnNext = $Parent.find('.btn-next');
            //checkBtnsBod($BodBtnNext);
            //##checkBodBtnsNext($Parent);
            //////##checkBodRequiresInputs($Parent);
        });

        if ($CollapseRecovery.find('.has-error').length > 0) {
            $CollapseRecovery.collapse('show');
        }
    }
    /*
     * recovery_checkAdstype
     */
    var recovery_checkAdstype = function (recovery_adstypes_id) {
        //@#console.info("recovery_checkAdstype");
        var url = '/ajax-adstype_detail';
        var params = {adstypes_id: recovery_adstypes_id};
        //var $RecoveryCategoriesIds = $('#recovery_categories_ids');
        //var $RecoverySubcategoriesIds = $('#recovery_subcategories_ids');
        var $RecoveryManufacturersId = $('#recovery_manufacturers_id');
        var $RecoveryModelsId = $('#recovery_models_id');
        var $RecoveryYearBuilt = $('#recovery_year_built');

        var token = $('meta[name="csrf-token"]').attr('content');
        $.extend({'token':token}, params);

        var checkType = function (data) {
            data = data.responseJSON;
            if (data.length != 0) {
                //@#console.log('data', data);
                if (/engine/.test(data.rewrite_url)) {
                    //console.log('engine');

                    unsetHasSuccess($RecoveryYearBuilt, true);

                    unsetHasSuccess($RecoveryModelsId);

                    unsetHasSuccess($RecoveryManufacturersId, true);
                } else if (/pontoon-mooring/.test(data.rewrite_url)) {
                    //console.log('pontoon-mooring');

                    unsetHasSuccess($RecoveryYearBuilt);

                    unsetHasSuccess($RecoveryModelsId);

                    unsetHasSuccess($RecoveryManufacturersId);
                } else if (/other/.test(data.rewrite_url)) {
                    //console.log('other');

                    unsetHasSuccess($RecoveryYearBuilt, true);

                    unsetHasSuccess($RecoveryModelsId);

                    unsetHasSuccess($RecoveryManufacturersId, true);
                } else if (/boat-trailer/.test(data.rewrite_url)) {
                    //console.log('boat-trailer');

                    unsetHasSuccess($RecoveryYearBuilt, true);

                    unsetHasSuccess($RecoveryModelsId);

                    unsetHasSuccess($RecoveryManufacturersId, true);
                } else {
                    //console.log('boats');

                    unsetHasSuccess($RecoveryYearBuilt, true);

                    //unsetHasSuccess($RecoveryModelsId);

                    unsetHasSuccess($RecoveryManufacturersId, true);
                }
            } else {
                //@#console.log('no datas');
            }
        };

        $.ajax({
            method: "GET",
            url: url,
            data: params,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            cache: true,
            beforeSend: function () {
                if (localCache.exist(url + '?' + $.param(params))) {
                    checkType(localCache.get(url + '?' + $.param(params)));
                    return false;
                }
                return true;
            },
            complete: function (jqXHR, textStatus) {
                localCache.set(url + '?' + $.param(params), jqXHR, checkType);
            }
        });
    };

    ////////////////////////////
    ////////////////////////////

    /*
     * Ads's Types
     */
    if ($RecoveryAdstypesId.length) {

        if ($RecoveryAdstypesId.val()) {
            //recovery_checkAdstype($RecoveryAdstypesId.val());
        }

        $RecoveryAdstypesId.on('change', function (event) {
            //@#console.info("$RecoveryAdstypesId.on('change'");
            //var $This = $(this);
            var dataAjaxUrl = '';

            var recovery_adstypes_id = event.target.value;
            if (recovery_adstypes_id.length > 0 && 0 != recovery_adstypes_id) {
                setHasSuccess($RecoveryAdstypesId);
                recovery_checkAdstype($RecoveryAdstypesId.val());
            } else  {
                unsetHasSuccess($RecoveryAdstypesId, true);
            }
            unsetHasSuccess($RecoverySubcategoriesIds);

            dataAjaxUrl = $RecoveryCategoriesIds[0].hasAttribute("data-ajax--url") ? $RecoveryCategoriesIds.attr('data-ajax--url') : 'gateway_categories';
            ajaxFill(dataAjaxUrl, $RecoveryCategoriesIds, {
                adstypes_id: recovery_adstypes_id,
                locale: locale
            });

            unsetHasSuccess($RecoverySubcategoriesIds);

            var recovery_category_id = $RecoveryCategoriesIds.val() ? $RecoveryCategoriesIds.val() : 0;
            var recovery_subcategory_id = $RecoverySubcategoriesIds.val() ? $RecoverySubcategoriesIds.val() : 0;

            dataAjaxUrl = $RecoveryManufacturersId[0].hasAttribute("data-ajax--url") ? $RecoveryManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $RecoveryManufacturersId, {
                    adstypes_id: recovery_adstypes_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        request = '/ajax-gateway_manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($RecoveryManufacturersId, request);
                });
            }
            $RecoveryManufacturersId.val(null).trigger('change');
        });
    }

    /*
     * Ads's Categories
     */
    if ($RecoveryCategoriesIds.length) {
        recovery_adstypes_id = $RecoveryAdstypesId.val();
        recovery_category_id = $RecoveryCategoriesIds.val();
        dataAjaxUrl = '';
        if (!recovery_adstypes_id && !recovery_category_id) {
            unsetHasSuccess($RecoveryCategoriesIds, true);
        } else if (!recovery_category_id) {
            dataAjaxUrl = $RecoveryCategoriesIds[0].hasAttribute("data-ajax--url") ? $RecoveryCategoriesIds.attr('data-ajax--url') : 'gateway_categories';
            ajaxFill(dataAjaxUrl, $RecoveryCategoriesIds, {
                adstypes_id: recovery_adstypes_id,
                locale: locale
            });
        }
        $RecoveryCategoriesIds.on('change', function (event) {
            //@#console.info('$RecoveryCategoriesIds change', $(this).val());
            var $This = $(this);
            var dataAjaxUrl = '';
            var recovery_category_id = event.target.value;
            if (recovery_category_id.length > 0 && 0 != recovery_category_id) {
                setHasSuccess($RecoveryCategoriesIds);

                //@#console.log('recovery_category_id.length');
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
                    $('option',$RecoveryAdstypesId).each(function() {
                        var text = $(this).text();

                        if (text.search(/engine/i) != -1) {
                            //console.log('>> engine <<');
                            $RecoveryAdstypesId.val($(this).val());
                            setHasSuccess($RecoveryAdstypesId);
                        } else {
                            unsetHasSuccess($RecoveryAdstypesId, true);
                        }
                    });
                    unsetHasSuccess($RecoveryYearBuilt, true);
                    unsetHasSuccess($RecoveryModelsId);
                    unsetHasSuccess($RecoveryManufacturersId, true);
                } else {
                    // boats
                }

                if($RecoverySubcategoriesIds.length) {
                    dataAjaxUrl = $RecoverySubcategoriesIds[0].hasAttribute("data-ajax--url") ? $RecoverySubcategoriesIds.attr('data-ajax--url') : 'gateway_subcategories';
                    ajaxFill(dataAjaxUrl, $RecoverySubcategoriesIds, {
                        category_id: recovery_category_id,
                        locale: locale
                    });
                    unsetHasSuccess($RecoverySubcategoriesIds);
                }
            } else {
                unsetHasSuccess($RecoveryCategoriesIds, true);

                if($RecoverySubcategoriesIds.length) {
                    unsetHasSuccess($RecoverySubcategoriesIds);
                    $RecoverySubcategoriesIds.val(null);
                    $RecoverySubcategoriesIds.trigger('change');
                }

                $RecoveryManufacturersId.val(null);
                $RecoveryManufacturersId.trigger('change');
            }

            var recovery_adstypes_id = $RecoveryAdstypesId.val() ? $RecoveryAdstypesId.val() : 0;
            var recovery_subcategory_id = $RecoverySubcategoriesIds.val() ? $RecoverySubcategoriesIds.val() : 0;

            dataAjaxUrl = $RecoveryManufacturersId[0].hasAttribute("data-ajax--url") ? $RecoveryManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $RecoveryManufacturersId, {
                    adstypes_id: recovery_adstypes_id,
                    category_id: recovery_category_id,
                    subcategory_id: recovery_subcategory_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        request = '/ajax-gateway_manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($RecoveryManufacturersId, request);
                });
            }
            $RecoveryManufacturersId.val(null).trigger('change');
        });
    }

    /*
     * Ads's Subcategories
     */
    if ($RecoverySubcategoriesIds.length) {
        dataAjaxUrl = '';
        recovery_category_id = $RecoveryCategoriesIds.val();
        recovery_subcategory_id = $RecoverySubcategoriesIds.val();

        if (!recovery_category_id) {
            unsetHasSuccess($RecoverySubcategoriesIds);
        } else if (recovery_category_id && recovery_subcategory_id) {
            setHasSuccess($RecoverySubcategoriesIds);
        } else if ('undefined' != recovery_category_id && !recovery_subcategory_id) {
            dataAjaxUrl = $RecoverySubcategoriesIds[0].hasAttribute("data-ajax--url") ? $RecoverySubcategoriesIds.attr('data-ajax--url') : 'gateway_subcategories';
            ajaxFill(dataAjaxUrl, $RecoverySubcategoriesIds, {
                category_id: recovery_category_id,
                locale: locale
            });
        }

        $RecoverySubcategoriesIds.on('change', function (event)     {
            //@#console.info('$RecoverySubcategoriesIds change', $(this).val());
            //var $This = $(this);
            var dataAjaxUrl = '';
            var recovery_adstypes_id = $RecoveryAdstypesId.val() ? $RecoveryAdstypesId.val() : 0;
            var recovery_category_id = $RecoveryCategoriesIds.val() ? $RecoveryCategoriesIds.val() : 0;
            var recovery_subcategory_id = event.target.value;

            if (recovery_subcategory_id.length > 0 && 0 != recovery_subcategory_id) {
                setHasSuccess($RecoverySubcategoriesIds);
            } else if(!recovery_category_id && !recovery_subcategory_id){
                unsetHasSuccess($RecoverySubcategoriesIds);
            } else if( !recovery_subcategory_id){
                unsetHasSuccess($RecoverySubcategoriesIds, true);
            }

            dataAjaxUrl = $RecoveryManufacturersId[0].hasAttribute("data-ajax--url") ? $RecoveryManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $RecoveryManufacturersId, {
                    adstypes_id: recovery_adstypes_id,
                    category_id: recovery_category_id,
                    subcategory_id: recovery_subcategory_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        request = '/ajax-gateway_manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($RecoveryManufacturersId, request);
                });
            }
            $RecoveryManufacturersId.val(null).trigger('change');
        });
    }

    /*
     * Ads's Manufacturers
     */
    if ($RecoveryManufacturersId.length) {
        dataAjaxUrl = '';
        recovery_adstypes_id = $RecoveryAdstypesId.val() ? $RecoveryAdstypesId.val() : 0;
        recovery_category_id = $RecoveryCategoriesIds.val() ? $RecoveryCategoriesIds.val() : 0;
        recovery_subcategory_id = $RecoverySubcategoriesIds.val() ? $RecoverySubcategoriesIds.val() : 0;
        recovery_manufacturer_id = $RecoveryManufacturersId.val();
        var request = '';
        if (!recovery_manufacturer_id) {
            dataAjaxUrl = $RecoveryManufacturersId[0].hasAttribute("data-ajax--url") ? $RecoveryManufacturersId.attr('data-ajax--url') : 'gateway_manufacturers';
            if (!/ajax-/.test(dataAjaxUrl)) {
                ajaxFill(dataAjaxUrl, $RecoveryManufacturersId, {
                    adstypes_id: recovery_adstypes_id,
                    category_id: recovery_category_id,
                    subcategory_id: recovery_subcategory_id,
                    locale: locale
                }, function () {
                    var request = '';
                    if (/gateway/.test(dataAjaxUrl)) {
                        request = '/ajax-gateway_manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                    } else {
                        //request = '/ajax-manufacturer?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                        request = dataAjaxUrl;
                    }
                    updateSelect2Element($RecoveryManufacturersId, request);
                });
            } else {
                request = dataAjaxUrl + '?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
                updateSelect2Element($RecoveryManufacturersId, request, false);
            }
        } else {
            dataAjaxUrl = $RecoveryManufacturersId[0].hasAttribute("data-ajax--url") ? $RecoveryManufacturersId.attr('data-ajax--url') : '/ajax-gateway_manufacturer';
            request = dataAjaxUrl + '?adstypes_id=' + recovery_adstypes_id + '&category_id=' + recovery_category_id + '&subcategory_id=' + recovery_subcategory_id;
            updateSelect2Element($RecoveryManufacturersId, request, false);
            $RecoveryManufacturersId.val(recovery_manufacturer_id).trigger('change');
        }

        $RecoveryManufacturersId.on('change', function (event) {
            //@#console.info("$RecoveryManufacturersId.on('change'");
            //var $This = $(this);
            var dataAjaxUrl = '';
            var recovery_manufacturers_id = event.target.value;
            if (recovery_manufacturers_id.length > 0 && 0 != recovery_manufacturers_id) {

                setHasSuccess($RecoveryManufacturersId);
                //dataAjaxUrl = $RecoveryModelsId[0].hasAttribute("data-ajax--url") ? $RecoveryModelsId.attr('data-ajax--url') : 'gateway_models';
                dataAjaxUrl = $RecoveryModelsId[0].hasAttribute("ajax-url") ? $RecoveryModelsId.attr('ajax-url') : 'gateway_models';
                ajaxFill(dataAjaxUrl, $RecoveryModelsId, {
                    manufacturers_id: recovery_manufacturers_id,
                    locale: locale
                }, function () {
                    $RecoveryModelsId.select2({allowClear: true, minimumResultsForSearch: 10});
                    if (/ajax-/.test(dataAjaxUrl)) {
                        $RecoveryModelsId.val(null).trigger('change');
                    }
                });
            } else {
                unsetHasSuccess($RecoveryModelsId);
                unsetHasSuccess($RecoveryManufacturersId, true);
            }
        });
    }

    /*
     * Ads's Models
     */
    if ($RecoveryModelsId.length) {
        recovery_models_id = $RecoveryModelsId.val();
        recovery_manufacturers_id = $RecoveryManufacturersId.val();
        recovery_adstypes_id = $RecoveryAdstypesId.val();
        if (!recovery_manufacturers_id) {
            unsetHasSuccess($RecoveryModelsId);
            $RecoveryModelsId.val(null).trigger('change');
        } else {
            $RecoveryModelsId.select2({allowClear: true, minimumResultsForSearch: 10});
            $RecoveryModelsId.val(recovery_models_id).trigger('change');
        }

        $RecoveryModelsId.on('change', function (event) {
            //@#console.info("$RecoveryModelsId.on('change'");
            //var $This = $(this);

            var recovery_models_id = event.target.value;
            var recovery_manufacturers_id = $RecoveryManufacturersId.val();
            if (recovery_models_id.length > 0 && 0 != recovery_models_id) {

                setHasSuccess($RecoveryModelsId);
            } else if(!recovery_manufacturers_id && !recovery_models_id){
                unsetHasSuccess($RecoveryModelsId);
            } else if(recovery_manufacturers_id && !recovery_models_id) {
                if ($RecoveryModelsId.find('option').length) {
                    unsetHasSuccess($RecoveryModelsId, true);
                } else {
                    unsetHasSuccess($RecoveryModelsId);
                }
            } else {
                unsetHasSuccess($RecoveryModelsId);
            }
        });
    }

});
