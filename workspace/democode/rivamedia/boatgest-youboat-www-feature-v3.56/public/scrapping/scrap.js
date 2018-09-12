var bridge = 'www.boatshop24.co.uk';
var ads_total_pages = 0;
var links = [];
jQuery.ajaxSetup({
    async:true,
    timeout: 3000,
    retryAfter:7000
});

var jqOnError = function(xhr, textStatus, errorThrown ) {
    if (typeof this.tryCount !== "number") {
        this.tryCount = 1;
    }
    if (textStatus === 'timeout') {
        if (this.tryCount < 3) {  /* hardcoded number */
            this.tryCount++;
            //try again
            $.ajax(this);
            return;
        }
        return;
    }
    if (xhr.status === 500) {
        //handle error
    } else {
        //handle error
    }
};

var url_referrer = 'http://www.boatshop24.co.uk/';

//01
function get_ads_total_pages() {

    if(!$('#steps').length) {
        $Steps = $('<div>', {'id': 'steps'});
        $('body').css({'background':'#FFF'}).html('');
        $('body').append($Steps);
        $Output = $('<div>', {'id': 'output'});
        $('body').append($Output);
        $Errors = $('<div>', {'id': 'errors'});
        $('body').append($Errors);
    }
    /*
        https://api.import.io/store/connector/3091ce5a-5fc8-435c-ae24-c7473c02fcf2/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Forder%3Dlatest%26list_type%3Dtable%26page%3D1%26url%3Dmaxdim&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb
        https://api.import.io/store/connector/3091ce5a-5fc8-435c-ae24-c7473c02fcf2/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Forder%3Dlatest%26list_type%3Dtable%26page%3D1%26url%3Dmaxdim&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb
     */
    var  api_url        = 'https://api.import.io/store/connector/';
    var  api_name       = '3091ce5a-5fc8-435c-ae24-c7473c02fcf2';
    var  api_params     = '/_query?input=webpage/url:';
    var  api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';
         api_key        = '&&_apikey=' + api_key;
    //var  scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale?order=latest&list_type=table&page=1';
    var  scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale/uk?order=latest&list_type=table&page=1';
    var  api_full_url   = api_url + api_name + api_params + encodeURIComponent(scrapping_url) + api_key;
    console.log('api_full_url', api_full_url);

    var  ajax_url = api_full_url;
    var  ajax_url = '/scrapping/scrapping_api.php?api_full_url='+ encodeURIComponent(api_full_url);
    //var  ajax_url = 'https://extraction.import.io/query/extractor/a105bd82-206b-473f-b6cc-63f4900fdc0c?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26order%3Dnewest%26page%3D1';
    /*var  api_url = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_full_url=https%3A%2F%2Fextraction.import.io%2Fquery%2Fextractor%2Fa105bd82-206b-473f-b6cc-63f4900fdc0c%3F_apikey%3D7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb%26url%3Dhttp%253A%252F%252Fwww.boatshop24.co.uk%252Fboats-for-sale%253Flist_type%253Dtable%2526order%253Dnewest%2526page%253D1&bridge=www.boatshop24.co.uk',*/
    console.log('ajax_url :: ' + ajax_url);

    $.ajax({
        async: true,
        url: ajax_url,
        complete: function (response) {
            //console.info('response');
            //console.log(response);
            if(response.status == 200) {

                var obj = $.parseJSON(response.responseText);
                if(obj.errorType) {
                    console.log('get_ads_total_pages: ' + obj.error +' ! for : ' + ajax_url);
                    $('#errors').append('<br>' + 'get_ads_total_pages: ' + obj.error +' ! for : ' + ajax_url);
                    setTimeout ( function(){ get_ads_total_pages() }, $.ajaxSetup().retryAfter );
                } else if(obj.results){
                    console.info('01 : ' + bridge + '_results_pages');
                    $('#errors').html('--');
                    $('#steps').append('<h2>' + '01 : ' + bridge + '_results_pages' + '</h2>');

                    var results = obj.results[0];
                    var ad_total_results = results.ad_total_results;
                    var ads_total_pages = results.ad_total_pages;
                    console.log('get_ads_total_pages for : ' + ajax_url);
                    console.log('ad_total_results : ' + ad_total_results);
                    console.log('ads_total_pages : ' + ads_total_pages);
                    $('#output').append('<br>01 : ' + 'get_ads_total_pages for : ' + ajax_url);
                    $('#output').append('<br>01 : ' + 'ad_total_results : ' + ad_total_results);
                    $('#output').append('<br>01 : ' + 'ads_total_pages : ' + ads_total_pages);

                    if (ads_total_pages > 0) {
                        $('#errors').html('--');
                        console.info('02 : ' + 'get_ads_links');
                        $('#steps').append('<h3>' + '02 : ' + 'get_ads_links' + ' for : ' + ajax_url + '</h3>');
                        get_ads_links(ads_total_pages);
                    } else {
                        console.log('02 : No pages! for : ' + ajax_url);
                        $('#errors').append('<br>02 : '  + 'get_ads_links' + 'No pages! for : ' + ajax_url);
                    }
                }
            } else if(response.statusText == 'error') {
                console.log('02 : get_ads_total_pages: Ajax error! for : ' + ajax_url);
                $('#errors').append('<br>01 : ' + 'get_ads_total_pages: Ajax error! for : ' + ajax_url);
            } else {
                console.log('02 : get_ads_total_pages: unknown error! for : ' + ajax_url);
                $('#errors').append('<br>01 : ' + 'get_ads_total_pages : unknown error! for : ' + ajax_url);
            }
        },
        error: function () {
            console.log('02 : get_ads_total_pages: there was an error! for : ' + ajax_url);
            $('#errors').append('<br>01 : ' + 'get_ads_total_pages : there was an error! for : ' + ajax_url);
            setTimeout ( function(){ get_ads_total_pages() }, $.ajaxSetup().retryAfter );
        }
    });
    return false;
}

