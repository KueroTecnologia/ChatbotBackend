<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Update;


use GuzzleHttp\Promise\Promise;
use Monolog\Logger;
use function KED\_mysql;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Order\Services\OrderUpdatePromise;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\PromiseWaiter;
use function KED\subscribe;

class AddActivityMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (!$promise = $this->getContainer()->get(PromiseWaiter::class)->getPromise('orderUpdate'))
            return $delegate;

        $promise->then(function (array $result) use ($request) {
            $conn = _mysql();
            $changes = $result['changes'];
            $orderId = $result['orgOrder']['order_id'];
            if (isset($changes['payment_status']) and $changes['payment_status'] == 'paid')
                $conn->getTable('order_activity')->insert([
                    'order_activity_order_id' => $orderId,
                    'comment' => "Customer paid",
                    'customer_notified' => $request->get('notify_customer') == 0 ? 0 : 1
                ]);

            if (isset($changes['payment_status']) and $changes['payment_status'] == 'refunded')
                $conn->getTable('order_activity')->insert([
                    'order_activity_order_id' => $orderId,
                    'comment' => "Refunded",
                    'customer_notified' => $request->get('notify_customer') == 0 ? 0 : 1
                ]);

            // Shipment status
            if (isset($changes['shipment_status']) and $changes['shipment_status'] == 'delivering')
                $conn->getTable('order_activity')->insert([
                    'order_activity_order_id' => $orderId,
                    'comment' => "Shipment is started",
                    'customer_notified' => $request->get('notify_customer') == 0 ? 0 : 1
                ]);

            if (isset($changes['shipment_status']) and $changes['shipment_status'] == 'delivered')
                $conn->getTable('order_activity')->insert([
                    'order_activity_order_id' => $orderId,
                    'comment' => "Shipment is completed",
                    'customer_notified' => $request->get('notify_customer') == 0 ? 0 : 1
                ]);
        });

        return $delegate;
    }
}