<?php

namespace controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $route = $app['controllers_factory'];
        $route->match('/', "controllers\\Provider::index")->bind('homepage');
        $route->match('/login', "controllers\\Provider::login")->bind('login');

        return $route;
    }

    public function index(Application $app, Request $request, $slug = null)
    {
        $view = [
            'title' => 'Dashboard'
        ];

        return $app['twig']->render('index.html.twig', $view);
    }

    public function login(Application $app, Request $request)
    {
        $role = NULL;
        $token = $app['security.token_storage']->getToken();

        if (null !== $token) {
            $user = $token->getUser();
            if(is_object($user)) {
                $role = $user->getRoles();
            }

            if ($app['security.authorization_checker']->isGranted($role)) {
                // return Tools::redirect($app, 'dashboard');
            }
        }

        $view = array(
            'title' => ucfirst($request->get('_route')),
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username')
        );

        return $app['twig']->render('login.twig', $view);
    }
}