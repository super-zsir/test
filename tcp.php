<?php
//创建Server对象，监听 127.0.0.1:9501 端口
$server = new Swoole\Server('0.0.0.0', 9501);

//设置启动的 Worker 进程数
$server->set([
   'worker_num' => 2
]);

//监听连接进入事件
$server->on('Connect', function ($server, $fd) {
    print_r($server);
    echo $fd . '\n';
    echo "Client: Connect.\n";
});

//监听数据接收事件
$server->on('Receive', function ($server, $fd, $from_id, $data) {
    // 返回给客户端的内容
     $server->send($fd, "Server: " . $data);
//    echo "接收到的数据为：\n".$data;
});

//监听连接关闭事件
$server->on('Close', function ($server, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$server->start();