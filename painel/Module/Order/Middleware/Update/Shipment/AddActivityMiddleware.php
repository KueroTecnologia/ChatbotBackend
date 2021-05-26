<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Update\Shipment;


use GuzzleHttp\Promise\Promise;
use Monolog\Logger;
use function KED\_mysql;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Order\Services\OrderUpdatePromise;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use function KED\subscribe;

class AddActivityMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (!$this->getContainer()->offsetExists(OrderUpdatePromise::class))
            return $delegate;
        $this->getContainer()->get(OrderUpdatePromise::class)->then(function (array $result) use ($request) {
            $conn = _mysql();
            $changes = $result['changes'];
            if (isset($changes['shipment_status']) and $changes['shipment_status'] == 'delivering')
                $conn->getTable('order_activity')->insert([
                    'order_activity_order_id' => $changes['order_id'],
                    'comment' => "Shipment is started",
                    'customer_notified' => $request->get('notify_customer') == 0 ? 0 : 1
                ]);

            if (isset($changes['shipment_status']) and $changes['shipment_status'] == 'delivered')
                $conn->getTable('order_activity')->insert([
                    'order_activity_order_id' => $changes['order_id'],
                    'comment' => "Shipment is completed",
                    'customer_notified' => $request->get('notify_customer') == 0 ? 0 : 1
                ]);
        });

        return $delegate;
    }
}