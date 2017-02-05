<?php

/**
 * @file
 * Contains Drupal\Console\Extend\Plugin\ScriptHandler.
 */

namespace Drupal\Console\Extend\Plugin;

use Composer\Script\Event;
use Composer\Util\ProcessExecutor;
use Symfony\Component\Yaml\Yaml;
use Drupal\Console\Extend\Utils\ExtendExtensionManager;

class ScriptHandler
{
    /**
     * Register
     *
     * @param \Composer\Script\Event $event
     *   The script event.
     */
    public static function dump(Event $event)
    {
        $extendExtensionManager = new ExtendExtensionManager();

        $directory = realpath(__DIR__.'/../../');
        $configFile = __DIR__.'/../../console.config.yml';
        $servicesFile = __DIR__.'/../../console.services.yml';

        $extendExtensionManager->addConfigFile($configFile);
        $extendExtensionManager->addServicesFile($servicesFile);
        $extendExtensionManager->processProjectPackages($directory);

        if ($configData = $extendExtensionManager->getConfigData()) {
            file_put_contents(
                $directory . '/extend.console.config.yml',
                Yaml::dump($configData, 6, 2)
            );
        }

        if ($servicesData = $extendExtensionManager->getServicesData()) {
            file_put_contents(
                $directory . '/extend.console.services.yml',
                Yaml::dump($servicesData, 4, 2)
            );
        }
    }
}
