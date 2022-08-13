<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require "../src/config/db.php";
require "../src/config/config.php";

$app = new \Slim\App;

// API Routes...
require "../src/routes/orders.php";
require "../src/routes/discounts.php";

$app->run();