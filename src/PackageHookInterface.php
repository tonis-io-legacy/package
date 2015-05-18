<?php
namespace Tonis\PackageManager;

use Tonis\Hookline\HookInterface;

interface PackageHookInterface extends HookInterface
{
    /**
     * @param PackageManager $packageManager
     * @return void
     */
    public function onLoad(PackageManager $packageManager);

    /**
     * @param PackageManager $packageManager
     * @return void
     */
    public function onMerge(PackageManager $packageManager);

    /**
     * @param PackageManager $packageManager
     * @param array $mergedConfig
     * @return void
     */
    public function afterLoad(PackageManager $packageManager, array $mergedConfig);
}
