<?php
/**
 * @author Bence Borbély
 *
 * Entry point of the application
 */

//Loading the configurations
require_once 'configuration.php';

//Loading the autoloader
require_once 'vendor/autoload.php';

$mailerService = new \Bence\Service\MailerService();
$mailAction = new \Bence\ErrorHandler\Action\Mail($mailerService, DEBUG_MAIL);
$errorHandler = new \Bence\ErrorHandler\ErrorHandler(new \Bence\ErrorHandler\ErrorMessageFormatter());
$errorHandler->addAction($mailAction);
$errorHandler->register();

//database
$database = new \Bence\Database\Database(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);

//session
$sessionHandler = new \Bence\Session\DbSessionHandler($database);
$session = new \Bence\Session\Session($sessionHandler);
$session->start();

//instantiate required services
$routeResolver = new \Bence\Routing\RouteResolver();

$userProvider = new \Bence\Model\User\UserProvider($database);
$loginService = new \Bence\Service\LoginService($session, $userProvider);

$twigLoader = new Twig_Loader_Filesystem('src/View');
$twig = new Twig_Environment($twigLoader, array(
    'cache' => 'twig',
));
$template = new \Bence\Template\Twig($twigLoader, $twig);

$controllerFactory = new \Bence\ControllerFactory();

$captchaBuilder = new \Gregwar\Captcha\CaptchaBuilder();

//Building the middleware chain
$applicationMiddleware = new \Bence\Middleware\Application($template, $controllerFactory);
$accessControlMiddleware = new \Bence\Middleware\AccessControl($applicationMiddleware, $userProvider);
$authenticationMiddleware = new \Bence\Middleware\Authentication($accessControlMiddleware, $loginService, $template, $captchaBuilder);
$routerMiddleware = new \Bence\Middleware\Router($authenticationMiddleware, $routeResolver);

//Create request and response
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$request = new \GuzzleHttp\Psr7\ServerRequest($method, $uri);
$request = $request->fromGlobals();
$response = new \GuzzleHttp\Psr7\Response();

//Running the middleware chain
$response = $routerMiddleware->run($request, $response, $session);

//Sending the generated response
$responseSender = new \Bence\ResponseSender();
$responseSender->send($response);
