<?php
namespace Tonis\Package;

/**
 * @coversDefaultClass \Tonis\Package\PackageEvent
 */
class PackageEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getPackageManager
     */
    public function testGetPackageManager()
    {
        $pm = new PackageManager;
        $event = new PackageEvent($pm);

        $this->assertSame($pm, $event->getPackageManager());
    }
}
