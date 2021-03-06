<?php
//
//
//namespace console\components;
//
//
//use Ratchet\MessageComponentInterface;
//use Ratchet\ConnectionInterface;
//use yii\helpers\VarDumper;
//
//class SocketServer implements MessageComponentInterface
//{
//    protected $clients;
//
//    public function __construct()
//    {
//        $this->clients = new \SplObjectStorage;
//    }
//
//    /**
//     * When a new connection is opened it will be passed to this method
//     * @param ConnectionInterface $conn The socket/connection that just connected to your application
//     * @throws \Exception
//     */
//    function onOpen(ConnectionInterface $conn)
//    {
//        // Store the new connection to send messages to later
//        $this->clients->attach($conn);
//        $this->sendWelcomeMessage($conn);
//
////        echo "New connection! ({$conn->resourceId})\n";
//        var_dump("New connection! ({$conn->resourceId})\n");
//    }
//
//    /**
//     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
//     * @param ConnectionInterface $conn The socket/connection that is closing/closed
//     * @throws \Exception
//     */
//    function onClose(ConnectionInterface $conn)
//    {
//        // The connection is closed, remove it, as we can no longer send it messages
//        $this->clients->detach($conn);
//
//        echo "Connection {$conn->resourceId} has disconnected\n";
//    }
//
//    public function onError(ConnectionInterface $conn, \Exception $e)
//    {
//        echo "An error has occurred: {$e->getMessage()}\n";
//
//        $conn->close();
//    }
//
//    /**
//     * Triggered when a client sends data through the socket
//     * @param \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
//     * @param string $msg The message received
//     * @throws \Exception
//     */
//    function onMessage(ConnectionInterface $from, $msg)
//    {
//        $numRecv = count($this->clients) - 1;
//        var_dump($msg);
//        var_dump('Connection %d sending message "%s" to %d other connection%s' . "\n"
//            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
////        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
////            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
//
//        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//                // The sender is not the receiver, send to each client connected
//                $client->send($msg);
//            }
//        }
//    }
//}
//
///**
// * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
// * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
// * @param ConnectionInterface $conn
// * @param \Exception $e
// * @throws \Exception
// */
//function onError(ConnectionInterface $conn, \Exception $e)
//{
//    echo "An error has occurred: {$e->getMessage()}\n";
//
//    $conn->close();
//}
//
///**
// * Triggered when a client sends data through the socket
// * @param \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
// * @param string $msg The message received
// * @throws \Exception
// */
//function onMessage(ConnectionInterface $from, $msg)
//{
//    $numRecv = count($this->clients) - 1;
//    var_dump($msg);
//    var_dump('clients:' . count($this->clients));
////    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
////        , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
//    var_dump(sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
//        , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's'));
//
//    foreach ($this->clients as $client) {
//        if ($from !== $client) {
//            // The sender is not the receiver, send to each client connected
//            $client->send($msg);
//        }
//    }
//}

/**
 * Created by PhpStorm.
 * User: evg
 * Date: 07/09/2019
 * Time: 17:43
 */

namespace console\components;


use common\models\ChatLog;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use yii\base\InvalidArgumentException;

class SocketServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        // Для хранения технической информации об присоединившихся
        // клиентах используется технология SplObjectStorage, встроенная в PHP
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->sendWelcomeMessage($conn);
        var_dump("New connection! ({$conn->resourceId})");
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        var_dump($message);
        var_dump('clients:' . count($this->clients));

        $message = json_decode($message, true);

        try {
            $type = $message['type'];
        } catch (\Throwable $exception) {
            throw new InvalidArgumentException('No type');
        }


        if ($type === 'chat') {
            $this->sendMessageToAll($message);
        } elseif ($type === 'hello') {
            $this->sendClientEnteredMessage($message);
        }

    }

    private function sendMessageToAll(array $message)
    {
        $message['created_at'] = \Yii::$app->formatter->asDatetime(time(), 'php:d.m.Y h:i:s');

        ChatLog::saveLog($message);

        foreach ($this->clients as $client) {

            $client->send(json_encode($message));
        }
    }

    private function sendClientEnteredMessage(array $message)
    {
        $clientUserName = $message['username'];
        $message['username'] = 'system';
        $message['message'] = "$clientUserName зашел в чат";

        $this->sendMessageToAll($message);
    }

    private function sendWelcomeMessage(ConnectionInterface $conn)
    {
        $message = [
            'created_at' => \Yii::$app->formatter->asDatetime(time(), 'php:d.m.Y h:i:s'),
            'username' => 'system',
            'message' => 'Добро пожаловать в чат geekbrains.ru'
        ];

        $conn->send(json_encode($message));
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

}