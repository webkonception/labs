<?php
    $website_youboat_url = !empty($website_name) ? $website_name : config('youboat.' . $country_code . '.website_youboat_url');

    $_TotalVisitorsAndPageViewsByPath = false;
    if(isset($path) && isset($chartTotalVisitorsAndPageViewsByPath) && !empty($chartTotalVisitorsAndPageViewsByPath)) {
        $_TotalVisitorsAndPageViewsByPath = true;

        $tabLists = '';
        $tabPanels = '';
        $i = 0;
        $TableTotalPageViews = '<table class="table table-bordered table-striped table-hover">';
        $TableTotalVisitors = '<table class="table table-bordered table-striped table-hover">';

        $TableTotalPageViews .= '    <thead><tr><th colspan="2"><h3><strong class="text-primary">Total Page Views</strong></h3></td></tr>';
        $TableTotalVisitors .= '    <thead><tr><th colspan="2"><h3><strong class="text-primary">Total Visitors</strong></h3></th></tr></thead>';

        $TableTotalPageViews .= '    <tbody>';
        $TableTotalVisitors .= '    <tbody>';

        $totalPageViewsTmp = 0;
        $totalPageVisitorsTmp = 0;
        foreach($chartTotalVisitorsAndPageViewsByPath as $k => $v) {
            if(isset($chartTotalVisitorsAndPageViewsByPath[$k]) && !empty($chartTotalVisitorsAndPageViewsByPath[$k])) {
                $chart = $chartTotalVisitorsAndPageViewsByPath[$k];

                $totalPageViews = (isset($chart->datasets[1]['options']['total']) ? $chart->datasets[1]['options']['total'] : '');
                $totalPageVisitors = (isset($chart->datasets[0]['options']['total']) ? $chart->datasets[0]['options']['total'] : '');

                $iconPageViews = $iconPageVisitors = $colorPageViews = $colorPageVisitors = '';
                $colorLabelPageViews = $colorLabelPageVisitors = 'primary';
                if(preg_match('/^Current/', $k)) {
                    $iconPageViews = $iconPageVisitors =  '<i class="text-danger fa fa-arrow-circle-down fa-2x fa-fw"></i>';
                    '<i class="text-danger fa fa-arrow-circle-down fa-2x fa-fw"></i>';
                    $colorPageViews = $colorPageVisitors = 'text-danger';
                    $colorLabelPageViews = $colorLabelPageVisitors = 'danger';

                    if($totalPageViews > $totalPageViewsTmp) {
                        $iconPageViews =  '<i class="text-success fa fa-arrow-circle-up fa-2x fa-fw"></i>';
                        $colorPageViews = 'text-success';
                        $colorLabelPageViews = 'success';
                    }

                    if($totalPageVisitors > $totalPageVisitorsTmp) {
                        $iconPageVisitors = '<i class="text-success fa fa-arrow-circle-up fa-2x fa-fw"></i>';
                        $colorPageVisitors = 'text-success';
                        $colorLabelPageVisitors = 'success';
                    }

                    if($totalPageViews == $totalPageViewsTmp) {
                        $iconPageViews =  '<i class="text-warning fa arrows-h fa-2x fa-fw"></i>';
                        $colorPageViews = 'text-warning';
                        $colorLabelPageViews = 'warning';
                    }

                    if($totalPageVisitors == $totalPageVisitorsTmp) {
                        $iconPageVisitors = '<i class="text-warning fa arrows-h fa-2x fa-fw"></i>';
                        $colorPageVisitors = 'text-warning';
                        $colorLabelPageVisitors = 'warning';
                    }
                }
                $TableTotalPageViews .= '<tr><td class="'. $colorPageViews . '"><strong>' . $k . '</strong>' . $iconPageViews . '</td><td class="text-right"><span class="label label-' . $colorLabelPageViews . '">' . $totalPageViews . '</span></td></tr>';
                $TableTotalVisitors .= '<tr><td class="'. $colorPageVisitors . '"><strong>' . $k . '</strong>' . $iconPageVisitors . '</td><td class="text-right"><span class="label label-' . $colorLabelPageVisitors . '">' . $totalPageVisitors . '</span></td></tr>';

                $totalPageViewsTmp = $totalPageViews;
                $totalPageVisitorsTmp = $totalPageVisitors;

                $tabLists .= '<li role="presentation" ' . ($i == 0 ? 'class="active"' : '') . '><a href="#' . $k . '_path" aria-controls="' . $k . '" role="tab" data-toggle="tab">' . $k . '</a></li>';
                $chart_render = $chart->render();
                $tabPanels .=
                        '<div role="tabpanel" class="tab-pane ' . ($i == 0 ? 'active' : '') . '" id="' . $k . '_path">' . "\n" .
                        '    <div class="row">' . "\n" .
                        '        <div class="col-sm-12">' . "\n" .
                        '           ' . $chart_render . "\n" .
                        '        </div>' . "\n" .
                        '    </div>' . "\n" .
                        '</div>' . "\n";
                unset($chart_render);

                $i++;
            }
        }
        $TableTotalPageViews .= '    </tbody>';
        $TableTotalVisitors .= '    </tbody>';

        $TableTotalPageViews .= '</table>';
        $TableTotalVisitors .= '</table>';
    }

    $_TotalVisitorsAndPageViews = false;
    if(isset($chartTotalVisitorsAndPageViews) && !empty($chartTotalVisitorsAndPageViews)) {
        $_TotalVisitorsAndPageViews = true;

        $tabLists = '';
        $tabPanels = '';
        $i = 0;
        $TableTotalPageViews = '<table class="table table-bordered table-striped table-hover">';
        $TableTotalVisitors = '<table class="table table-bordered table-striped table-hover">';

        $TableTotalPageViews .= '    <thead><tr><th colspan="2"><h3><strong class="text-primary">Total Page Views</strong></h3></td></tr>';
        $TableTotalVisitors .= '    <thead><tr><th colspan="2"><h3><strong class="text-primary">Total Visitors</strong></h3></th></tr></thead>';

        $TableTotalPageViews .= '    <tbody>';
        $TableTotalVisitors .= '    <tbody>';

        $totalPageViewsTmp = 0;
        $totalPageVisitorsTmp = 0;
        foreach($chartTotalVisitorsAndPageViews as $k => $v) {
            if(isset($chartTotalVisitorsAndPageViews[$k]) && !empty($chartTotalVisitorsAndPageViews[$k])) {
                $chart = $chartTotalVisitorsAndPageViews[$k];

                $totalPageViews = (isset($chart->datasets[1]['options']['total']) ? $chart->datasets[1]['options']['total'] : '');
                $totalPageVisitors = (isset($chart->datasets[0]['options']['total']) ? $chart->datasets[0]['options']['total'] : '');

                $iconPageViews = $iconPageVisitors = $colorPageViews = $colorPageVisitors = '';
                $colorLabelPageViews = $colorLabelPageVisitors = 'primary';
                if(preg_match('/^Current/', $k)) {
                    $iconPageViews = $iconPageVisitors =  '<i class="text-danger fa fa-arrow-circle-down fa-2x fa-fw"></i>';
                    '<i class="text-danger fa fa-arrow-circle-down fa-2x fa-fw"></i>';
                    $colorPageViews = $colorPageVisitors = 'text-danger';
                    $colorLabelPageViews = $colorLabelPageVisitors = 'danger';

                    if($totalPageViews > $totalPageViewsTmp) {
                        $iconPageViews =  '<i class="text-success fa fa-arrow-circle-up fa-2x fa-fw"></i>';
                        $colorPageViews = 'text-success';
                        $colorLabelPageViews = 'success';
                    }

                    if($totalPageVisitors > $totalPageVisitorsTmp) {
                        $iconPageVisitors = '<i class="text-success fa fa-arrow-circle-up fa-2x fa-fw"></i>';
                        $colorPageVisitors = 'text-success';
                        $colorLabelPageVisitors = 'success';
                    }

                    if($totalPageViews == $totalPageViewsTmp) {
                        $iconPageViews =  '<i class="text-warning fa arrows-h fa-2x fa-fw"></i>';
                        $colorPageViews = 'text-warning';
                        $colorLabelPageViews = 'warning';
                    }

                    if($totalPageVisitors == $totalPageVisitorsTmp) {
                        $iconPageVisitors = '<i class="text-warning fa arrows-h fa-2x fa-fw"></i>';
                        $colorPageVisitors = 'text-warning';
                        $colorLabelPageVisitors = 'warning';
                    }
                }
                $TableTotalPageViews .= '<tr><td class="'. $colorPageViews . '"><strong>' . $k . '</strong>' . $iconPageViews . '</td><td class="text-right"><span class="label label-' . $colorLabelPageViews . '">' . $totalPageViews . '</span></td></tr>';
                $TableTotalVisitors .= '<tr><td class="'. $colorPageVisitors . '"><strong>' . $k . '</strong>' . $iconPageVisitors . '</td><td class="text-right"><span class="label label-' . $colorLabelPageVisitors . '">' . $totalPageVisitors . '</span></td></tr>';

                $totalPageViewsTmp = $totalPageViews;
                $totalPageVisitorsTmp = $totalPageVisitors;

                $tabLists .= '<li role="presentation" ' . ($i == 0 ? 'class="active"' : '') . '><a href="#' . $k . '" aria-controls="' . $k . '" role="tab" data-toggle="tab">' . $k . '</a></li>';

                $chart_render = $chart->render();
                $tabPanels .=
                        '<div role="tabpanel" class="tab-pane ' . ($i == 0 ? 'active' : '') . '" id="' . $k . '">' . "\n" .
                        '    <div class="row">' . "\n" .
                        '        <div class="col-sm-12">' . "\n" .
                        '           ' . $chart_render . "\n" .
                        '        </div>' . "\n" .
                        '    </div>' . "\n" .
                        '</div>' . "\n";
                $i++;
            }
        }
        $TableTotalPageViews .= '    </tbody>';
        $TableTotalVisitors .= '    </tbody>';

        $TableTotalPageViews .= '</table>';
        $TableTotalVisitors .= '</table>';
    }
    //if(isset($chartGlobal) && !empty($chartGlobal)) {
        ////var_dump(json_decode(json_encode($chartGlobal), true));
        //var_dump(json_decode(json_encode($chartEnquiryBOD), true)['labels']);
        //var_dump(json_decode(json_encode($chartEnquiryBOD), true)['values']);
    //}
    $_EnquiryBOD = false;
    if(isset($chartEnquiryBOD) && !empty($chartEnquiryBOD)) {
        $_EnquiryBOD = true;
        ////var_dump(json_decode(json_encode($chartEnquiryBOD), true));
        //var_dump(json_decode(json_encode($chartEnquiryBOD), true)['labels']);
        //var_dump(json_decode(json_encode($chartEnquiryBOD), true)['values']);
    }

    $_Contact = false;
    if(isset($chartContact) && !empty($chartContact)) {
        $_Contact = true;
        ////var_dump(json_decode(json_encode($chartContact), true));
        //var_dump(json_decode(json_encode($chartContact), true)['labels']);
        //var_dump(json_decode(json_encode($chartContact), true)['values']);
    }

    $_BODsTotalPie = false;
    if(isset($chartBODsTotalPie) && !empty($chartBODsTotalPie)) {
        $_BODsTotalPie = true;
        ////var_dump(json_decode(json_encode($chartBODsTotalPie), true));
        //var_dump(json_decode(json_encode($chartBODsTotalPie), true)['labels']);
        //var_dump(json_decode(json_encode($chartBODsTotalPie), true)['values']);
    }

    $_BODsByMonth = false;
    if(isset($chartBODsByMonth) && !empty($chartBODsByMonth)) {
        $_BODsByMonth = true;
        ////var_dump(json_decode(json_encode($chartBODsByMonth), true));
        //var_dump(json_decode(json_encode($chartBODsByMonth), true)['labels']);
        //var_dump(json_decode(json_encode($chartBODsByMonth), true)['values']);
    }

    $_AdsCaractsByMonth = false;
    if(isset($chartAdsCaractsByMonth) && !empty($chartAdsCaractsByMonth)) {
        $_AdsCaractsByMonth = true;
    }

    $_ProspectiveCustomersPie = false;
    if(isset($chartProspectiveCustomersPie) && !empty($chartProspectiveCustomersPie)) {
        $_ProspectiveCustomersPie = true;
    }

    $_ProspectiveCustomersByMonth = false;
    if(isset($chartProspectiveCustomersByMonth) && !empty($chartProspectiveCustomersByMonth)) {
        $_ProspectiveCustomersByMonth = true;
        ////var_dump(json_decode(json_encode($chartProspectiveCustomersByMonth), true));
        //var_dump(json_decode(json_encode($chartProspectiveCustomersByMonth), true)['labels']);
        //var_dump(json_decode(json_encode($chartProspectiveCustomersByMonth), true)['values']);
    }

    $_TotalBods = false;
    if(isset($Total) && is_array($Total) && array_key_exists('bods', $Total) && !empty($Total['bods'])) {
        $_TotalBods = true;
    }

    $_TotalEnquiry = false;
    if(isset($Total) && is_array($Total) && array_key_exists('enquiry', $Total) && !empty($Total['enquiry'])) {
        $_TotalEnquiry = true;
    }

    $_TotalBodsTransfo = false;
    if(isset($Total) && is_array($Total) && array_key_exists('bods_transfo', $Total) && !empty($Total['bods_transfo'])) {
        $_TotalBodsTransfo = true;
    }

    $_TotalContact = false;
    if(isset($Total) && is_array($Total) && array_key_exists('contact', $Total) && !empty($Total['contact'])) {
        $_TotalContact = true;
    }

    $_adEvents = false;
    if(isset($ad_id) && isset($adEvents) && !empty($adEvents)) {
        $_adEvents = true;
    }
    $_TotalEvents = false;
    if(isset($Total) && is_array($Total) && array_key_exists('events', $Total) && !empty($Total['events'])) {
        $_TotalEvents = true;
        $detail_max_lines = 50;

        $_TotalEventDealerDetails = false;
        if(is_array($Total['events']) && array_key_exists('dealer_details', $Total['events']) && !empty($Total['events']['dealer_details'])) {
            arsort($Total['events']['dealer_details']);
            $_TotalEventDealerDetails = true;
        }

//        $_TotalEventSell = false;
//        if(is_array($Total['events']) && array_key_exists('sell', $Total['events']) && !empty($Total['events']['sell'])) {
//            arsort($Total['events']['sell']);
//            $_TotalEventSell = true;
//        }

        $_TotalEventSendEnquiry = false;
        if(is_array($Total['events']) && array_key_exists('send_enquiry', $Total['events']) && !empty($Total['events']['send_enquiry'])) {
            arsort($Total['events']['send_enquiry']);
            $_TotalEventSendEnquiry = true;
        }

        $_TotalEventViewThePhoneNumber = false;
        if(is_array($Total['events']) && array_key_exists('view_the_phone_number', $Total['events']) && !empty($Total['events']['view_the_phone_number'])) {
            arsort($Total['events']['view_the_phone_number']);
            $_TotalEventViewThePhoneNumber = true;
        }
    }

