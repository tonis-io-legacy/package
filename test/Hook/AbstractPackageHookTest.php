<?php

namespace Tonis\PackageManager\Hook;

use Tonis\PackageManager\PackageManager;
use Tonis\PackageManager\TestAsset\Hook\AbstractPackageHookClass;

/**
 * @coversDefaultClass \Tonis\PackageManager\Hook\AbstractPackageHook
 */
class AbstractPackageHookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractPackageHook
     */
    private $ph;

    /**
     * @var PackageManager
     */
    private $pm;

    protected function setUp()
    {
        $this->ph = new AbstractPackageHookClass();
        $this->pm = new PackageManager();
    }

    public function testOnLoad()
    {
        $this->assertNull($this->ph->onLoad($this->pm));
    }

    public function testOnMerge()
    {
        $this->assertNull($this->ph->onMerge($this->pm));
    }

    public function testAfterLoad()
    {
        $this->assertNull($this->ph->afterLoad($this->pm, []));
    }
}