// 02
function get_ads_links(ads_total_pages) {
    ads_total_pages = 1;
    if(!$('#steps').length) {
        $Steps = $('<div>', {'id': 'steps'});
        $('body').css({'background':'#FFF'}).html('');
        $('body').append($Steps);
        $Output = $('<div>', {'id': 'output'});
        $('body').append($Output);
        $Errors = $('<div>', {'id': 'errors'});
        $('body').append($Errors);
    }

    var  api_url        = 'https://api.import.io/store/connector/';
    var  api_name       = 'fb098754-e9b8-45a8-9215-7f954ef8d08e';
    var  api_params     = '/_query?input=webpage/url:';
    var  api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';
    api_key        = '&&_apikey=' + api_key;

    if (ads_total_pages > 0) {
        var array_links = [];
        var i = 1;

        for (i; i < ads_total_pages +1; i++) {
            (function(ads_page_counter){
                //var ajax_url = 'https://extraction.import.io/query/extractor/e6138c59-0785-48b2-8df1-09a810fb34a3?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26order%3Dnewest%26page%3D' + ads_page_counter;
                var  scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale/uk?order=latest&list_type=table&page=' + i;
                var  api_full_url   = api_url + api_name + api_params + encodeURIComponent(scrapping_url) + api_key;
                console.log('scrapping_url',scrapping_url);
                console.log('api_full_url',api_full_url);

                var  ajax_url = api_full_url;
                var  ajax_url = '/scrapping/scrapping_api.php?api_full_url='+ encodeURIComponent(api_full_url);

                console.log('ajax_url :: ' + ajax_url);

                var request =  $.ajax({
                    async: true,
                    url: ajax_url,
                    complete: function (response) {
                        if(response.status == 200) {

                            var obj = $.parseJSON(response.responseText);

                            if (obj.errorType) {
                                console.log('02: ' + 'get_ads_links: ' + obj.error + ' ! for : ' + ajax_url);
                                $('#errors').append('<br>02: ' + 'get_ads_links: ' + obj.error + ' ! for : ' + ajax_url);
                                setTimeout ( function(){ get_ads_links(i) }, $.ajaxSetup().retryAfter );
                            } else if (obj.results) {
                                $('#errors').html('--');
                                console.info('02-' + ads_page_counter + '_get_ads_links' + ' for : ' + ajax_url);
                                $('#steps').append('<h4>' + '02-' + ads_page_counter + '_get_ads_links' + ' for : ' + ajax_url + '</h4>');

                                var results = obj.results;
                                var ad_title = '';
                                var ad_url = '';
                                console.log('02: results.length :: ' + results.length);
                                console.log(results);
                                if (results.length > 0) {
                                    (function (array) {
                                        $.each(array, function (index, val) {
                                            ad_title = val.ad_title;
                                            ad_url = val.ad_url;
                                            array_links[index] = {
                                                'ad_title': ad_title,
                                                'ad_url': ad_url
                                            };
                                        })
                                        links = links.concat(array_links);
                                    })(results);

                                    console.log('02: links.length :: ' + links.length);
                                    console.log(links);
                                    if (links.length > 0 && links.length == ads_total_pages * results.length) {
                                        console.log(ads_page_counter, links.length);
                                        console.info('03 : ' + 'get_ads_detail' + ' for ' + ajax_url);
                                        $('#errors').html('--');
                                        $('#steps').append('<h3>' + '03 : ' + 'get_ads_detail' + ' for : ' + ajax_url + '</h3>');
                                        get_ads_detail(links);
                                    } else {
                                        console.log('03 : ' + 'get_ads_detail' + ' No links! for : ' + ajax_url);
                                        $('#errors').append('<br>03: ' + 'get_ads_detail' + ' No links! for : ' + ajax_url);
                                    }
                                } else {
                                    console.log('03 : ' + 'get_ads_detail' + 'No links! for : ' + ajax_url);
                                    $('#errors').append('<br>03: ' + 'get_ads_detail' + ' No links! for : ' + ajax_url);
                                }
                            }
                        } else if (response.statusText == 'error') {
                            console.log('get_ads_links: Ajax error! for : ' + ajax_url);
                            $('#errors').append('<br>02: ' + 'get_ads_links : Ajax error! for : ' + ajax_url);
                        } else {
                            console.log('get_ads_links: unknown error! for : ' + ajax_url);
                            $('#errors').append('<br>02: ' + 'get_ads_links : unknown error! for : ' + ajax_url);
                        }
                    },
                    error: function () {
                        console.log('get_ads_links: there was an error! for : ' + ajax_url);
                        $('#errors').append('<br>02: ' + 'get_ads_links: there was an error! for : ' + ajax_url);
                        setTimeout ( function(){ get_ads_links(i) }, $.ajaxSetup().retryAfter );
                    }
                });
            })(i);
        }
    }
}

