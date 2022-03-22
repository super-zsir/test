<?php

$http = new Swoole\Http\Server('0.0.0.0', 9501);

// 配置静态服务器
$http->set([
    // 虚拟目录的只想位置，只针对静态的资源  html css js 图片 视频
    'document_root' => '/www/wwwroot/web', // v4.4.0以下版本, 此处必须为绝对路径
    'enable_static_handler' => true,
]);

$http->on('Request', function ($request, $response) {

//    print_r($request->get);
//    print_r($request->post);
//    print_r($request->server);

    $req_file = $request->server['request_uri'];

//    echo $req_file;
    $filePath = '/www/wwwroot/web/'.$req_file;

    if(file_exists($filePath)) {

        ob_start();

        include $filePath;

        $html = ob_get_contents();

        ob_clean();

    }

    $response->header('Content-Type', 'text/html; charset=utf-8');
    $response->end($html);
});

$http->start();