<?php
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
 * @since         3.0.0
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 */

/*
 * 다른 define 문에서 디렉토리를 구분할 때 DS를 사용합니다.
 * (DS : 디렉토리 구분자(Directory Separator))
 */

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/*
 * CakePHP가 기본적으로 제공하는 디렉토리 구조와 다르게 설치된 경우에만,
 * 아래 정의 값을 수정하세요. 사용자 지정 설정을 적용할 때는 반드시 DS를 사용하고
 * 마지막에 DS를 추가하지 마세요.
 */

/*
 * "src" 디렉토리를 포함하는 루트 디렉토리의 전체 경로 (마지막에 DS 없음).
 */
define('ROOT', dirname(__DIR__));

/*
 * 애플리케이션 디렉토리의 실제 이름. 기본적으로 'src'입니다.
 */
define('APP_DIR', 'src');

/*
 * 애플리케이션 디렉토리의 경로.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/*
 * 설정 파일이 위치한 디렉토리의 경로.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/*
 * 웹루트 디렉토리의 파일 경로.
 *
 * 웹 서버에서 직접 웹루트를 설정하려면 다음을 사용하세요:
 *
 * `define('WWW_ROOT', rtrim($_SERVER['DOCUMENT_ROOT'], DS) . DS);`
 */
define('WWW_ROOT', ROOT . DS . 'webroot' . DS);

/*
 * 테스트 파일이 위치한 디렉토리의 경로.
 */
define('TESTS', ROOT . DS . 'tests' . DS);

/*
 * 임시 파일이 저장되는 디렉토리의 경로.
 */
define('TMP', ROOT . DS . 'tmp' . DS);

/*
 * 로그 파일이 저장되는 디렉토리의 경로.
 */
define('LOGS', ROOT . DS . 'logs' . DS);

/*
 * 캐시 파일이 저장되는 디렉토리의 경로. 
 * 다중 서버 환경에서 공유할 수도 있습니다.
 */
define('CACHE', TMP . 'cache' . DS);

/*
 * 리소스 파일이 위치한 디렉토리의 경로.
 */
define('RESOURCES', ROOT . DS . 'resources' . DS);

/*
 * "cake" 디렉토리의 절대 경로 (마지막에 DS 없음).
 *
 * CakePHP는 항상 composer를 통해 설치되어야 하므로 해당 경로를 확인하세요.
 */
define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');

/*
 * CakePHP 코어 디렉토리의 경로.
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);
