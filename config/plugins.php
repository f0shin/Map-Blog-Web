<?php
/**
 * 플러그인 설정 파일 (plugins.php)
 *
 * 이 파일에서는 애플리케이션에서 사용할 플러그인을 설정할 수 있습니다.
 * `Application::bootstrap()` 메서드에서 `parent::bootstrap();` 호출을 통해 로드됩니다.
 * 자세한 내용은 아래 문서를 참조하세요:
 * https://book.cakephp.org/5/en/plugins.html#loading-plugins-via-configuration-array
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * MIT 라이선스 하에 제공됩니다.
 * 전체 라이선스 내용은 LICENSE.txt 파일에서 확인할 수 있으며,
 * 파일을 재배포할 경우 저작권 표시를 유지해야 합니다.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         5.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * 로드할 플러그인의 목록 (`PluginName` => `[설정 옵션]` 형식).
 *
 * 사용 가능한 옵션:
 * - onlyDebug: 디버그 모드에서만 플러그인을 로드합니다. 기본값은 false.
 * - onlyCli: CLI(명령줄) 모드에서만 플러그인을 로드합니다. 기본값은 false.
 * - optional: 플러그인이 존재하지 않아도 예외를 발생시키지 않습니다. 기본값은 false.
 */

return [
  'DebugKit' => ['onlyDebug' => true],
  'Bake' => ['onlyCli' => true, 'optional' => true],
  'Migrations' => ['onlyCli' => true],

  // 추가할 플러그인은 여기에..
];
