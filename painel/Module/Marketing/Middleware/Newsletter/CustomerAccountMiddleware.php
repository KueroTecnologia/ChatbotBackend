<?php


declare(strict_types=1);

namespace KED\Module\Marketing\Middleware\Newsletter;


use function KED\_mysql;
use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class CustomerAccountMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'newsletter_block',
            'customer_dashboard_layout',
            20,
            get_js_file_url("production/marketing/newsletter/account/newsletter.js", false),
            [
                "status" => (function () use ($request) {
                    $n = _mysql()->getTable("newsletter_subscriber")
                        ->where("email", "=", $request->getCustomer()->getData("email"))
                        ->andWhere("customer_id", "=", $request->getCustomer()->getData("customer_id"))
                        ->fetchOneAssoc();

                    return $n ? $n["status"] : "unsubscribed";
                })(),
                "email" => $request->getCustomer()->getData("email"),
                "customerId" => $request->getCustomer()->getData("customer_id"),
                "subscribeUrl" => generate_url("newsletter.subscribe"),
                "unsubscribeUrl" => generate_url("newsletter.unsubscribe")
            ]
        );

        return $delegate;
    }
}