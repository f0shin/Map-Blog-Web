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
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Console;

if (!defined('STDIN')) {
    define('STDIN', fopen('php://stdin', 'r'));
}

use PHPUnit\Runner\Version as PHPUnitVersion; // PHPUnit의 버전 정보 확인 가능

// use Cake\Codeception\Console\Installer as CodeceptionInstaller;
use Cake\Utility\Security;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Exception;

/**
 * 이 애플리케이션이 composer를 통해 설치될 때 실행되는 설치 후크를 제공합니다.
 * 필요에 따라 이 클래스를 수정하여 원하는 설정을 적용하세요.
 * (설치 후크(Installation Hook) : 소프트웨어가 설치될 때 자동으로 실행되는 특별한 코드 또는 스크립트)
 */
class Installer
{
    /**
     * 쓰기 가능하도록 설정해야 할 디렉터리의 배열
     *
     * @var list<string>
     */
    public const WRITABLE_DIRS = [
        'logs',
        'tmp',
        'tmp/cache',
        'tmp/cache/models',
        'tmp/cache/persistent',
        'tmp/cache/views',
        'tmp/sessions',
        'tmp/tests',
    ];

    /**
     * 사람(개발자)들이 직접 수행할 필요 없이 몇 가지 기본적인 설치 작업을 자동으로 처리합니다.
     *
     * @param \Composer\Script\Event $event Composer 이벤트 객체
     * @throws \Exception 검증기(validator)에서 발생한 예외.
     * @return void
     */
    public static function postInstall(Event $event): void
    {
        $io = $event->getIO();

        $rootDir = dirname(__DIR__, 2);

        static::createAppLocalConfig($rootDir, $io);
        static::createWritableDirectories($rootDir, $io);
        static::setFolderPermissions($rootDir, $io);
        static::setSecuritySalt($rootDir, $io);

        // Codeception 설치 관련 부분 제거
        // if (class_exists(CodeceptionInstaller::class)) {
        //     CodeceptionInstaller::customizeCodeceptionBinary($event);
        // }

        // PHPUnit 버전 확인 (테스트 환경 설정 시 유용)
        $io->write('PHPUnit Version: ' . PHPUnitVersion::id());
    }

    /**
     * Create config/app_local.php file if it does not exist.
     *
     * @param string $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     */
    public static function createAppLocalConfig(string $dir, IOInterface $io): void
    {
        $appLocalConfig = $dir . '/config/app_local.php';
        $appLocalConfigTemplate = $dir . '/config/app_local.example.php';
        if (!file_exists($appLocalConfig)) {
            copy($appLocalConfigTemplate, $appLocalConfig);
            $io->write('Created `config/app_local.php` file');
        }
    }

    /**
     * Create the `logs` and `tmp` directories.
     *
     * @param string $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     */
    public static function createWritableDirectories(string $dir, IOInterface $io): void
    {
        foreach (static::WRITABLE_DIRS as $path) {
            $path = $dir . '/' . $path;
            if (!file_exists($path)) {
                mkdir($path);
                $io->write('Created `' . $path . '` directory');
            }
        }
    }

    /**
     * Set globally writable permissions on the "tmp" and "logs" directory.
     *
     * This is not the most secure default, but it gets people up and running quickly.
     *
     * @param string $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     */
    public static function setFolderPermissions(string $dir, IOInterface $io): void
    {
        // ask if the permissions should be changed
        if ($io->isInteractive()) {
            $validator = function (string $arg): string {
                if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
                    return $arg;
                }
                throw new Exception('This is not a valid answer. Please choose Y or n.');
            };
            $setFolderPermissions = $io->askAndValidate(
                '<info>Set Folder Permissions ? (Default to Y)</info> [<comment>Y,n</comment>]? ',
                $validator,
                10,
                'Y'
            );

            if (in_array($setFolderPermissions, ['n', 'N'])) {
                return;
            }
        }

        // Change the permissions on a path and output the results.
        $changePerms = function (string $path) use ($io): void {
            $currentPerms = fileperms($path) & 0777;
            $worldWritable = $currentPerms | 0007;
            if ($worldWritable == $currentPerms) {
                return;
            }

            $res = chmod($path, $worldWritable);
            if ($res) {
                $io->write('Permissions set on ' . $path);
            } else {
                $io->write('Failed to set permissions on ' . $path);
            }
        };

        $walker = function (string $dir) use (&$walker, $changePerms): void {
            /** @phpstan-ignore-next-line */
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;

                if (!is_dir($path)) {
                    continue;
                }

                $changePerms($path);
                $walker($path);
            }
        };

        $walker($dir . '/tmp');
        $changePerms($dir . '/tmp');
        $changePerms($dir . '/logs');
    }

    /**
     * Set the security.salt value in the application's config file.
     *
     * @param string $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     */
    public static function setSecuritySalt(string $dir, IOInterface $io): void
    {
        $newKey = hash('sha256', Security::randomBytes(64));
        static::setSecuritySaltInFile($dir, $io, $newKey, 'app_local.php');
    }

    /**
     * Set the security.salt value in a given file
     *
     * @param string $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @param string $newKey key to set in the file
     * @param string $file A path to a file relative to the application's root
     * @return void
     */
    public static function setSecuritySaltInFile(string $dir, IOInterface $io, string $newKey, string $file): void
    {
        $config = $dir . '/config/' . $file;
        $content = file_get_contents($config);

        /** @phpstan-ignore-next-line */
        $content = str_replace('__SALT__', $newKey, $content, $count);

        if ($count == 0) {
            $io->write('No Security.salt placeholder to replace.');

            return;
        }

        $result = file_put_contents($config, $content);
        if ($result) {
            $io->write('Updated Security.salt value in config/' . $file);

            return;
        }
        $io->write('Unable to update Security.salt value.');
    }

    /**
     * Set the APP_NAME value in a given file
     *
     * @param string $dir The application's root directory.
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @param string $appName app name to set in the file
     * @param string $file A path to a file relative to the application's root
     * @return void
     */
    public static function setAppNameInFile(string $dir, IOInterface $io, string $appName, string $file): void
    {
        $config = $dir . '/config/' . $file;
        $content = file_get_contents($config);
        /** @phpstan-ignore-next-line */
        $content = str_replace('__APP_NAME__', $appName, $content, $count);

        if ($count == 0) {
            $io->write('No __APP_NAME__ placeholder to replace.');

            return;
        }

        $result = file_put_contents($config, $content);
        if ($result) {
            $io->write('Updated __APP_NAME__ value in config/' . $file);

            return;
        }
        $io->write('Unable to update __APP_NAME__ value.');
    }
}
