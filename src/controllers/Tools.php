<?php

namespace controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Tools
{
    public static function redirect(Application $app, $link, $msg = null)
    {
        if (!is_array($msg)) {
            $msg = ["msg" => $msg];
        }

        $url = $app['url_generator']->generate($link, $msg);

        return $app->redirect($url);
    }

    public static function findBy(Application $app, $model, $criteria = [], $sort = NULL, $limit = NULL, $offset = NULL)
    {
        $object = $app['orm.em']->getRepository('models' . $model)->findBy($criteria, $sort, $limit, $offset);

        return $object;
    }

    public static function findOneBy(Application $app, $model, $criteria = NULL)
    {
        $object = $app['orm.em']->getRepository('models' . $model)->findOneBy($criteria);

        return $object;
    }
}
