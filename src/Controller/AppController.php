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
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * 아래 클래스에 애플리케이션 전반에서 사용할 메서드를 추가하세요.
 * 다른 컨트롤러들은 이 컨트롤러를 상속받아 기능을 사용할 수 있습니다.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * 이 메서드를 사용하여 컴포넌트를 로드하는 등 초기 설정을 추가하세요.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * 추천되는 CakePHP 폼 보호 설정을 활성화하세요.
         * 자세한 내용은 다음 문서를 참고하세요: 
         *  https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }
}
