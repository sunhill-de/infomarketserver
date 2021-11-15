<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Sunhill\InfoMarket\Market\InfoMarket;

use Sunhill\InfoMarket\Marketeers\System\System;
use Sunhill\InfoMarket\Marketeers\System\Disk;
use Sunhill\InfoMarket\Marketeers\System\CPU;

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
        $market->installMarketeer(System::class);
        $market->installMarketeer(CPU::class);
        $market->installMarketeer(Disk::class);
        
        $data = $market->readItem($path);
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    });
            
    $app->get('/itemlist/{path}', function (Request $request, Response $response, $args) {
        $path = $args['path'];
            
        $market = new InfoMarket();
        
        
        $data = $market->readItem($path);
        $response->getBody()->write($data);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->notFound(function () use ($app) {
        $app->halt(404,json_encode(['status'=>'failed','error_code'=>'NOINTERFACE','error_message'=>'The REST interface is not defined.']));
    }
                   
        try {
            $app->run();
        } catch (\Exception $e) {
            $app->halt(500,json_encode(['status'=>'failed','error_code'=>'EXCEPTION','error_message'=>'$e->getMessage()]));
        }
