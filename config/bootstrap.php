<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * MIT 라이선스 하에 제공됩니다.
 * 전체 라이선스 내용은 LICENSE.txt 파일에서 확인할 수 있으며,
 * 파일을 재배포할 경우 저작권 표시를 유지해야 합니다.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * 이 파일은 `src/Application.php`의 부트스트랩 메서드에서 로드됩니다.
 * 필요에 따라 부트스트랩 과정의 일부를 확장하거나 별도의 파일로 분리하여
 * 원하는 방식으로 구성할 수 있습니다.
 */

/*
 * CakePHP와 일반적인 파일 경로 상수를 찾기 위해 필요한 경로를 설정합니다.
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'paths.php';

/*
 * CakePHP 부트스트랩을 실행합니다.
 * 현재 이 과정에서는 라우터를 초기화하지만, 라우트 파일을 로드하지는 않습니다.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

/*
 * 컬렉션, 번역, 디버깅 등을 위한 전역 함수를 로드합니다.
 */
require CAKE . 'functions.php';


/*
 * 기본 설정 저장소(Config)를 초기화하고, 주요 설정 파일(app.php)을 로드합니다.
 *
 * CakePHP에서는 프로젝트 생성 후 두 개의 설정 파일이 제공됩니다:
 * - `config/app.php`: 기본 애플리케이션 설정 파일.
 * - `config/app_local.php`: 환경별 맞춤 설정을 위한 파일.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * 환경별 로컬 설정 파일을 로드하여 기존 설정을 덮어씁니다.
 * 참고: 보안상의 이유로 `app_local.php` 파일은 **Git 저장소에 포함하지 않아야 합니다**.
 */
if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

/*
 * debug 값이 true일 때 메타데이터 캐시는 짧은 시간 동안만 유지됩니다.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_translations_.duration', '+2 minutes');
}

/*
 * 기본 서버 시간대를 설정합니다. UTC를 사용하면 시간 계산 및 변환이 더 쉬워집니다.
 * 사용할 수 있는 시간대 목록은 다음 링크에서 확인하세요:
 * https://php.net/manual/en/timezones.php
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * mbstring 확장을 올바른 인코딩 방식으로 설정합니다.
 * (mbstring : PHP의 멀티바이트 문자열 처리 확장 기능)
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * 기본 로케일을 설정합니다. 날짜, 숫자, 통화 형식을 제어하며,
 * 기본 번역 언어도 여기에서 지정됩니다.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * 애플리케이션의 오류 및 예외 처리기를 등록합니다.
 */
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

/*
 * CLI(명령줄) 환경에 대한 설정.
 */
if (PHP_SAPI === 'cli') {
    // 명령에서 URL을 생성할 수 있도록 `fullBaseUrl`을 설정합니다.
    // 이메일을 명령을 통해 보낼 때 유용합니다.
    // Configure::write('App.fullBaseUrl', php_uname('n'));

    // 로그를 별도의 파일로 저장하여 권한 충돌을 방지합니다.
    if (Configure::check('Log.debug')) {
        Configure::write('Log.debug.file', 'cli-debug');
    }
    if (Configure::check('Log.error')) {
        Configure::write('Log.error.file', 'cli-error');
    }
}

/*
 * 전체 기본 URL 설정.
 * 이 URL은 모든 절대 링크의 기본 URL로 사용됩니다.
 * CLI(명령줄) 애플리케이션에서 유용합니다.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    /*
     * 프록시 또는 로드 밸런서를 사용할 경우, SSL/TLS 연결이
     * 서버에 도달하기 전에 종료될 수 있습니다. 프록시를 신뢰하는 경우,
     * `$trustProxy`를 활성화하여 `X-Forwarded-Proto` 헤더를 기반으로
     * `https`를 사용할지 결정할 수 있습니다.
     *
     * 자세한 내용은 다음 문서를 참조하세요:
     * https://book.cakephp.org/5/en/controllers/request-response.html#trusting-proxy-headers
     */
    $trustProxy = false;

    $s = null;
    if (env('HTTPS') || ($trustProxy && env('HTTP_X_FORWARDED_PROTO') === 'https')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if ($httpHost) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

/*
 * 로드된 설정을 시스템에 적용합니다.
 * 설정 데이터를 메모리에서 제거합니다.
 */
Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * 모바일 및 태블릿 감지 기능을 설정합니다.
 * 만약 이 기능이 필요 없다면 이 코드를 삭제하고
 * `composer.json`에서 `mobiledetect` 패키지를 제거해도 됩니다.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * `useLocaleParser()`를 호출하면 기본 locale 형식 파싱을 활성화할 수 있습니다.
 * 이를 통해 요청 데이터를 처리할 때 locale별 날짜 형식을 자동으로 변환할 수 있습니다.
 * 자세한 내용은 아래 링크에서 확인하세요:
 * @link https://book.cakephp.org/5/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */

// \Cake\Database\TypeFactory::build('time')->useLocaleParser();
// \Cake\Database\TypeFactory::build('date')->useLocaleParser();
// \Cake\Database\TypeFactory::build('datetime')->useLocaleParser();
// \Cake\Database\TypeFactory::build('timestamp')->useLocaleParser();
// \Cake\Database\TypeFactory::build('datetimefractional')->useLocaleParser();
// \Cake\Database\TypeFactory::build('timestampfractional')->useLocaleParser();
// \Cake\Database\TypeFactory::build('datetimetimezone')->useLocaleParser();
// \Cake\Database\TypeFactory::build('timestamptimezone')->useLocaleParser();

/*
 * 사용자 정의 Inflector 규칙을 설정할 수 있습니다.
 * 이를 통해 테이블, 모델, 컨트롤러 이름 또는 기타 문자열을 올바르게 단수형/복수형으로 변환할 수 있습니다.
 */
// \Cake\Utility\Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
// \Cake\Utility\Inflector::rules('irregular', ['red' => 'redlings']);
// \Cake\Utility\Inflector::rules('uninflected', ['dontinflectme']);

/*
 * 사용자 정의 날짜 및 시간 형식을 설정합니다.
 * 자세한 내용은 아래 문서에서 확인하세요:
 * https://book.cakephp.org/5/en/core-libraries/time.html#setting-the-default-locale-and-format-string
 * https://unicode-org.github.io/icu/userguide/format_parse/datetime/#datetime-format-syntax
 */
// \Cake\I18n\Date::setToStringFormat('dd.MM.yyyy');
// \Cake\I18n\Time::setToStringFormat('dd.MM.yyyy HH:mm');
