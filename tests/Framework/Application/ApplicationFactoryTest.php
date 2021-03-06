<?php

/*
 * This file is part of the BlendEngine framework.
 *
 * (c) Gevik Babakhani <gevikb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Blend\Tests\Framework\Application;

use Blend\Tests\ProjectUtil;
use Blend\Component\Filesystem\Filesystem;
use Blend\Framework\Factory\ApplicationFactory;
use Blend\Tests\Framework\Application\Stubs\DummyApplication;
use Symfony\Component\HttpFoundation\Request;
use Blend\Tests\Framework\Application\Stubs\TestableApplication;
use Blend\Tests\Framework\Application\Stubs\CustomRequestExceptionHandler;

/**
 * Description of ApplicationFactoryTest
 *
 * @author Gevik Babakhani <gevikb@gmail.com>
 */
class ApplicationFactoryTest extends \PHPUnit_Framework_TestCase {

    static $cleanup = [];

    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\FileNotFoundException
     */
    public function testNoCacheFolder() {
        $factory = new ApplicationFactory(DummyApplication::class, '/', true);
        $factory->create();
    }

    /**
     * @expectedException \Symfony\Component\Filesystem\Exception\FileNotFoundException
     */
    public function testWithNoConfigFile() {

        $fs = new Filesystem();
        $appdir = sys_get_temp_dir() . '/' . uniqid();
        $fs->mkdir($appdir . '/var/cache');
        self::$cleanup[] = $appdir;
        $factory = new ApplicationFactory(DummyApplication::class, $appdir);
        $factory->create();
    }

    /**
     * @large
     */
    public function testFactorySanity() {
        $appName = 'App1';
        $projectFolder = ProjectUtil::createNewProject($appName, true);
        list($clazz, $loader) = ProjectUtil::initProjectClassLoader($projectFolder);
        $factory = new ApplicationFactory($clazz, $projectFolder);
        $factory->create();
        $configCache = $projectFolder . '/var/cache/config.cache';
        $this->assertFileExists($configCache);

        unlink($projectFolder . '/config/config.json');

        $factory = new ApplicationFactory($clazz, $projectFolder);
        $app2 = $factory->create();
        $this->assertTrue($app2 instanceof \Blend\Framework\Application\Application);
        $loader->unregister();
        self::$cleanup[] = $projectFolder;
    }

    /**
     * @large
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testNoRouteExists() {
        $appName = 'App12';
        $projectFolder = ProjectUtil::createNewProject($appName, true);
        list($clazz, $loader) = ProjectUtil::initProjectClassLoader($projectFolder);
        $factory = new ApplicationFactory(TestableApplication::class, $projectFolder);
        $app = $factory->create();
        $request = Request::create("/notexists");
        $app->run($request);
        self::$cleanup[] = $projectFolder;
    }

    /**
     * @large
     */
    public function testNoRouteExistsException() {
        $appName = 'App13';
        $projectFolder = ProjectUtil::createNewProject($appName, true);
        list($clazz, $loader) = ProjectUtil::initProjectClassLoader($projectFolder);
        $factory = new ApplicationFactory($clazz, $projectFolder);
        $app = $factory->create();
        $request = Request::create("/notexists");
        $output = catch_output(function() use($app, $request) {
            $app->run($request);
        });
        $this->assertEquals('No routes found for "/notexists".', $output);
        self::$cleanup[] = $projectFolder;
    }

    /**
     * @large
     */
    public function testCustomExceptionHandler() {
        $appName = 'App14';
        $projectFolder = ProjectUtil::createNewProject($appName, true);
        list($clazz, $loader) = ProjectUtil::initProjectClassLoader($projectFolder);
        ProjectUtil::appendOrCreateServicesConfig($projectFolder, [
            'custom-exception-handler' => CustomRequestExceptionHandler::class
        ]);
        $factory = new ApplicationFactory($clazz, $projectFolder);
        $app = $factory->create();
        $request = Request::create("/notexists");
        $output = catch_output(function() use($app, $request) {
            $app->run($request);
        });
        $this->assertEquals('Page not found /notexists', $output);
        self::$cleanup[] = $projectFolder;
    }

    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
        $fs = new Filesystem();
        foreach (self::$cleanup as $folder) {
            $fs->remove($folder);
        }
    }

}
