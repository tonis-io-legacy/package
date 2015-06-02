<?php

namespace Tonis\Package\Hook;

use Tonis\Package\Package;
use Tonis\Package\TestAsset\Hook\AbstractPackageHookClass;

/**
 * @coversDefaultClass \Tonis\Package\Hook\AbstractPackageHook
 */
class AbstractPackageHookTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractPackageHook
     */
    private $ph;

    /**
     * @var Package
     */
    private $pm;

    protected function setUp()
    {
        $this->ph = new AbstractPackageHookClass();
        $this->pm = new Package();
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
