<?php
/**
 * 라우트 설정 파일(routes.php)
 *
 * 이 파일에서는 컨트롤러와 액션을 URL과 연결할 수 있습니다.
 * CakePHP의 라우팅을 사용하면 원하는 URL을 특정 컨트롤러와 액션에 매핑할 수 있습니다.
 *
 * 이 파일은 `Application::routes()` 메서드에서 `RouteBuilder` 인스턴스 `$routes`를 통해 로드됩니다.
 *
 * MIT 라이선스 하에 제공됩니다.
 * 전체 라이선스 내용은 LICENSE.txt 파일에서 확인할 수 있습니다.
 * 파일을 재배포할 때는 저작권 표시를 유지해야 합니다.
 * 
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;


/*
 * 이 파일은 'Application' 클래스의 컨텍스트에서 로드됩니다.
 * 따라서 필요한 경우 '$this'를 사용하여 애플리케이션 클래스 인스턴스를 참조할 수 있습니다.
 */
return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
 
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

        $builder->connect('/pages/*', 'Pages::display');

        $builder->fallbacks();
    });


};