// 03
function get_ads_detail(links) {
    if(!$('#steps').length) {
        $Steps = $('<div>', {'id': 'steps'});
        $('body').css({'background':'#FFF'}).html('');
        $('body').append($Steps);
        $Output = $('<div>', {'id': 'output'});
        $('body').append($Output);
        $Errors = $('<div>', {'id': 'errors'});
        $('body').append($Errors);
    }

    var  api_url        = 'https://api.import.io/store/connector/';
    var  api_name       = '00992084-552b-486b-803e-98969b240c8d';
    var  api_params     = '/_query?input=webpage/url:';
    var  api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';
    api_key        = '&&_apikey=' + api_key;

    // bis_2_boatshop24_co_uk-ads_details
    // https://api.import.io/store/connector/91a48cdc-8e23-4061-b315-df1215e57b65/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fcanal-narrow-boats%2Fsea-otter-27%2F108695&&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb
    var  api_name       = '91a48cdc-8e23-4061-b315-df1215e57b65';

    /*var links = [

        {
             "ad_url": "http://www.boatshop24.co.uk/fishing-boats/quintrex-420-estuary-angler/99194",
             "ad_url/_text": "Quintrex - 420 Estuary Angler",
             "ad_url/_source": "/fishing-boats/quintrex-420-estuary-angler/99194",
             "ad_title": "Quintrex - 420 Estuary Angler"
        }
    ];*/
    function getDetails (index, ad_url) {
        //var ajax_url = 'https://extraction.import.io/query/extractor/65b6b991-1a9c-4de8-be49-00cd57a759e7?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=' + encodeURIComponent(ad_url);

        var  scrapping_url  = ad_url;
        var  api_full_url   = api_url + api_name + api_params + encodeURIComponent(scrapping_url) + api_key;
        console.log('api_full_url',api_full_url);

        var  ajax_url = api_full_url;
        var  ajax_url = '/scrapping/scrapping_api.php?api_full_url='+ encodeURIComponent(api_full_url);

        console.log('ajax_url :: ' + ajax_url);

        $.ajax({
            async: false,
            url: ajax_url,
            complete: function (response) {
                if(response.status == 200) {

                    var obj = $.parseJSON(response.responseText);

                    if (obj.errorType) {
                        console.log('03: ' + 'get_ads_detail: ' + obj.error + ' ! for : ' + ajax_url);
                        $('#errors').append('<br>03: ' + 'get_ads_detail: ' + obj.error + ' ! for : ' + ajax_url);
                        setTimeout ( function(){ getDetails(index, ad_url) }, $.ajaxSetup().retryAfter );
                    } else if (obj.results) {
                        $('#errors').html('--');
                        console.info('03-' + index + '_get_ads_detail' + ' for : ' + ajax_url);
                        $('#steps').append('<h4>' + '03-' + index + '_get_ads_detail' + ' for : ' + ajax_url + '</h4>');
                        console.info('[03-' + index + '] ' + ad_title + ' : ' + ad_url);
                        $('#output').append('<p>[03-' + index + '] ' + ad_title + ' : ' + ad_url + '</p>');

                        var results = obj.results;
                        if (results.length > 0) {
                            $('#errors').html('--');
                            //console.log(results[0]);
                            var details = results[0];

                            /*
                             // ad_dealer_name:
                             // ad_dealer_url:
                             // ad_description_caracts:
                             // ad_description_caracts_labels:
                             // ad_description_caracts_values:
                             // ad_description_full:
                             // ad_location:
                             // ad_phones:
                             // ad_photo:
                             // ad_photos:
                             // ad_photos_thumbs:
                             // ad_price:
                             // ad_price_descr:
                             // ad_ref:
                             // ad_specifications_caracts:
                             ad_specifications_caracts_labels:
                             ad_specifications_caracts_values:
                             // ad_specifications_full:
                             //ad_title:

                             ad_type_cat_name:
                             ad_type_cat_url:
                             ad_sales_status:
                             ad_manufacturer_name:
                             ad_manufacturer_url:
                             ad_model_name:
                             ad_model_url:
                             ad_mooring_country:
                             ad_sale_type_condition:
                             ad_sale:
                             ad_year_built:
                             ad_length:
                             ad_width:
                             ad_propulsion:
                             ad_nb_engines:
                             */

                            //console.info('typeof details.ad_type_cat_name', typeof details.ad_type_cat_name);
                            ///////////////////////
                            // ad_type_cat_name
                            if('undefined' != typeof details.ad_type_cat_name) {
                                var ad_type_cat_name = details.ad_type_cat_name;
                                //console.log('ad_type_cat_name');
                                //console.log(ad_type_cat_name);
                            }

                            //console.info('typeof details.ad_type_cat_url', typeof details.ad_type_cat_url);
                            ///////////////////////
                            // ad_type_cat_url
                            if('undefined' != typeof details.ad_type_cat_url) {
                                var ad_type_cat_url = details.ad_type_cat_url;
                                //console.log('ad_type_cat_url');
                                //console.log(ad_type_cat_url);
                            }

                            //console.info('typeof details.ad_sales_status', typeof details.ad_sales_status);
                            ///////////////////////
                            // ad_sales_status
                            if('undefined' != typeof details.ad_sales_status) {
                                var ad_sales_status = details.ad_sales_status;
                                //console.log('ad_sales_status');
                                //console.log(ad_sales_status);
                            }

                            //console.info('typeof details.ad_manufacturer_url', typeof details.ad_manufacturer_url);
                            ///////////////////////
                            // ad_manufacturer_url
                            if('undefined' != typeof details.ad_manufacturer_url) {
                                var ad_manufacturer_url = details.ad_manufacturer_url;
                                //console.log('ad_manufacturer_url');
                                //console.log(ad_manufacturer_url);
                            }

                            //console.info('typeof details.ad_manufacturer_name', typeof details.ad_manufacturer_name);
                            ///////////////////////
                            // ad_manufacturer_name
                            if('undefined' != typeof details.ad_manufacturer_name) {
                                var ad_manufacturer_name = details.ad_manufacturer_name;
                                //console.log('ad_manufacturer_name');
                                //console.log(ad_manufacturer_name);
                            }

                            //console.info('typeof details.ad_model_name', typeof details.ad_model_name);
                            ///////////////////////
                            // ad_model_name
                            if('undefined' != typeof details.ad_model_name) {
                                var ad_model_name = details.ad_model_name;
                                //console.log('ad_model_name');
                                //console.log(ad_model_name);
                            }

                            //console.info('typeof details.ad_model_url', typeof details.ad_model_url);
                            ///////////////////////
                            // ad_model_url
                            if('undefined' != typeof details.ad_model_url) {
                                var ad_model_url = details.ad_model_url;
                                //console.log('ad_model_url');
                                //console.log(ad_model_url);
                            }

                            //console.info('typeof details.ad_mooring_country', typeof details.ad_mooring_country);
                            ///////////////////////
                            // ad_mooring_country
                            if('undefined' != typeof details.ad_mooring_country) {
                                var ad_mooring_country = details.ad_mooring_country;
                                //console.log('ad_mooring_country');
                                //console.log(ad_mooring_country);
                            }

                            //console.info('typeof details.ad_sale_type_condition', typeof details.ad_sale_type_condition);
                            ///////////////////////
                            // ad_sale_type_condition
                            if('undefined' != typeof details.ad_sale_type_condition) {
                                var ad_sale_type_condition = details.ad_sale_type_condition;
                                //console.log('ad_sale_type_condition');
                                //console.log(ad_sale_type_condition);
                            }

                            //console.info('typeof details.ad_sale', typeof details.ad_sale);
                            ///////////////////////
                            // ad_sale
                            if('undefined' != typeof details.ad_sale) {
                                var ad_sale = details.ad_sale;
                                //console.log('ad_sale');
                                //console.log(ad_sale);
                            }

                            //console.info('typeof details.ad_year_built', typeof details.ad_year_built);
                            ///////////////////////
                            // ad_year_built
                            if('undefined' != typeof details.ad_year_built) {
                                var ad_year_built = details.ad_year_built;
                                //console.log('ad_year_built');
                                //console.log(ad_year_built);
                            }

                            //console.info('typeof details.ad_length', typeof details.ad_length);
                            ///////////////////////
                            // ad_length
                            if('undefined' != typeof details.ad_length) {
                                var ad_length = details.ad_length;
                                //console.log('ad_length');
                                //console.log(ad_length);
                            }

                            //console.info('typeof details.ad_width', typeof details.ad_width);
                            ///////////////////////
                            // ad_width
                            if('undefined' != typeof details.ad_width) {
                                var ad_length = details.ad_width;
                                //console.log('ad_width');
                                //console.log(ad_width);
                            }

                            //console.info('typeof details.ad_propulsion', typeof details.ad_propulsion);
                            ///////////////////////
                            // ad_propulsion
                            if('undefined' != typeof details.ad_propulsion) {
                                var ad_propulsion = details.ad_propulsion;
                                //console.log('ad_propulsion');
                                //console.log(ad_propulsion);
                            }

                            //console.info('typeof details.ad_nb_engines', typeof details.ad_nb_engines);
                            ///////////////////////
                            // ad_nb_engines
                            if('undefined' != typeof details.ad_nb_engines) {
                                var ad_propulsion = details.ad_nb_engines;
                                //console.log('ad_nb_engines');
                                //console.log(ad_nb_engines);
                            }

                            //console.info('typeof details.ad_ref', typeof details.ad_ref);
                            ///////////////////////
                            // ad_ref
                            if('undefined' != typeof details.ad_ref) {
                                var ad_ref = details.ad_ref;
                                //console.log('ad_ref');
                                //console.log(ad_ref);
                            }

                            //console.info('typeof details.ad_title', typeof details.ad_title);
                            ///////////////////////
                            // ad_title
                            if('undefined' != typeof details.ad_title) {
                                var ad_title = details.ad_title;
                                //console.log('ad_title');
                                //console.log(ad_title);
                            }

                            //console.info('typeof details.ad_location', typeof details.ad_location);
                            ///////////////////////
                            // ad_location
                            if('undefined' != typeof details.ad_location) {
                                var ad_location = details.ad_location;
                                //console.log('ad_location');
                                //console.log(ad_location);
                            }

                            //console.info('typeof details.ad_price', typeof details.ad_price);
                            ///////////////////////
                            // ad_price
                            if('undefined' != typeof details.ad_price) {
                                var ad_price = details.ad_price;
                                //console.log('ad_price');
                                //console.log(ad_price);
                            }

                            //console.info('typeof details.ad_price_descr', typeof details.ad_price_descr);
                            ///////////////////////
                            // ad_price_descr
                            if('undefined' != typeof details.ad_price_descr) {
                                var ad_price_descr = details.ad_price_descr;
                                //console.log('ad_price_descr');
                                //console.log(ad_price_descr);
                            }

                            //console.info('typeof details.ad_phones', typeof details.ad_phones);
                            ///////////////////////
                            // ad_phones
                            if('undefined' != typeof details.ad_phones) {
                                var ad_phones = details.ad_phones;
                                //console.log('ad_phones');
                                //console.log(ad_phones);
                            }

                            //console.info('typeof details.ad_photo', typeof details.ad_photo);
                            ///////////////////////
                            // ad_photo
                            if('undefined' != typeof details.ad_photo) {
                                var ad_photo = details.ad_photo;
                                //console.log('ad_photo');
                                //console.log(ad_photo);
                            }

                            //console.info('typeof details.ad_photos', typeof details.ad_photos);
                            ///////////////////////
                            // ad_photos
                            if('undefined' != typeof details.ad_photos) {
                                var ad_photos = details.ad_photos;
                                //console.log('ad_photos');
                                //console.log(ad_photos);
                            }

                            //console.info('typeof details.ad_photos_thumbs', typeof details.ad_photos_thumbs);
                            ///////////////////////
                            // ad_photos_thumbs
                            if('undefined' != typeof details.ad_photos_thumbs) {
                                var ad_photos_thumbs = details.ad_photos_thumbs;
                                //console.log('ad_photos_thumbs');
                                //console.log(ad_photos_thumbs);
                            }

                            //console.info('typeof details.ad_description_caracts', typeof details.ad_description_caracts);
                            ///////////////////////
                            // ad_description_caracts
                            if('undefined' != typeof details.ad_description_caracts) {
                                var ad_description_caracts = details.ad_description_caracts;
                                //console.info('ad_description_caracts');
                                //console.log(ad_description_caracts);
                            }

                            //console.info('typeof details.ad_description_full', typeof details.ad_description_full);
                            ///////////////////////
                            // ad_description_full
                            if('undefined' != typeof details.ad_description_full) {
                                var ad_description_full = details.ad_description_full;
                                //console.log('>>ad_description_full');
                                ad_description_full = ad_description_full.replace(ad_description_caracts, '').replace(/^\s+|\s+$/g, '').replace(/(\n )/gm, '\n');
                                //console.log(ad_description_full);
                            }


                            //console.info('typeof details.ad_description_caracts_labels', typeof details.ad_description_caracts_labels);
                            ///////////////////////
                            // ad_description_caracts_labels
                            if('undefined' != typeof details.ad_description_caracts_labels) {
                                var ad_description_caracts_labels = details.ad_description_caracts_labels;

                                var array_labels = [];
                                var caracts_labels = [];
                                if (ad_description_caracts_labels.length > 0) {
                                    //(function (array) {
                                        //$.each(array, function (index, val) {
                                        $.each(ad_description_caracts_labels, function (index, val) {
                                            array_labels[index] = val.replace(/(:)/gm, '');
                                        })
                                        caracts_labels = caracts_labels.concat(array_labels);
                                    //})(ad_description_caracts_labels);
                                }
                                if (caracts_labels.length > 0) {
                                    //console.log('caracts_labels');
                                    //console.log(caracts_labels);
                                } else {
                                    //console.log('no caracts_labels');
                                }
                            }

                            //console.info('typeof details.ad_description_caracts_values', typeof details.ad_description_caracts_values);
                            ///////////////////////
                            // ad_description_caracts_values
                            if('undefined' != typeof details.ad_description_caracts_values) {
                                var ad_description_caracts_values = details.ad_description_caracts_values;
                                //console.log('ad_description_caracts_values');
                                //console.log(ad_description_caracts_values);
                                var ad_description_caracts = [];
                                if (caracts_labels.length > 0 && ad_description_caracts_values.length > 0) {
                                    //(function (array) {
                                        //$.each(array, function (index, val) {
                                        $.each(caracts_labels, function (index, val) {
                                            var caracts_key = val;
                                            var caracts_value = ad_description_caracts_values[index];
                                            //console.log('caracts_key', caracts_key);
                                            //console.log('caracts_value', caracts_value);

                                            ad_description_caracts[caracts_key] = caracts_value;
                                        })
                                    //})(caracts_labels);
                                }
                                //console.log('ad_description_caracts');
                                //console.log(ad_description_caracts);
                            }

                            //console.info('typeof details.ad_specifications_caracts', typeof details.ad_specifications_caracts);
                            ///////////////////////
                            // ad_specifications_caracts
                            if('undefined' != typeof details.ad_specifications_caracts) {
                                var ad_specifications_caracts = details.ad_specifications_caracts;
                                //console.info('ad_specifications_caracts');
                                //console.log(ad_specifications_caracts);
                            }

                            //console.info('typeof details.ad_specifications_full', typeof details.ad_specifications_full);
                            ///////////////////////
                            // ad_specifications_full
                            if('undefined' != typeof details.ad_specifications_full) {
                                var ad_specifications_full = details.ad_specifications_full;
                                //console.log('>>ad_specifications_full');
                                ad_specifications_full = ad_specifications_full.replace(ad_specifications_caracts, '').replace(/^\s+|\s+$/g, '').replace(/(\n )/gm, '\n');
                                //console.log(ad_specifications_full);
                            }

                            //console.info('typeof details.ad_specifications_caracts_labels', typeof details.ad_specifications_caracts_labels);
                            ///////////////////////
                            // ad_specifications_caracts_labels
                            if('undefined' != typeof details.ad_specifications_caracts_labels) {
                                var ad_specifications_caracts_labels = details.ad_specifications_caracts_labels;

                                var array_labels = [];
                                var specifications_caracts_labels = [];
                                if (ad_specifications_caracts_labels.length > 0) {
                                    //(function (array) {
                                        //$.each(array, function (index, val) {
                                        $.each(ad_specifications_caracts_labels, function (index, val) {
                                            array_labels[index] = val.replace(/(:)/gm, '');
                                        })
                                        specifications_caracts_labels = specifications_caracts_labels.concat(array_labels);
                                    //})(ad_specifications_caracts_labels);
                                }
                                if (specifications_caracts_labels.length > 0) {
                                    //console.log('specifications_caracts_labels');
                                    //console.log(specifications_caracts_labels);
                                } else {
                                    //console.log('no specifications_caracts_labels');
                                }
                            }

                            //console.info('typeof details.ad_specifications_caracts_values', typeof details.ad_specifications_caracts_values);
                            ///////////////////////
                            // ad_specifications_caracts_values
                            if('undefined' != typeof details.ad_specifications_caracts_values) {
                                var ad_specifications_caracts_values = details.ad_specifications_caracts_values;
                                //console.log('ad_specifications_caracts_values');
                                //console.log(ad_specifications_caracts_values);
                                var ad_specifications_caracts = [];
                                if (specifications_caracts_labels.length > 0 && ad_specifications_caracts_values.length > 0) {
                                    //(function (array) {
                                        //$.each(array, function (index, val) {
                                        $.each(specifications_caracts_labels, function (index, val) {
                                            var specifications_caracts_key = val;
                                            var specifications_caracts_value = ad_specifications_caracts_values[index];
                                            //console.log('specifications_caracts_key', specifications_caracts_key);
                                            //console.log('specifications_caracts_value', specifications_caracts_value);

                                            ad_specifications_caracts[specifications_caracts_key] = specifications_caracts_value;
                                        })
                                    //})(specifications_caracts_labels);
                                }
                                //console.log('ad_specifications_caracts');
                                //console.log(ad_specifications_caracts);
                            }

                            //console.info('typeof details.ad_dealer_name', typeof details.ad_dealer_name);
                            ///////////////////////
                            // ad_dealer_name
                            if('undefined' != typeof details.ad_dealer_name) {
                                var ad_dealer_name = details.ad_dealer_name;
                                //console.log('ad_dealer_name');
                                //console.log(ad_dealer_name);
                            }

                            //console.info('typeof details.ad_dealer_url', typeof details.ad_dealer_url);
                            ///////////////////////
                            // ad_dealer_url
                            if('undefined' != typeof details.ad_dealer_url) {
                                var ad_dealer_url = details.ad_dealer_url;
                                //console.log('ad_dealer_url');
                                //console.log(ad_dealer_url);
                            }

                            var final_object = {
                                'ad_ref' : ad_ref,
                                'ad_title' : ad_title,
                                'ad_location' : ad_location,
                                'ad_price' : ad_price,
                                'ad_type_cat_name' : ad_type_cat_name,
                                'ad_type_cat_url' : ad_type_cat_url,
                                'ad_sales_status' : ad_sales_status,
                                'ad_manufacturer_name' : ad_manufacturer_name,
                                'ad_manufacturer_url' : ad_manufacturer_url,
                                'ad_model_name' : ad_model_name,
                                'ad_model_url' : ad_model_url,
                                'ad_mooring_country' : ad_mooring_country,
                                'ad_sale_type_condition' : ad_sale_type_condition,
                                'ad_sale' : ad_sale,
                                'ad_year_built' : ad_year_built,
                                'ad_length' : ad_length,
                                'ad_width' : ad_width,
                                'ad_propulsion' : ad_propulsion,
                                'ad_nb_engines' : ad_nb_engines,
                                'ad_price_descr' : ad_price_descr,
                                'ad_phones' : ad_phones,
                                'ad_photo' : url_referrer + ad_photo,
                                'ad_photos' : ad_photos,
                                'ad_photos_thumbs' : ad_photos_thumbs,
                                'ad_description' : ad_description_full,
                                'ad_description_caracts' : ad_description_caracts,
                                'ad_specifications' : ad_specifications_full,
                                'ad_specifications_caracts' : ad_specifications_caracts,
                                'ad_dealer_name' : ad_dealer_name,
                                'ad_dealer_url' : url_referrer + ad_dealer_url
                            }
                            console.log('final_object', final_object);
                        } else {
                            $('#errors').append('<br>03: ' + 'get_ads_detail ' + 'No Ad\'s detail! for : ' + ajax_url);
                        }
                    }
                } else if (response.statusText == 'error') {
                    console.log('get_ads_links: Ajax error! for : ' + ajax_url);
                    $('#errors').append('<br>03: ' + 'get_ads_detail : Ajax error! for : ' + ajax_url);
                } else {
                    console.log('get_ads_links: unknown error! for : ' + ajax_url);
                    $('#errors').append('<br>03: ' + 'get_ads_detail : unknown error! for : ' + ajax_url);
                }
            },
            error: function () {
                console.log('03' + 'get_ads_detail: there was an error for : ' + ajax_url);
                $('#errors').append('<br>03' + 'get_ads_detail: there was an error for : ' + ajax_url);
                setTimeout ( function(){ getDetails(index, ad_url) }, $.ajaxSetup().retryAfter );
            },
        });
    }
    if (links.length>0) {
        (function(array) {
            $.each(array, function (index, val) {
                //console.log(index,val.ad_url);
                ad_title = val.ad_title;
                ad_url = val.ad_url;
                //$('#steps').append('<h4>' + '03-' + index + ' : ' + bridge + '_ads_detail' + '</h4>');

                getDetails (index, ad_url);
                //if (index == 1) return false;
            });
        })(links);
    }
}
