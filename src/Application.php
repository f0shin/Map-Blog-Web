<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * MIT 라이선스 하에 제공됩니다.
 * 전체 저작권 및 라이선스 정보는 LICENSE.txt 파일에서 확인할 수 있으며,
 * 파일을 재배포할 경우 저작권 표시를 유지해야 합니다.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

/**
 * Application setup class.
 *
 * 애플리케이션에서 사용하려는 부트스트래핑(초기 설정) 로직과 미들웨어 레이어를 정의합니다.
 *
 * @extends \Cake\Http\BaseApplication<\App\Application>
 */
class Application extends BaseApplication
{
    /**
     * 애플리케이션의 모든 설정 및 부트스트래핑 로직을 로드합니다.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // 부모 클래스의 bootstrap 메서드를 호출하여 설정을 로드합니다.
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }
    }

    /**
     * 애플리케이션에서 사용할 미들웨어 큐를 설정합니다.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue 설정할 미들웨어 큐.
     * @return \Cake\Http\MiddlewareQueue 업데이트된 미들웨어 큐.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // 하위 계층에서 발생한 예외를 처리하고, 오류 페이지/응답을 생성합니다.
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // CakePHP가 플러그인/테마의 assets을 처리하는 방식대로 처리합니다.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // 라우팅 미들웨어를 추가합니다.
            // 많은 라우트를 연결하는 경우 운영 환경에서 라우트 캐싱을 활성화하면 성능이 향상될 수 있습니다.
            // 자세한 내용: https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // 다양한 형태로 인코딩된 요청 본문을 파싱하여,
            // `$request->getData()`를 통해 배열로 사용할 수 있도록 합니다.
            // https://book.cakephp.org/5/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // 크로스 사이트 요청 위조(CSRF) 보호 미들웨어
            // https://book.cakephp.org/5/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

        return $middlewareQueue;
    }

    /**
     * 애플리케이션의 컨테이너 서비스를 등록합니다.
     *
     * @param \Cake\Core\ContainerInterface $container 업데이트할 컨테이너.
     * @return void
     * @link https://book.cakephp.org/5/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }
}
