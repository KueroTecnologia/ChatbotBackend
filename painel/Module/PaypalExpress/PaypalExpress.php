<?php


declare(strict_types=1);

namespace KED\Module\PaypalExpress;

use KED\Services\Sale\Order;
use KED\Module\Checkout\PaymentMethod\OnlinePaymentAbstract;

class PaypalExpress extends OnlinePaymentAbstract
{
    private $code = 'paypal_express';

    private $name = 'Paypal Express';

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return "This is free shipping method";
    }

    public function isRedirectRequired()
    {
        return true;
    }

    public function getRedirectUrl(Order $order = null)
    {
        return build_url('checkout/paypal/express/redirect');
    }

    public function getReturnUrl()
    {
        return build_url('checkout/paypal/express/return');
    }

    public function getCancelUrl()
    {
        return build_url('checkout/paypal/express/cancel');
    }

    public function canRefundOnline()
    {
        return true;
    }

    public function getIcon()
    {
        return false;
    }
}