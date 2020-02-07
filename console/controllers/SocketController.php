<?php


namespace console\controllers;

use yii\console\Controller;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use console\components\SocketServer;


class SocketController extends Controller
{

    public function actionStart()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SocketServer()
                )
            ),
            8080
        );
        echo "Сервер запущен \r\n";

        $server->run();

        echo "Сервер остановлен \r\n";
    }

}