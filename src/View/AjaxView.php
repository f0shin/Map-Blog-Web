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
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.4
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

/**
 * AJAX 응답을 처리하는 뷰 클래스.
 * 현재 기본 레이아웃을 변경하고 응답 타입을 설정하는 역할을 합니다.
 * 기본적으로 응답 타입은 text/html로 매핑됩니다.
 */
class AjaxView extends AppView
{
    /**
     * 뷰를 렌더링할 때 사용할 레이아웃 파일의 이름.
     * 지정된 이름은 /templates/Layout 디렉토리 내 레이아웃 파일의 이름이며,
     * `.php` 확장자는 포함되지 않습니다.
     *
     * @var string
     */
    protected string $layout = 'ajax';

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->response = $this->response->withType('ajax');
    }
}
