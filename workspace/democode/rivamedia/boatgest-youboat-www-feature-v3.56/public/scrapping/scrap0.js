var bridge = 'www.boatshop24.co.uk';
var ads_total_pages = 0;
var links = [];
jQuery.ajaxSetup({async:true});
//01
function get_ads_total_pages() {
    var  api_url = 'https://extraction.import.io/query/extractor/a105bd82-206b-473f-b6cc-63f4900fdc0c?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26order%3Dnewest%26page%3D1';
    $.ajax({
        async: true,
        url: api_url,
        /*url : 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_full_url=https%3A%2F%2Fextraction.import.io%2Fquery%2Fextractor%2Fa105bd82-206b-473f-b6cc-63f4900fdc0c%3F_apikey%3D7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb%26url%3Dhttp%253A%252F%252Fwww.boatshop24.co.uk%252Fboats-for-sale%253Flist_type%253Dtable%2526order%253Dnewest%2526page%253D1&bridge=www.boatshop24.co.uk',*/
        complete: function (response) {
            //console.log(response);
            var obj = $.parseJSON(response.responseText);
            //console.log(obj);

            var pageData = obj.pageData;
            if(200 === pageData.statusCode){
                console.info('01 : ' + bridge + '_results_pages');
                $('#steps').append('<h2>' + '01 : ' + bridge + '_results_pages' + '</h2>');

                var extractorData = obj.extractorData;
                //console.log(extractorData);
                //console.log(obj.extractorData.url);

                var group = obj.extractorData.data[0].group;
                //console.log(group);

                ads_total_pages = group[0].ads_total_pages[0].text
                console.log('ads_total_pages :: ' + ads_total_pages);
                if (ads_total_pages > 0) {
                    console.info('02 : ' + bridge + '_ads_links');
                    $('#steps').append('<h3>' + '02 : ' + bridge + '_ads_links' + '</h3>');
                    get_ads_links(ads_total_pages);
                } else {
                    $('#output').append('<br>' + 'No pages! for : ' + api_url);
                }
            }
        },
        error: function () {
            $('#output').append('<br>' + 'Bummer_ads_total_page: there was an error! for : ' + api_url);
        }
    });
    return false;
}

