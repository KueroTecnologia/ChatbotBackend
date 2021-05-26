<?php


declare(strict_types=1);

namespace KED\Module\Marketing\Middleware\Newsletter;


use function KED\_mysql;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class UnsubscribeMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response)
    {
        $customerID = $request->request->get("customer_id");
        $customerEmail = $request->request->get("email");
        $conn = _mysql();
        try {
            if ($request->isAdmin() == false) {
                $customer = $conn->getTable("customer")->where("email", "=", $customerEmail)->andWhere("customer_id", "=", $customerID)->fetchOneAssoc();
                if (!$customer)
                    throw new \Exception("Permission denied");
                $conn->getTable("newsletter_subscriber")->where("email", "=", $customerEmail)->andWhere("customer_id", "=", $customerID)->update(["status" => "unsubscribed"]);
            } else {
                $conn->getTable("newsletter_subscriber")->where("email", "=", $customerEmail)->update(["status" => "unsubscribed"]);
            }

            $response->addData("success", 1)->addAlert("newsletter_unsubscribe_success", "success", "Email unsubscribed")->notNewPage();
        } catch (\Exception $e) {
            $response->addData("success", 0)->addAlert("newsletter_unsubscribe_error", "error", $e->getMessage())->notNewPage();
        }
    }
}