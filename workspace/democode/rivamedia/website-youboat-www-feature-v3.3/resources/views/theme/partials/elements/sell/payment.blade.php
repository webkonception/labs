<?php
    if(!isset($customer_email)) {
        $customer_email = '';
    }

    if(isset($StripePlan) && !empty($StripePlan)) {
        $result = json_decode(json_encode($StripePlan), true);
        $PageViewName .= '_' . $result["plan"]["id"];
        $CustomDimensions = "{'subscription_id': '" . $result["id"] . "', 'plan': '" . $result["plan"]["name"] . "', 'price': '" . ($result["plan"]["amount"]/100) . " " . $result["plan"]["currency"] . "'}";
        $titlePage .= 'Subscription to ' . $result["plan"]["name"];
    } else if(isset($plan) && !empty($plan)) {
        if(isset($plan_id) && !empty($plan_id)) {
            $PageViewName .= '_' . $plan_id;
        } else {
            $PageViewName .= '_plan-' . $plan;
        }
        $titlePage .= $plan_description;
    } else {
        $PageViewName .= '_offers';
        $titlePage .= 'Pay Offers';
    }
    $tagPage = "'$PageViewName'";

?>
<?php
    if(isset($CustomDimensions) && !empty($CustomDimensions)) {
        echo "<script>var CustomDimensions = $CustomDimensions;</script>";
    }
?>

{{--@if(isset($submitBtnPay))--}}
{{--@if(isset($ready_to_pay))--}}
    <link rel="stylesheet" type="text/css" href="/pay/css/pay.css" media="screen">
    <div class="row">
        @if(isset($pay_error) && !empty($pay_error))
        <div class="cols-sm-12">
            <div class="alert alert-danger" role="alert">
                <blockquote><?php echo $pay_error; ?></blockquote>
            </div>
        </div>
        @endif

        @if(isset($success) && $success)
            <?php
                if(isset($transaction) && !empty($transaction)) {
                    Session::put('transaction.success_msg', $success_msg);
                    Session::put('transaction.id', $transaction['id']);
                    Session::put('transaction.description', $transaction['description']);
                    Session::put('transaction.amount', $transaction['amount']);
                }
            ?>
        <div class="cols-sm-12">
            <h2 class="uppercase strong text-success text-center inbox-title"><?php echo REGLEMENT_CONFIRM_TITLE; ?></h2>
            <div class="alert alert-success" role="alert">
                <div class="row">
                    <blockquote class="col-sm-8 pay-success-msg pay-success-msg-infos">
                        <?php echo sprintf(REGLEMENT_CONFIRM_TEXT, $customer_email); ?>
                    </blockquote>
                    <?php echo $success_msg; ?>
                </div>
            </div>
        </div>
        @else
        <div class="charge col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            <div class="charge-item panel panel-success text-center">
                <div class="panel-heading">
                    <i class="fa fa-3x fa-bookmark fa-fw pull-right" aria-hidden="true"></i>
                    <h2>{!! $charge_description !!}</h2>
                </div>
                <div class="panel-body">
                    <div class="col-sm-6">
                        <div class="btn btn-block btn-sm btn-default">
                            <label for="charge">
                                {!! ($amount/100) . ' ' . config('youboat.'. $country_code .'.currency') !!}
                                <input id="charge" class="payradio" type="radio" name="charge" value="{!! $charge !!}" checked="checked">
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        {!! Form::hidden('pay_currency', $pay_currency) !!}
                        {!! Form::hidden('pay_locale', $pay_locale) !!}
                        {!! Form::hidden('amount', $amount) !!}
                        {!! Form::hidden('email', $email) !!}
                        {!! Form::hidden('charge_description', $charge_description) !!}
                        <script
                                src="https://checkout.stripe.com/checkout.js"
                                class="stripe-button"
                                data-key="{!! $stripe['publishable_key'] !!}"
                                data-email="{!! $ci_email !!}"
                                data-amount="{!! $amount !!}"
                                data-name="YOUBOAT.COM"
                                data-description="YOUBOAT.COM 2017"
                                data-image="https://uk.youboat.com/assets/img/favicons/favicon-194x194.png"
                                data-locale="{!! $pay_locale !!}"
                                data-zip-code="true"
                                data-currency="{!! $pay_currency !!}"></script>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <?php
        /*if(isset($charge) && !empty($charge)) {
            $pay_javascript = '<script>$(function(){$(\'#payform button[type="submit"]\').trigger(\'click\');});</script>' . "\n";
        }*/
    ?>
{{--@endif--}}

