<?php
/**
 * The Front Controller for handling every request
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * MIT 라이선스 하에 제공됩니다.
 * 전체 저작권 및 라이선스 정보는 LICENSE.txt 파일에서 확인할 수 있습니다.
 * 파일을 재배포할 경우 저작권 표시를 유지해야 합니다.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.2.9
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */

// // 내장 서버용 설정
// if (PHP_SAPI === 'cli-server') {
//     $_SERVER['PHP_SELF'] = '/' . basename(__FILE__);

//     $url = parse_url(urldecode($_SERVER['REQUEST_URI']));
//     $file = __DIR__ . $url['path'];
//     if (!str_contains($url['path'], '..') && str_contains($url['path'], '.') && is_file($file)) {
//         return false;
//     }
// }
// require dirname(__DIR__) . '/vendor/autoload.php';

// use App\Application;
// use Cake\Http\Server;

// // 애플리케이션을 서버에 바인딩
// $server = new Server(new Application(dirname(__DIR__) . '/config'));

// // 요청/응답을 애플리케이션을 통해 실행하고, 응답을 전송
// $server->emit($server->run());


echo "Hello, World!";
?>
