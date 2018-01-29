<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\Form\FormRenderer;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Component\Translation\Loader\YamlFileLoader;

use Rpodwika\Silex\YamlConfigServiceProvider;

define("APP_ROOT", __DIR__ . "/");
define('CONF_FILES', __DIR__ . "/../config/");
define('UPLOAD_FILES', __DIR__ . "/../web/upload/");

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new RoutingServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new LocaleServiceProvider());

// Forms
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new YamlConfigServiceProvider(CONF_FILES . "settings.yml"));
$app->register(new YamlConfigServiceProvider(CONF_FILES . "database.yml"));

// Forms
$app['form.extensions'] = $app->extend('form.extensions', function ($extensions) use ($app) {
    $managerRegistry = new forms\ManagerRegistry(null, array(), array('orm.em'), null, null, '\Doctrine\ORM\Proxy\Proxy');
    $managerRegistry->setContainer($app);
    $extensions[] = new DoctrineOrmExtension($managerRegistry);
    $extensions[] = new forms\TypesExtension($app);

    return $extensions;
});

$app['form.type.extensions'] = $app->extend('form.type.extensions', function ($extensions) use ($app) {
    $extensions[] = new forms\extensions\FileTypeExtension($app);
    return $extensions;
});

$app['twig.runtimes'] = $app->extend('twig.runtimes', function ($runtimes, $app) {
    return array_merge($runtimes, [
        FormRenderer::class => 'twig.form.renderer',
    ]);
});

// Database
$cache = new \Doctrine\Common\Cache\ArrayCache;
$app->register(new DoctrineServiceProvider(), ['db.options' => $app['config']['database']]);

$dbConfig = new Configuration;
$dbConfig->setMetadataCacheImpl($cache);
$driverImpl = $dbConfig->newDefaultAnnotationDriver(APP_ROOT . "models");
$dbConfig->setMetadataDriverImpl($driverImpl);
$dbConfig->setQueryCacheImpl($cache);
$dbConfig->setProxyDir(__DIR__."/proxies");
$dbConfig->setProxyNamespace('Dorm\Proxies');
$dbConfig->setAutoGenerateProxyClasses(true);

$app['orm.em'] = EntityManager::create($app['db'], $dbConfig);

// Session
$app['session.storage.handler'] = null;

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());

    return $twig;
});

// Translation
$app->register(new TranslationServiceProvider(), [
    "locale_fallback" => ['en']
]);

$app["translator"] = $app->extend("translator", function($translator, $app) {
    $translator->addLoader("yaml", new YamlFileLoader());
    $translator->addResource("yaml", CONF_FILES . "ph.yml", 'ph');

    return $translator;
});

// security
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^.*$',
            'anonymous' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'logout' => array('logout_path' => '/logout'),
            'users' => function() use($app) {
                return new helpers\UserProvider($app);
           	}
        ),
    ),
    'security.access_rules' => array(
        array('^/login', ['IS_AUTHENTICATED_ANONYMOUSLY']),
        array('^/', ['ROLE_ADMIN']),
    )
));

$encoder = $app['security.default_encoder'];

// echo  $encoder->encodePassword('password', '');
// die;

return $app;
