<?php

namespace Tonis\Package;

use Tonis\Event\EventManager;
use Tonis\Package\TestAsset\Application\ApplicationPackage;
use Tonis\Package\TestAsset\Override\OverridePackage;
use Tonis\Package\TestAsset\Path\PathPackage;

/**
 * @coversDefaultClass \Tonis\Package\PackageManager
 */
class PackageTest extends \PHPUnit_Framework_TestCase
{
    /** @var PackageManager */
    protected $pm;

    protected function setUp()
    {
        $pm = $this->pm = new PackageManager();
        $pm->add(ApplicationPackage::class);
        $pm->add(OverridePackage::class);
    }

    protected function tearDown()
    {
        if (file_exists(sys_get_temp_dir() . '/package.merged.config.php')) {
            unlink(sys_get_temp_dir() . '/package.merged.config.php');
        }
    }

    /**
     * @covers ::__construct
     */
    public function testPackagesIsInitialized()
    {
        $pm = new PackageManager();
        $this->assertInstanceOf('ArrayObject', $pm->getPackages());
    }

    /**
     * @covers ::getPath
     */
    public function testGetPath()
    {
        $pm = $this->pm;
        $pm->add(PathPackage::class);
        $pm->load();

        // Default path is up one dir from location
        $this->assertSame(
            realpath(__DIR__ . '/TestAsset'),
            $pm->getPath(ApplicationPackage::class)
        );

        // Implemeting PathProvider
        $this->assertSame(
            realpath(__DIR__ . '/TestAsset/Path'),
            $pm->getPath(PathPackage::class)
        );

        // Reuses cache
        $this->assertSame(
            realpath(__DIR__ . '/TestAsset/Path'),
            $pm->getPath(PathPackage::class)
        );
    }

    /**
     * @covers ::getMergedConfig
     */
    public function testMergedConfigIsInitialized()
    {
        $pm = new PackageManager();
        $this->assertInternalType('array', $pm->getMergedConfig());
    }

    /**
     * @covers ::getPackages
     */
    public function testGetPackages()
    {
        $pm = $this->pm;
        $this->assertCount(2, $pm->getPackages());
    }

    /**
     * @covers ::getPackage
     * @covers \Tonis\Package\Exception\PackageDoesNotExistException::__construct
     * @expectedException \Tonis\Package\Exception\PackageDoesNotExistException
     * @expectedExceptionMessage Package with name "foo" does not exist
     */
    public function testGetPackageThrowsExceptionForMissingPackage()
    {
        $pm = $this->pm;
        $pm->getPackage('foo');
    }

    /**
     * @covers ::getPackage
     * @covers \Tonis\Package\Exception\PackagesNotLoadedException::__construct
     * @expectedException \Tonis\Package\Exception\PackagesNotLoadedException
     * @expectedExceptionMessage Packages have not been loaded
     */
    public function testGetPackageThrowsExceptionIfPackagesNotLoaded()
    {
        $pm = $this->pm;
        $pm->getPackage(ApplicationPackage::class);
    }

    /**
     * @covers ::getPackage
     */
    public function testPackage()
    {
        $pm = $this->pm;
        $pm->load();

        $package = $pm->getPackage(ApplicationPackage::class);
        $this->assertInstanceOf(ApplicationPackage::class, $package);
    }

    /**
     * @covers ::add
     * @covers \Tonis\Package\Exception\PackagesAlreadyLoadedException::__construct
     * @expectedException \Tonis\Package\Exception\PackagesAlreadyLoadedException
     * @expectedExceptionMessage Packages can not be added after loading is complete
     */
    public function testAddThrowsExceptionWhenAlreadyLoaded()
    {
        $pm = $this->pm;
        $pm->load();
        $pm->add('foo');
    }

    /**
     * @covers ::add
     * @covers \Tonis\Package\Exception\PackageExistsException::__construct
     * @expectedException \Tonis\Package\Exception\PackageExistsException
     * @expectedExceptionMessage Package with name "Tonis\Package\TestAsset\Application\ApplicationPackage" already exists
     */
    public function testAddThrowsExceptionWhenPackageExists()
    {
        $pm = $this->pm;
        $pm->add(ApplicationPackage::class);
    }

    /**
     * @covers ::load
     * @covers ::writeCache
     * @covers ::getCacheFile
     */
    public function testLoadCachedFile()
    {
        $pm = new PackageManager();
        $pm->load();
        
        $this->assertFileNotExists(sys_get_temp_dir() . '/package.merged.config.php');
        
        $tmp = sys_get_temp_dir();

        $pm = new PackageManager(['cache_dir' => $tmp]);
        $pm->load();

        $file = sys_get_temp_dir() . '/package.merged.config.php';
        $this->assertFileExists($file);

        $result = include $file;

        $this->assertSame($result, $pm->getMergedConfig());
        
        $pm = new PackageManager(['cache_dir' => $tmp]);
        $pm->load();

        $this->assertSame($result, $pm->getMergedConfig());
    }

    /**
     * @covers ::load
     */
    public function testLoadReturnsEarlyIfLoadedAlready()
    {
        $pm = $this->pm;

        $refl = new \ReflectionClass($pm);
        $loaded = $refl->getProperty('loaded');
        $loaded->setAccessible(true);

        $this->assertFalse($loaded->getValue($pm));
        $pm->load();
        $this->assertTrue($loaded->getValue($pm));
        $this->assertNull($pm->load());
    }

    /**
     * @covers ::merge
     */
    public function testMerge()
    {
        $pm = $this->pm;
        $a = ['one' => ['foo', 'bar'], 'stringkey' => 'string', 'two'];
        $b = ['one' => ['baz' => 'booze'], 'stringkey' => 'override', 'three'];

        $refl = new \ReflectionClass($pm);
        $method = $refl->getMethod('merge');
        $method->setAccessible(true);
        $result = $method->invokeArgs($pm, [$a, $b]);

        $this->assertSame(
            ['one' => ['foo', 'bar', 'baz' => 'booze'], 'stringkey' => 'override', 'two', 'three'],
            $result
        );
    }

    /**
     * @covers ::getConfig
     * @covers ::setConfigDefaults
     * @covers ::installSubscribers
     */
    public function testConfigAndHookSetupIsSuccessfulAndConfigRetrievedAsExpected()
    {
        $config = [
            'foo' => 'bar',
        ];

        $pm = new PackageManager($config);

        $result = $pm->getConfig();

        $this->assertArrayHasKey('foo', $result);
        $this->assertEquals('bar', $result['foo']);

        $events = $pm->events();
        $this->assertInstanceOf(EventManager::class, $events);
    }
}
