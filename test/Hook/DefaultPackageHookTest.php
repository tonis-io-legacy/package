<?php

namespace Tonis\PackageManager\Hook;

use Tonis\PackageManager\PackageManager;

/**
 * @coversDefaultClass \Tonis\PackageManager\Hook\DefaultPackageHook
 */
class DefaultPackageHookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultPackageHook
     */
    private $dph;

    /**
     * @var PackageManager
     */
    private $pm;

    protected function setUp()
    {
        $this->dph = new DefaultPackageHook();
        $this->pm = new PackageManager();
    }

    /**
     * @covers ::onLoad
     */
    public function testOnLoadCreatesInstanceOfClassWhenNameAndPackageAreSpecified()
    {
        $this->pm->add('fqcn', '\Tonis\PackageManager\TestAsset\FQCN\Module');

        $packages = $this->pm->getPackages();
        $this->assertArrayHasKey('fqcn', $packages);
        $this->assertEquals($packages['fqcn'], '\Tonis\PackageManager\TestAsset\FQCN\Module');

        $this->dph->onLoad($this->pm);

        $this->assertArrayHasKey('fqcn', $packages);
        $this->assertInstanceOf('\Tonis\PackageManager\TestAsset\FQCN\Module', $packages['fqcn']);
    }

    /**
     * @covers ::onLoad
     */
    public function testOnLoadCreatesInstanceOfPackageWhenOnlyNameIsSpecified()
    {
        $this->pm->add('\Tonis\PackageManager\TestAsset\Application');

        $packages = $this->pm->getPackages();
        $this->assertArrayHasKey('\Tonis\PackageManager\TestAsset\Application', $packages);
        $this->assertNull($packages['\Tonis\PackageManager\TestAsset\Application']);

        $this->dph->onLoad($this->pm);

        $this->assertArrayHasKey('\Tonis\PackageManager\TestAsset\Application', $packages);
        $this->assertInstanceOf(
            '\Tonis\PackageManager\TestAsset\Application\Package',
            $packages['\Tonis\PackageManager\TestAsset\Application']
        );
    }

    /**
     * @covers ::onLoad
     * @covers \Tonis\PackageManager\Exception\PackageLoadFailedException
     * @expectedException \Tonis\PackageManager\Exception\PackageLoadFailedException
     * @expectedExceptionMessage Package "Foo" failed to load: check your package name and composer autoloading
     */
    public function testOnLoadThrowsExpectedExceptionWhenAttemptingToLoadNonExistentException()
    {
        $this->pm->add('Foo');

        $packages = $this->pm->getPackages();
        $this->assertArrayHasKey('Foo', $packages);
        $this->assertNull($packages['Foo']);

        $this->dph->onLoad($this->pm);
    }

    /**
     * @covers ::onMerge
     */
    public function testOnMergeWithNoOverridesReturnsExpectedConfig()
    {
        $this->pm->add('\Tonis\PackageManager\TestAsset\Application');
        $this->dph->onLoad($this->pm);

        $config = $this->dph->onMerge($this->pm);

        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals('bar', $config['foo']);
    }

    public function testOnMergeWithOverrideConfigsReturnsExpectedConfig()
    {
        $pm = new PackageManager([
            'override_pattern' => __DIR__ . '/../TestAsset/Application/config/*.override.config.php',
        ]);
        $this->dph->onLoad($pm);

        $config = $this->dph->onMerge($pm);

        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals('bar', $config['foo']);
        $this->assertArrayHasKey('baz', $config);
        $this->assertEquals('booze', $config['baz']);
    }
}
