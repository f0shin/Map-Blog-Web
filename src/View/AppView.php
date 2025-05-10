<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * MIT 라이선스 하에 제공됩니다.
 * 파일을 재배포할 경우 저작권 표시를 유지해야 합니다.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * 애플리케이션의 기본 뷰 클래스입니다.
 *
 * @link https://book.cakephp.org/5/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * 공통적인 초기화 코드(예: 헬퍼 추가)를 설정하는 데 사용됩니다.
     *
     * 예: `$this->addHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
    }
}
