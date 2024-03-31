<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use ZxcvbnPhp\Zxcvbn;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../handlers/RegisterHandler.php';
require __DIR__ . '/../handlers/AuthorizeHandler.php';
require __DIR__ . '/../handlers/FeedHandler.php';
require 'dependencies.php';


$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::INFO));
$app = AppFactory::create();
$app->add(new LoggingMiddleware($logger));
$secretKey = $_ENV['SK'];

$app->post('/register', '\Handlers\RegisterHandler:register');
$app->post('/authorize', '\Handlers\AuthorizeHandler:authorize');
$app->get('/feed', '\Handlers\FeedHandler:feed');

$app->run();
