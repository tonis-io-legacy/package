<?php

namespace Tonis\Package\Subscriber;

use Tonis\Package\PackageEvent;
use Tonis\Package\PackageManager;
use Tonis\Package\TestAsset\Application\ApplicationPackage;

/**
 * @coversDefaultClass \Tonis\Package\Subscriber\DefaultSubscriber
 */
class DefaultPackageHookTest extends \PHPUnit_Framework_TestCase
{
    /** @var DefaultSubscriber */
    private $s;
    /** @var PackageManager */
    private $pm;
    /** @var PackageEvent */
    private $e;

    /**
     * @covers ::onLoad
     */
    public function testOnLoadCreatesInstanceOfPackageWhenOnlyNameIsSpecified()
    {
        $this->pm->add(ApplicationPackage::class);

        $packages = $this->pm->getPackages();
        $this->assertArrayHasKey(ApplicationPackage::class, $packages);
        $this->assertNull($packages[ApplicationPackage::class]);

        $this->s->onLoad($this->e);

        $this->assertArrayHasKey(ApplicationPackage::class, $packages);
        $this->assertInstanceOf(ApplicationPackage::class, $packages[ApplicationPackage::class]);
    }

    /**
     * @covers ::onLoad
     * @covers \Tonis\Package\Exception\PackageLoadFailedException
     * @expectedException \Tonis\Package\Exception\PackageLoadFailedException
     * @expectedExceptionMessage Package "Foo" failed to load: check your package name and composer autoloading
     */
    public function testOnLoadThrowsExpectedExceptionWhenAttemptingToLoadNonExistentException()
    {
        $this->pm->add('Foo');

        $packages = $this->pm->getPackages();
        $this->assertArrayHasKey('Foo', $packages);
        $this->assertNull($packages['Foo']);

        $this->s->onLoad($this->e);
    }

    /**
     * @covers ::onMerge
     */
    public function testOnMergeWithNoOverridesReturnsExpectedConfig()
    {
        $this->pm->add(ApplicationPackage::class);
        $this->s->onLoad($this->e);

        $config = $this->s->onMerge($this->e);

        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals('bar', $config['foo']);
    }

    public function testOnMergeWithOverrideConfigsReturnsExpectedConfig()
    {
        $pm = new PackageManager([
            'override_pattern' => __DIR__ . '/../TestAsset/Application/config/*.override.config.php',
        ]);
        $e = new PackageEvent($pm);
        $this->s->onLoad($e);

        $config = $this->s->onMerge($e);

        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals('bar', $config['foo']);
        $this->assertArrayHasKey('baz', $config);
        $this->assertEquals('booze', $config['baz']);
    }

    protected function setUp()
    {
        $this->s = new DefaultSubscriber();
        $this->pm = new PackageManager();
        $this->e = new PackageEvent($this->pm);
    }
}
