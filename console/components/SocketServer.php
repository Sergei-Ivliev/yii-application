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
use console\models\ChatMessage;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use yii\base\InvalidArgumentException;

class SocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $message;

    public function __construct()
    {
        // Для хранения технической информации об присоединившихся
        // клиентах используется технология SplObjectStorage, встроенная в PHP
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $conn->send('join-user');
        $this->sendWelcomeMessage($conn);
        $conn->send('history');
        echo "New connection! ({$conn->resourceId})\n";
    }

    private function sendWelcomeMessage(ConnectionInterface $conn)
    {
        $conn->send(json_encode([
            'message' => 'Чат студентов geekbrains.ru',
            'username' => 'System',
            'created_datetime' => \Yii::$app->formatter->asDatetime(time())
        ]));
    }

    public function sendHistory(ConnectionInterface $from, $chatMessage)
    {
        $project_id = $chatMessage['project_id'] ?? null;
        $task_id = $chatMessage['task_id'] ?? null;
        $chatLogs = ChatLog::find()->andWhere([
            'project_id' => $project_id,
            'task_id' => $task_id
        ])->orderBy(['created_at' => SORT_DESC])->all();

        foreach ($chatLogs as $chatLog) {
            $from->send($chatLog->asJson());
        }

    }

    public function onMessage(ConnectionInterface $from, $jsonMsg)
    {
        $message = json_decode($jsonMsg, true);
        $action = $message['action'] ?? null;

        if (isset($action)) {
            if ($action === 'history') {
                $this->sendHistory($from, $message);
            } elseif ($action === 'join-user') {
                $this->sendJoinUserMessage($message);
            }
        } else {
            $this->sendChatMessageToAll($message);
        }
    }

    private function sendChatMessageToAll($message)
    {
        $chatLog = new ChatLog($message);

        $chatLog->save();

        foreach ($this->clients as $client) {
            $client->send($chatLog->asJson());
        }
    }

    private function sendJoinUserMessage($message)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'message' => 'Пользователь ' . $message['username'] . ' присоединился к чату',
                'username' => 'System',
                'created_datetime' => \Yii::$app->formatter->asDatetime(time())
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getTraceAsString()}\n";
        $conn->close();
    }

}