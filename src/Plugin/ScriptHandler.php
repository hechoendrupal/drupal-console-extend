<?php

/**
 * @file
 * Contains Drupal\Console\Extend\Plugin\ScriptHandler.
 */

namespace Drupal\Console\Extend\Plugin;

use Composer\Script\Event;
use Composer\Util\ProcessExecutor;
use Eureka\Component\Yaml\Yaml;

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
        $yaml = new Yaml();

        $packages = array_keys($event->getComposer()->getPackage()->getRequires());
        if (!$packages) {
            return;
        }

        $directory = realpath(__DIR__.'/../../');

        $configFile = __DIR__.'/../../console.config.yml';
        $servicesFile = __DIR__.'/../../console.services.yml';

        $configData = static::validateConfigFile($configFile, $yaml);
        $servicesData = static::validateServicesFile($servicesFile, $yaml);

        foreach ($packages as $package) {
            $packageDirectory = $directory.'/vendor/'.$package;
            if (!is_dir($packageDirectory)) {
                continue;
            }

            $composerFile = $packageDirectory.'/composer.json';
            if (!static::isValidPackageType($composerFile)) {
                continue;
            }

            $configFile = $packageDirectory.'/console.config.yml';
            if ($packageConfigData = static::validateConfigFile($configFile, $yaml)) {
                $configData = array_merge_recursive(
                    $configData,
                    $packageConfigData
                );
            }

            $servicesFile = $packageDirectory.'/console.services.yml';
            if ($packageServicesData = static::validateServicesFile($servicesFile, $yaml)) {
                $servicesData = array_merge_recursive(
                    $servicesData,
                    $packageServicesData
                );
            }
        }

        if ($configData) {
            file_put_contents(
//                $directory . '/extend.config.yml',
                $directory . '/extend.yml',
                $yaml->dump($configData, false, 0, true)
            );
        }

        if ($servicesData) {
            file_put_contents(
                $directory . '/extend.services.yml',
                $yaml->dump($servicesData, false, 0, true)
            );
        }
    }

    /**
     * @param string $composerFile
     *
     * @return bool
     */
    public static function isValidPackageType($composerFile)
    {
        if (!is_file($composerFile)) {
            return false;
        }

        $composerContent = json_decode(file_get_contents($composerFile), true);
        if (!$composerContent) {
            return false;
        }

        if (!array_key_exists('type', $composerContent)) {
            return false;
        }
        $packageType = $composerContent['type'];

        return $packageType === 'drupal-console-library';
    }

    /**
     * @param string $configFile
     * @param Yaml $yaml
     *
     * @return array
     */
    public static function validateConfigFile($configFile, Yaml $yaml)
    {
        if (!is_file($configFile)) {
            return [];
        }

        $packageConfigurationData = $yaml->load($configFile);

        if (!array_key_exists('application', $packageConfigurationData)) {
            return [];
        }

        if (!array_key_exists('autowire', $packageConfigurationData['application'])) {
            return [];
        }

        if (!array_key_exists('commands', $packageConfigurationData['application']['autowire'])) {
            return [];
        }

        return $packageConfigurationData;
    }

    /**
     * @param string $servicesFile
     * @param Yaml $yaml
     *
     * @return array
     */
    public static function validateServicesFile($servicesFile, Yaml $yaml)
    {
        if (!is_file($servicesFile)) {
            return [];
        }

        $packageServicesData = $yaml->load($servicesFile);

        if (!array_key_exists('services', $packageServicesData)) {
            return [];
        }

        return $packageServicesData;
    }
}