?>
@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    @if(isset($EffectiveUrl) && !empty($EffectiveUrl))
    <p class="lead">
        <?php
        //$EffectiveUrl = getEffectiveUrl(url($website_youboat_url . '/buy/type/manufacturer/model/' . $ad_id));
        ?>
        <a href="{!! $EffectiveUrl !!}" class="blank" title="{!! $EffectiveUrl !!}">{!! $EffectiveUrl !!}<i class="fa fa-external-link-square fa-fw"></i></a>
    </p>
    @elseif(!isset($path) || !isset($ad_id))
        <p class="alert alert-danger">
            No datas available
        </p>
    @endif
    @if($_adEvents)
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">Total Events count</div>
            </div>
            <div class="portlet-body">
                <ul class="row">
                    @foreach($adEvents as $event)
                        {!! '<li class="col-sm-6"><strong>' . $event['Action'] . ':</strong> <span class="label label-success">' . $event['Total']. '</span></li>' !!}
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    @if($_TotalVisitorsAndPageViewsByPath)
        <div class="row">
            <div class="col-sm-6 table-responsive">{!! $TableTotalPageViews !!}</div>
            <div class="col-sm-6 table-responsive">{!! $TableTotalVisitors !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="TotalVisitorsAndPageViewsByPath" class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">Total Visitors And Page Views</div>
                    </div>
                    <div class="portlet-body">

                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            {!! $tabLists !!}
                        </ul>

                        <div class="tab-content">
                            {!! $tabPanels !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($isAdmin)

        @if(isset($Total) && !empty($Total))
        <div class="row">
            <div class="col-sm-6 table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead><tr><th colspan="2"><h3><strong class="text-warning">Total Acquisitions</strong></h3></th></thead>
                    <tbody>
                    @if(isset($Total['ads']) && !empty($Total['ads']))
                        <tr>
                            <td>
                                <strong class="text-primary">
                                    Ad's active for {!! $website_youboat_url !!}
                                </strong>
                                <img class="img-flag pull-right" src="{{ asset('assets/img/flags/' . mb_strtoupper($country_code) . '.png') }}" alt="{{ $country_code }}">
                            </td>
                            <td class="text-right"><span class="label label-primary">{!! $Total['ads'] !!}</span></td>
                        </tr>
                    @endif
                    @if(isset($Total['enquiry']) && !empty($Total['enquiry']))
                        <tr>
                            <td><strong>Enquiry mail sent</strong></td>
                            <td class="text-right"><span class="label label-warning">{!! $Total['enquiry'] !!}</span></td>
                        </tr>
                    @endif
                    @if(isset($Total['bods_transfo']) && !empty($Total['bods_transfo']))
                        <tr>
                            <td><strong>Enquiry Transfo to BOD</strong></td>
                            <td class="text-right"><span class="label label-warning">{!! $Total['bods_transfo'] !!}</span></td>
                        </tr>
                    @endif
                    @if(isset($Total['bods']) && !empty($Total['bods']))
                        <tr>
                            <td><strong>BODs created</strong></td>
                            <td class="text-right"><span class="label label-warning">{!! $Total['bods'] !!}</span></td>
                        </tr>
                    @endif
                    @if(isset($Total['contact']) && !empty($Total['contact']))
                        <tr>
                            <td><strong>Contact mail</strong></td>
                            <td class="text-right"><span class="label label-warning">{!! $Total['contact'] !!}</span></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            @endif
            @if($_TotalEvents)
            <div class="col-sm-6 table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead><tr><th colspan="2"><h3><strong class="text-success">Total clicks's events</strong></h3></th></thead>
                    <tbody>
                    <tr>
                        <td><strong>View the phone number clicks's events</strong></td>
                        <td class="text-right"><span class="label label-success">{!! array_sum(array_column($Total['events']['view_the_phone_number'], 'Total')) !!}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Send Enquiry clicks's events</strong></td>
                        <td class="text-right"><span class="label label-success">{!! array_sum(array_column($Total['events']['send_enquiry'], 'Total')) !!}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Look Dealer Details clicks's events</strong></td>
                        <td class="text-right"><span class="label label-success">{!! array_sum(array_column($Total['events']['dealer_details'], 'Total')) !!}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Autopromo Sell Banners clicks's events</strong></td>
                        <td class="text-right"><span class="label label-success">{!! array_sum(array_column($Total['events']['sell'], 'Total')) !!}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        <div class="row">
            @if(isset($chartGlobal) && !empty($chartGlobal))
            <div class="col-sm-8">
                {!! $chartGlobal->render() !!}
            </div>
            @endif
            @if(isset($chartGlobalPie) && !empty($chartGlobalPie))
            <div class="col-sm-4">
                {!! $chartGlobalPie->render() !!}
            </div>
            @endif
        </div>
        <hr>
        <div class="row">
            @if(isset($chartGlobalEvents) && !empty($chartGlobalEvents))
            <div class="col-sm-8">
                {!! $chartGlobalEvents->render() !!}
            </div>
            @endif
            @if(isset($chartGlobalEventsPie) && !empty($chartGlobalEventsPie))
            <div class="col-sm-4">
                {!! $chartGlobalEventsPie->render() !!}
            </div>
            @endif
        </div>

        @if($_TotalVisitorsAndPageViews)
        <div class="row">
            <div class="col-sm-6 table-responsive">{!! $TableTotalPageViews !!}</div>
            <div class="col-sm-6 table-responsive">{!! $TableTotalVisitors !!}</div>
        </div>
        @endif

        <ul class="nav nav-tabs nav-pills nav-justified" role="tablist">
        @if($_AdsCaractsByMonth)
            <li role="presentation" class="bg-danger"><a href="#ads" aria-controls="ads" role="tab" data-toggle="tab">Ads</a></li>
        @endif
        @if($_TotalVisitorsAndPageViews)
            <li role="presentation" class="bg-success"><a href="#total_visitors_and_page_views" aria-controls="total_visitors_and_page_views" role="tab" data-toggle="tab">Total Visitors And Page Views</a></li>
        @endif
        @if($_EnquiryBOD || $_BODsTotalPie || $_BODsByMonth)
            <li role="presentation" class="bg-info"><a href="#bods" aria-controls="bods" role="tab" data-toggle="tab">BODs <span class="label label-info">{!! $BODsTotal !!}</span></a></li>
        @endif
        @if($_ProspectiveCustomersPie || $_ProspectiveCustomersByMonth|| $_Contact)
            <li role="presentation" class="bg-warning"><a href="#acquisitions" aria-controls="acquisitions" role="tab" data-toggle="tab">Acquisitions</a></li>
        @endif
        </ul>

        <div class="tab-content">
            @if($_ProspectiveCustomersPie || $_ProspectiveCustomersByMonth || $_Contact)
                <div role="tabpanel" class="tab-pane" id="acquisitions">
                    <div id="Acquisitions" class="portlet box yellow">
                        <div class="portlet-body">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                            @if($_ProspectiveCustomersByMonth)
                                <li role="presentation"><a href="#prospective_customers_by_month" aria-controls="prospective_customers_by_month" role="tab" data-toggle="tab">Prospective Customers total request by month</a></li>
                            @endif
                            @if($_ProspectiveCustomersPie)
                                <li role="presentation" class="active"><a href="#prospective_customers_pie" aria-controls="prospective_customers_pie" role="tab" data-toggle="tab">Prospective Customers Total</a></li>
                            @endif
                            @if($_Contact)
                                <li role="presentation"><a href="#Contact" aria-controls="Contact" role="tab" data-toggle="tab">Contact requests</a></li>
                            @endif
                            </ul>

                            <div class="tab-content">
                            @if($_ProspectiveCustomersByMonth)
                                <div role="tabpanel" class="tab-pane" id="prospective_customers_by_month">
                                    {!! $chartProspectiveCustomersByMonth->render() !!}
                                </div>
                            @endif
                            @if($_ProspectiveCustomersPie)
                                <div role="tabpanel" class="tab-pane active" id="prospective_customers_pie">
                                    {!! $chartProspectiveCustomersPie->render() !!}
                                </div>
                            @endif
                            @if($_Contact)
                                <div role="tabpanel" class="tab-pane" id="Contact">
                                    {!! $chartContact->render() !!}
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($_TotalVisitorsAndPageViews)
                <div role="tabpanel" class="tab-pane" id="total_visitors_and_page_views">
                    <div id="TotalVisitorsAndPageViews" class="portlet box green">
                        <div class="portlet-body">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                {!! $tabLists !!}
                            </ul>
                            <div class="tab-content">
                                {!! $tabPanels !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($_EnquiryBOD || $_BODsTotalPie || $_BODsByMonth)
                <div role="tabpanel" class="tab-pane" id="bods">
                    <div id="BODs" class="portlet box blue">
                        <div class="portlet-body">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                            @if($_EnquiryBOD)
                                <li role="presentation"><a href="#EnquiryBOD" aria-controls="EnquiryBOD" role="tab" data-toggle="tab">Enquiry Transfo to BOD</a></li>
                            @endif
                            @if($_BODsTotalPie)
                                <li role="presentation" class="active"><a href="#BODsTotalPie" aria-controls="BODsTotalPie" role="tab" data-toggle="tab">BODs Total</a></li>
                            @endif
                            @if($_BODsByMonth)
                                <li role="presentation"><a href="#BODsByMonth" aria-controls="BODsByMonth" role="tab" data-toggle="tab">BODs total by month</a></li>
                            @endif
                            </ul>

                            <div class="tab-content">
                            @if($_EnquiryBOD)
                                <div role="tabpanel" class="tab-pane" id="EnquiryBOD">
                                    {!! $chartEnquiryBOD->render() !!}
                                </div>
                            @endif
                            @if($_BODsTotalPie)
                                <div role="tabpanel" class="tab-pane active" id="BODsTotalPie">
                                    {!! $chartBODsTotalPie->render() !!}
                                </div>
                            @endif
                            @if($_BODsByMonth)
                                <div role="tabpanel" class="tab-pane" id="BODsByMonth">
                                    {!! $chartBODsByMonth->render() !!}
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($_AdsCaractsByMonth)
            <div role="tabpanel" class="tab-pane" id="ads">
                <div id="ADs" class="portlet box red">
                    <div class="portlet-body">
                        <ul class="nav nav-tabs nav-justified" role="tablist">
                            @if($_AdsCaractsByMonth)
                                <li role="presentation" class="active"><a href="#ADsByMonth" aria-controls="ADsByMonth" role="tab" data-toggle="tab">Ads total by month</a></li>
                            @endif
                        </ul>

                        <div class="tab-content">
                            @if($_AdsCaractsByMonth)
                                <div role="tabpanel" class="tab-pane active" id="ADsByMonth">
                                    {!! $chartAdsCaractsByMonth->render() !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($_TotalEvents)
        <div class="row detail">
            <div class="col-sm-12">
                <h3><strong class="text-muted">Total detail for clicks's events</strong></h3>

                @if($_TotalEventDealerDetails)
                <h4 class="lead"><strong>Look Dealer Details clicks's events</strong></h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                        @foreach(array_splice($Total['events']['dealer_details'], 0, $detail_max_lines) as $k => $v)
                        <?php
                        $source = $v['Source'];
                        $url = $v['Url'];
                        //$url = preg_match("/^(http|https):\/\//i", $url) ? $url : url($website_youboat_url . '/buy/type/manufacturer/model/' . $source);
                        $url = preg_match("/^(http|https):\/\//i", $url) ? $url : url($website_youboat_url . $url);
                        $link = link_to($url, $url, ['class'=>'btn btn-link blank', 'title'=>$source]);
                        $break = explode('/', $url);
                        $ad_id = $break[count($break) - 1];
                        $ad_edit_link = '<a href="/' . config('quickadmin.route') . '/adscaracts/' . $ad_id . '/edit" class="blank btn btn-block btn-xs btn-primary"><i class="fa fa-pencil fa-fw"></i>Edit</a>';
                        ?>
                        <tr>
                            <td class="text-center"><span class="label label-info">{!! $v['Total'] !!}</span></td>
                            <td>{!! $ad_edit_link !!}</td>
                            <td><strong>{!! $link !!}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if($_TotalEventSendEnquiry)
                <br><h4 class="lead"><strong>Send Enquiry clicks's events</strong></h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                        @foreach(array_splice($Total['events']['send_enquiry'], 0, $detail_max_lines) as $k => $v)
                        <?php
                        $source = $v['Source'];
                        $url = $v['Url'];
                        //$url = preg_match("/^(http|https):\/\//i", $url) ? $url : url($website_youboat_url . '/buy/type/manufacturer/model/' . $source);
                        $url = preg_match("/^(http|https):\/\//i", $url) ? $url : url($website_youboat_url . $url);
                        $link = link_to($url, $url, ['class'=>'btn btn-link blank', 'title'=>$source]);
                        $ad_id = $break[count($break) - 1];
                        $ad_edit_link = '<a href="/' . config('quickadmin.route') . '/adscaracts/' . $ad_id . '/edit" class="blank btn btn-block btn-xs btn-primary"><i class="fa fa-pencil fa-fw"></i>Edit</a>';
                        ?>
                        <tr>
                            <td class="text-center"><span class="label label-info">{!! $v['Total'] !!}</span></td>
                            <td>{!! $ad_edit_link !!}</td>
                            <td><strong>{!! $link !!}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if($_TotalEventViewThePhoneNumber)
                <br><h4 class="lead"><strong>View the phone number clicks's events</strong></h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                        @foreach(array_splice($Total['events']['view_the_phone_number'], 0, $detail_max_lines) as $k => $v)
                        <?php
                        $source = $v['Source'];
                        $url = $v['Url'];
                        //$url = preg_match("/^(http|https):\/\//i", $url) ? $url : url($website_youboat_url . '/buy/type/manufacturer/model/' . $source);
                        $url = preg_match("/^(http|https):\/\//i", $url) ? $url : url($website_youboat_url . $url);
                        $link = link_to($url, $url, ['class'=>'btn btn-link blank', 'title'=>$source]);
                        $ad_id = $break[count($break) - 1];
                        $ad_edit_link = '<a href="/' . config('quickadmin.route') . '/adscaracts/' . $ad_id . '/edit" class="blank btn btn-block btn-xs btn-primary"><i class="fa fa-pencil fa-fw"></i>Edit</a>';
                        ?>
                        <tr>
                            <td class="text-center"><span class="label label-info">{!! $v['Total'] !!}</span></td>
                            <td>{!! $ad_edit_link !!}</td>
                            <td><strong>{!! $link !!}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
        @endif
    @endif

@endsection
@section('javascript')
    <script>
        var canvasMinHeight = '400px';
        $(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($(e.target).attr('href')).find('canvas').height(canvasMinHeight);
            });
            $NavTabs = $('.nav-tabs');
            $NavTabs.each(function() {
                var $This = $(this);
                @if($_TotalVisitorsAndPageViewsByPath)
                    $('li:last-child a', $This).tab('show');
                @else
                    $('li:eq(1) a', $This).tab('show');
                @endif
            });
        });
    </script>
@endsection