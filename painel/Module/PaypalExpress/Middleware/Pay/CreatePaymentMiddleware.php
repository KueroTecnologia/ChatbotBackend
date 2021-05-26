<?php

declare(strict_types=1);

namespace KED\Module\PaypalExpress\Middleware\Pay;

use KED\Http\Request;
use KED\Middleware\Delegate;
use KED\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class CreatePaymentMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, callable $next, Delegate $delegate)
    {
        if ($delegate->getPaymentMethod() != 'paypal_express')
            return $next($request, $response, $delegate);

        $paypal = $delegate->getPaypalApiContext();
        $payer = $delegate->getPayer();
        $transaction = $delegate->getTransaction();
        // Redirect Urls
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(build_url('paypal_express/success'))
            ->setCancelUrl(build_url('paypal_express/failure'));

        // Payment
        $payment = new Payment();
        $payment->setIntent(get_config('checkout_paypal_payment_action', 'authorize'))
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($paypal);
        } catch (\Exception $e) {
            the_app()->get(Session::class)->getFlashBag()->add('error', $e->getMessage());
            $delegate->stopAndResponse(new RedirectResponse(build_url('checkout/index')));
            return $next($request, $response, $delegate);
        }
        $approval_url = $payment->getApprovalLink();
        $delegate->stopAndResponse(new RedirectResponse($approval_url));
        return $next($request, $response, $delegate);
    }
}