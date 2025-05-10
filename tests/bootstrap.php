<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * MIT 라이선스 하에 제공됩니다.
 * 전체 저작권 및 라이선스 정보는 LICENSE.txt 파일에서 확인할 수 있습니다.
 * 파일을 재배포할 경우 저작권 표시를 유지해야 합니다.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\ConnectionHelper;
use Migrations\TestSuite\Migrator;

/**
 * Test runner bootstrap.
 *
 * 애플리케이션에서 단위 테스트를 실행할 때 필요한 추가적인 설정을 정의합니다.
 */
require dirname(__DIR__) . '/vendor/autoload.php';

require dirname(__DIR__) . '/config/bootstrap.php';

if (empty($_SERVER['HTTP_HOST']) && !Configure::read('App.fullBaseUrl')) {
    Configure::write('App.fullBaseUrl', 'http://localhost');
}

// DebugKit은 CLI / PHPDBG 환경에서 연결 설정을 건너뜹니다.
// 하지만 PagesControllerTest는 디버그 모드에서 실행되며 DebugKit이 로드되므로,
// 설정되지 않으면 DebugKit에서 오류가 발생할 수 있습니다.
ConnectionManager::setConfig('test_debug_kit', [
    'className' => 'Cake\Database\Connection',
    'driver' => 'Cake\Database\Driver\Sqlite',
    'database' => TMP . 'debug_kit.sqlite',
    'encoding' => 'utf8',
    'cacheMetadata' => true,
    'quoteIdentifiers' => false,
]);

ConnectionManager::alias('test_debug_kit', 'debug_kit');

// 1초 차이로 인한 문제를 방지하기 위해 현재 시간을 고정합니다.
Chronos::setTestNow(Chronos::now());

// PHP 7.2 이상에서는 stdout이 출력된 후에는
// session ID를 설정할 수 없으므로 미리 고정합니다
session_id('cli');

// 마이그레이션 실행 전에 연결 별칭을 설정해야 합니다.
// 그렇지 않으면 마이그레이션 내 테이블 객체가 기본 데이터 소스를 사용하게 됩니다.
ConnectionHelper::addTestAliases();

// 마이그레이션을 사용하여 테스트 데이터베이스 스키마를 구축합니다.
//
// 파일 내 마이그레이션 기록과 상태가 다를 경우 데이터베이스를 다시 구축합니다.
//
// CakePHP의 마이그레이션을 사용하지 않는다면,
// 원하는 마이그레이션 도구를 여기서 호출하거나
// SQL 덤프 파일을 로드할 수도 있습니다.
// use Cake\TestSuite\Fixture\SchemaLoader;
// (new SchemaLoader())->loadSqlFiles('./tests/schema.sql', 'test');

(new Migrator())->run();
