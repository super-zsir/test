<?php

//创建WebSocket Server对象，监听0.0.0.0:9502端口
$ws = new Swoole\WebSocket\Server('0.0.0.0', 9502);

//监听WebSocket连接打开事件
$ws->on('Open', function ($ws, $request) {
    $sendMsg = [
        'type' => 'open',
        'fd' => $request->fd
    ];

    $ws->push($request->fd, json_encode($sendMsg));
});

//监听WebSocket消息事件
$ws->on('Message', function ($ws, $frame) {

    $clientContent = json_decode($frame->data, true);

    switch ($clientContent['type']) {
        case 'login' :
            $sendMsg = [
                'type' => 'login',
                'content' => '欢迎' . $clientContent['msg'] . '进入聊天室',
                'avatar' => './images/' . mt_rand(1, 5) . '.png',
                'fd' => $frame->fd
            ];
            foreach ($ws->connections as $fd) {
                $ws->push($fd, json_encode($sendMsg));
            }

            break;
        case 'chat' :
            $sendMsg = [
               'type' => 'chat',
               'content' => $clientContent['msg'],
               'nickname' => $clientContent['nickname'],
               'avatar' => $clientContent['avatar'],
               'fd' => $frame->fd,
            ];

            foreach ($ws->connections as $fd) {
                $ws->push($fd,json_encode($sendMsg));
            }

            break;
    }

});

//监听WebSocket连接关闭事件
$ws->on('Close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
});

$ws->start();