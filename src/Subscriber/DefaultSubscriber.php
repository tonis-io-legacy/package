<?php

namespace Tonis\Package\Subscriber;

use Tonis\Event\EventManager;
use Tonis\Event\SubscriberInterface;
use Tonis\Package\Exception\PackageLoadFailedException;
use Tonis\Package\Feature\ConfigProviderInterface;
use Tonis\Package\PackageEvent;
use Tonis\Package\PackageManager;

class DefaultSubscriber implements SubscriberInterface
{
    /**
     * @param EventManager $events
     * @return void
     */
    public function subscribe(EventManager $events)
    {
        $events->on(PackageManager::EVENT_ON_LOAD, [$this, 'onLoad']);
        $events->on(PackageManager::EVENT_ON_MERGE, [$this, 'onMerge']);
    }

    /**
     * {@inheritDoc}
     */
    public function onLoad(PackageEvent $event)
    {
        $manager = $event->getPackageManager();
        $packages = $manager->getPackages();
        foreach ($packages as $fqcn => $package) {
            $package = class_exists($fqcn) ? new $fqcn() : null;

            if (null === $package) {
                throw new PackageLoadFailedException($fqcn);
            }

            $packages[$fqcn] = $package;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function onMerge(PackageEvent $event)
    {
        $manager = $event->getPackageManager();
        $config = [];

        foreach ($manager->getPackages() as $package) {
            if ($package instanceof ConfigProviderInterface) {
                $config = $manager->merge($config, $package->getConfig());
            }
        }

        $pmConfig = $manager->getConfig();
        if (null === $pmConfig['override_pattern']) {
            return $config;
        }

        $overrideFiles = glob($pmConfig['override_pattern'], $pmConfig['override_flags']);
        foreach ($overrideFiles as $file) {
            $config = $manager->merge($config, include $file);
        }

        return $config;
    }
}