// 02
function get_ads_links(ads_total_pages) {
    ads_total_pages = 1;

    if (ads_total_pages > 0) {
        var array_links = [];
        var i = 1;

        for (i; i < ads_total_pages +1; i++) {
            (function(ads_page_counter){
                var api_url = 'https://extraction.import.io/query/extractor/e6138c59-0785-48b2-8df1-09a810fb34a3?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26order%3Dnewest%26page%3D' + ads_page_counter;
                console.log('api_url :: '+api_url);
                $('#steps').append('<h4>' + '02-' + ads_page_counter + ' : ' + bridge + '_ads_links' + '</h4>');

                var request =  $.ajax({
                    async: true,
                    url: api_url,
                    complete: function (response) {
                        //console.log(response);
                        var obj = $.parseJSON(response.responseText);
                        //console.log(obj);

                        var pageData = obj.pageData;
                        if (200 === pageData.statusCode) {
                            console.info('02-' + ads_page_counter + ' : ' + bridge + '_ads_links');

                            var extractorData = obj.extractorData;
                            //console.log(extractorData);
                            //console.log(obj.extractorData.url);

                            var group = obj.extractorData.data[0].group;
                            //console.log(group);

                            var ad_title = '';
                            var ad_url = '';
                            console.log('group.length :: ' + group.length);
                            if (group.length > 0) {
                                (function(array) {
                                    $.each(array, function (index, val) {
                                        ad_title = val.ad_title[0].text;
                                        ad_url = val.ad_url[0].text;
                                        //console.log(index, ad_title, ad_url);
                                        array_links[index] = {
                                            'ad_title': val.ad_title[0].text,
                                            'ad_url': 'http://' + bridge + val.ad_url[0].text
                                        };
                                        //array_links[index] = ['url'=>'http://' + bridge + val.ad_url[0].text];
                                    })
                                    links = links.concat(array_links);
                                })(group);


                                if (links.length>0 && links.length == ads_total_pages * group.length) {
                                    console.log(ads_page_counter, links.length);
                                    $('#steps').append('<h3>' + '03 : ' + bridge + '_ads_detail' + '</h3>');
                                    get_ads_detail(links);
                                } else {
                                    $('#output').append('<br>' + 'No links! for : ' + api_url);
                                }
                            }
                        }
                    },
                    error: function () {
                        $('#output').append('<br>' + 'Bummer_ads_links: there was an error! for : ' + api_url);
                    }
                });
            })(i);
        }
    }
}
// 03
function get_ads_detail(links) {
    if (links.length>0) {
        (function(array) {
            $.each(array, function (index, val) {
                //console.log(index,val.ad_url);
                ad_title = val.ad_title;
                ad_url = val.ad_url;
                //$('#steps').append('<h4>' + '03-' + index + ' : ' + bridge + '_ads_detail' + '</h4>');

                var api_url = 'https://extraction.import.io/query/extractor/65b6b991-1a9c-4de8-be49-00cd57a759e7?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=' + encodeURIComponent(ad_url);
                console.log(api_url);
                $.ajax({
                    async: false,
                    url: api_url,
                    complete: function (response) {
                        //console.log(response);
                        var obj = $.parseJSON(response.responseText);
                        //console.log(obj);

                        var pageData = obj.pageData;
                        if(200 === pageData.statusCode){
                            console.info('03-' + index + ' : ' + bridge + '_ads_detail');
                            $('#output').append('<p>[' + index + '] ' + ad_title + ' : ' + ad_url + '</p>');
                            $('#steps').append('<h4>' + '03-' + index + ' : ' + bridge + '_ads_detail' + '</h4>');

                            var extractorData = obj.extractorData;
                            //console.log(extractorData);
                            //console.log(obj.extractorData.url);

                            var group = obj.extractorData.data[0].group;
                            if (group.length > 0) {
                                //console.log(group[0].ad_array_description[0].text);
                                //console.info('A');
                                //console.log(JSON.stringify(group[0].ad_array_description[0].text));
                                //var text = String(group[0].ad_array_description[0].text);
                                //var ad_description = text.replace(/(:\n)/gm, '":"').replace(/(\r\n|\n|\r)/gm,'","');
                                //var desc = text.replace(/(:\n)/gm, '</td><td>');
                                //text = desc.replace(/(\n)/gm,'</td></tr><tr><td>');
                                //console.log(text);
                                //console.info('B');
                                //console.log(text.replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n'));
                                console.info('C');

                                //console.log(JSON.stringify(text.replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n')));

                                //console.log(JSON.stringify(group[0].ad_array_description[0].text).replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n'));
                                //var array_desc = [text.replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n').replace(/(:\n)/gm, '" :"').replace(/(\n)/gm,'", "')];
                                //console.log(typeof array_desc);
                                //console.log(array_desc);

                                var text_ad_array_description = group[0].ad_array_description[0].text;
                                console.log(JSON.stringify(text_ad_array_description));

                                //var html_ad_array_description = '<table border="1"><tr><td>' + text_ad_array_description.replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n').replace(/(:\n)/gm, '</td><td>').replace(/(\n)/gm,'</td></tr><tr><td>') + '</td></tr></table>';
                                //console.log(ad_description);
                                console.info('D');
                                console.log(html_ad_array_description);

                                //var desc = '{"' + text.replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n').replace(/(:\n)/gm, '" :"').replace(/(\n)/gm,'", "') +'"}';
                                //console.log(desc);
                                //var obj_ad_array_description = JSON.parse(desc)
                                text_ad_array_description = '{"' + text_ad_array_description.replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n').replace(/(:\n)/gm, '" :"').replace(/(\n)/gm,'", "') +'"}';
                                console.log(text_ad_array_description);
                                var obj_ad_array_description = JSON.parse(text_ad_array_description)
                                console.log(obj_ad_array_description);
                                console.log(JSON.stringify(obj_ad_array_description));

                                //{"Type":"Fishing Boats","Manufacturer":"Romany 21","Model":"By Gordon Payne","Mooring Country":"United Kingdom","Year built":"1980","Length":"6.10 m/20 ft"}
                                var html_ad_array_description = '<table border="1"><tr><td>' + JSON.stringify(obj_ad_array_description).replace(/({")/gm, '').replace(/(":")/gm, '</td><td>').replace(/(",")/gm,'</td></tr><tr><td>').replace(/("})/gm, '') + '</td></tr></table>';

                                //console.log(JSON.parse(JSON.stringify(obj_ad_array_description)));
                                /*$.each( obj_ad_array_description, function( key, value ) {
                                    console.log( key + ": " + value );
                                });*/

                                //var ad_array_description = '<table border="1"><tr><td>' + text.replace(/(:)/gm, ':\n').replace(/(\n\n)/gm, '\n').replace(/(:\n)/gm, '</td><td>').replace(/(\n)/gm,'</td></tr><tr><td>') + '</td></tr></table>';
                                //console.log(ad_array_description);

                                //$('#output').append('<article>' + JSON.stringify(group[0].ad_array_description[0].text).replace('/\n/g','<br>') + '</article><br>');
                                $('#output').append('<article>' + html_ad_array_description + '</article><br>');
                            } else {
                                $('#output').append('<br>' + 'No Ad\'s detail! for : ' + api_url);
                            }
                        }
                    },
                    error: function () {
                        $('#output').append('<br>' + 'Bummer_ads_detail: there was an error for : ' + api_url);
                    },
                });
                if (index == 1) return false;
            });
        })(links);
    }
}
