<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
/**
 *
 * @var FactoryDefault
 */
$di = new FactoryDefault ();
/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared ( 'url', function () use($config) {
	$url = new UrlResolver ();
	$url->setBaseUri ( $config->application->baseUri );
	
	return $url;
} );

/**
 * Setting up the view component
 */
$di->setShared ( 'view', function () use($config) {
	
	$view = new View ();
	
	$view->setViewsDir ( $config->application->viewsDir );
	
	$view->registerEngines ( array (
			'.volt' => function ($view, $di) use($config) {
				
				$volt = new VoltEngine ( $view, $di );
				
				$volt->setOptions ( array (
						'compiledPath' => $config->application->cacheDir,
						'compiledSeparator' => '_' 
				) );
				
				return $volt;
			},
			'.phtml' => 'Phalcon\Mvc\View\Engine\Php' 
	) );
	
	return $view;
} );

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared ( 'db', function () use($config) {
	$dbConfig = $config->database->toArray ();
	$adapter = $dbConfig ['adapter'];
	unset ( $dbConfig ['adapter'] );
	
	$class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;
	
	return new $class ( $dbConfig );
} );

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared ( 'modelsMetadata', function () {
	return new MetaDataAdapter ();
} );

/**
 * Start the session the first time some component request the session service
 */
$di->setShared ( 'session', function () {
	$session = new SessionAdapter ();
	$session->start ();
	
	return $session;
} );
/**
 * register dispatcher
 */
$di->setShared ( 'dispatcher', function () {
	
	// create an events manager
	$eventsManager = new EventsManager ();
	
	// listen for events produced in dispatcher using sercurityplugin
	$eventsManager->attach ( 'dispatch:beforeExecuteRoute', new SercurityPlugin () );
	
	$eventsManager->attach ( 'dispatch:beforeException', new NotFoundPlugin () );
	
	$dispatcher = new Dispatcher ();
	$dispatcher->setEventsManager ( $eventsManager );
	return $dispatcher;
} );
/**
 * register flash
 */
$di->setShared ( 'flash', function () {
	return new FlashSession ();
} );
/**
 * Register to use component
 */
$di->setShared ( 'elements', function () {
	return new Elements();
} );
		
		
		
		 	