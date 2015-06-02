<?php

namespace Tonis\Package\Hook;

use Tonis\Package\Package;

/**
 * @coversDefaultClass \Tonis\Package\Hook\DefaultPackageHook
 */
class DefaultPackageHookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultPackageHook
     */
    private $dph;

    /**
     * @var Package
     */
    private $pm;

    protected function setUp()
    {
        $this->dph = new DefaultPackageHook();
        $this->pm = new Package();
    }

    /**
     * @covers ::onLoad
     */
    public function testOnLoadCreatesInstanceOfClassWhenNameAndPackageAreSpecified()
    {
        $this->pm->add('fqcn', '\Tonis\Package\TestAsset\FQCN\Module');

        $packages = $this->pm->getPackages();
        $this->assertArrayHasKey('fqcn', $packages);
        $this->assertEquals($packages['fqcn'], '\Tonis\Package\TestAsset\FQCN\Module');

        $this->dph->onLoad($this->pm);

        $this->assertArrayHasKey('fqcn', $packages);
        $this->assertInstanceOf('\Tonis\Package\TestAsset\FQCN\Module', $packages['fqcn']);
    }

    /**
     * @covers ::onLoad
     */
    public function testOnLoadCreatesInstanceOfPackageWhenOnlyNameIsSpecified()
    {
        $this->pm->add('\Tonis\Package\TestAsset\Application');

        $packages = $this->pm->getPackages();
        $this->assertArrayHasKey('\Tonis\Package\TestAsset\Application', $packages);
        $this->assertNull($packages['\Tonis\Package\TestAsset\Application']);

        $this->dph->onLoad($this->pm);

        $this->assertArrayHasKey('\Tonis\Package\TestAsset\Application', $packages);
        $this->assertInstanceOf(
            '\Tonis\Package\TestAsset\Application\Package',
            $packages['\Tonis\Package\TestAsset\Application']
        );
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

        $this->dph->onLoad($this->pm);
    }

    /**
     * @covers ::onMerge
     */
    public function testOnMergeWithNoOverridesReturnsExpectedConfig()
    {
        $this->pm->add('\Tonis\Package\TestAsset\Application');
        $this->dph->onLoad($this->pm);

        $config = $this->dph->onMerge($this->pm);

        $this->assertArrayHasKey('foo', $config);
        $this->assertEquals('bar', $config['foo']);
    }

    public function testOnMergeWithOverrideConfigsReturnsExpectedConfig()
    {
        $pm = new Package([
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
