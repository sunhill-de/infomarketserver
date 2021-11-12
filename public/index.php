<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Sunhill\InfoMarket\Market\InfoMarket;

use Sunhill\InfoMarket\Marketeers\System\Uptime;

require __DIR__ . '/../vendor/autoload.php';

    $app = AppFactory::create();
        
    $app->get('/status', function (Request $request, Response $response, $args) {
        $data = json_encode(['result'=>'OK','status'=>'working']);
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/item/{path}', function (Request $request, Response $response, $args) {
        $path = $args['path'];
        
        $market = new InfoMarket();
        $data = $market->readItem($path);
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    });
            
    $app->get('/itemlist/{path}', function (Request $request, Response $response, $args) {
        $path = $args['path'];
            
        $market = new InfoMarket();
        $market->installMarketeer(Uptime::class);
        
        $data = $market->readItem($path);
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    });
            
            $app->run();
