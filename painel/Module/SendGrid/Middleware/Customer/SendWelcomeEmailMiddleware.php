<?php


declare(strict_types=1);

namespace KED\Module\SendGrid\Middleware\Customer;


use GuzzleHttp\Promise\Promise;
use function KED\get_config;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\SendGrid\Services\SendGrid;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class SendWelcomeEmailMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, Promise $promise = null)
    {
        if (!$promise instanceof Promise)
            return $promise;

        $promise->then(function ($result) {
            if (isset($result->data['createCustomer']['status']) and $result->data['createCustomer']['status'] == true) {
                $templateId = get_config('sendgrid_customer_welcome_email');
                $this->getContainer()->get(SendGrid::class)->sendEmail(
                    'customer_welcome',
                    $result->data['createCustomer']['customer']['email'],
                    $templateId,
                    $result->data['createCustomer']['customer']
                );
            }
        });

        return $promise;
    }
}