<?php


declare(strict_types=1);

namespace KED\Middleware;

use function KED\_mysql;
use function KED\generate_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;
use KED\Module\User\Services\User;

class UserAuthenticateMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (!$request->isAdmin()) {
            return $delegate;
        }

        if ($request->getSession()->get('user_id', null) == null) {
            if ($request->attributes->get('_matched_route') != 'admin.login' &&
                $request->attributes->get('_matched_route') != 'admin.authenticate'
            ) {
                $response->redirect(generate_url('admin.login'));
                return $response;
            }
        } else {
            try {
                $user = new User($this->loadUser($request->getSession()->get('user_id', null)));
                $request->setUser($user);
            } catch (\Exception $e) {
                // Customer does not exist. need to clear session information
                $request->getSession()->set('user_id', null);
                $response->redirect($this->getContainer()->get(Router::class)->generateUrl('admin.login'));

                return $response;
            }
        }

        return $delegate;
    }

    protected function loadUser($id)
    {
        $user = _mysql()->getTable('admin_user')
            ->where('status', '=', 1)
            ->andWhere('admin_user_id', '=', $id)
            ->fetchOneAssoc();
        if ($user == false) {
            throw new \RuntimeException('User does not exist');
        }

        return $user;
    }
}