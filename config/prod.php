<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.form.templates'] = ['bootstrap_4_layout.twig'];
$app['twig.options'] = array(
	// 'cache' => __DIR__.'/../var/cache/twig'
);
