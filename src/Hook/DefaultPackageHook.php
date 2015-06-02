<?php

namespace Tonis\Package\Hook;

use Tonis\Package\Exception\PackageLoadFailedException;
use Tonis\Package\Feature\ConfigProviderInterface;
use Tonis\Package\Package;

class DefaultPackageHook extends AbstractPackageHook
{
    /**
     * {@inheritDoc}
     */
    public function onLoad(Package $Package)
    {
        $packages = $Package->getPackages();
        foreach ($packages as $name => $package) {
            $fcqn = null;

            if (is_string($package)) {
                $fcqn = $package;
            } elseif (empty($package)) {
                $fcqn = $name . '\\Package';
            }

            if (null !== $fcqn) {
                $package = class_exists($fcqn) ? new $fcqn() : null;
            }

            if (null === $package) {
                throw new PackageLoadFailedException($name);
            }

            $packages[$name] = $package;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function onMerge(Package $Package)
    {
        $config = [];

        foreach ($Package->getPackages() as $package) {
            if ($package instanceof ConfigProviderInterface) {
                $config = $Package->merge($config, $package->getConfig());
            }
        }

        $pmConfig = $Package->getConfig();
        if (null === $pmConfig['override_pattern']) {
            return $config;
        }

        $overrideFiles = glob($pmConfig['override_pattern'], $pmConfig['override_flags']);
        foreach ($overrideFiles as $file) {
            $config = $Package->merge($config, include $file);
        }

        return $config;
    }
}
