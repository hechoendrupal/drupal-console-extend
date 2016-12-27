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

        $config = __DIR__.'/../../config.yml';
        $directory = dirname($config);
        $configurationData = file_exists($config)?$yaml->load($config):[];

        foreach ($packages as $package) {
            $packageDirectory = $directory.'/vendor/'.$package;
            if (!is_dir($packageDirectory)) {
                continue;
            }

            $composerFile = $packageDirectory.'/composer.json';
            if (!is_file($composerFile)) {
                continue;
            }

            if (!static::isValidPackageType($composerFile)) {
                continue;
            }

            $configFile = $packageDirectory.'/config.yml';
            if (!is_file($configFile)) {
                continue;
            }

            $libraryData = $yaml->load($configFile);
            if (!static::isValidAutoWireData($libraryData)) {
                continue;
            }

            $configurationData = array_merge_recursive(
                $configurationData,
                $libraryData
            );
        }

        if ($configurationData) {
            file_put_contents(
                $directory . '/extend.yml',
                $yaml->dump($configurationData, false, 0, true)
            );
        }
    }

    public static function isValidPackageType($composerFile)
    {
        $composerContent = json_decode(file_get_contents($composerFile), true);
        if (!array_key_exists('type', $composerContent)) {
            return false;
        }
        $packageType = $composerContent['type'];

        return $packageType === 'drupal-console-library';
    }

    public static function isValidAutoWireData($libraryData)
    {
        if (!array_key_exists('application', $libraryData)) {
            return false;
        }

        if (!array_key_exists('autowire', $libraryData['application'])) {
            return false;
        }

        if (!array_key_exists('commands', $libraryData['application']['autowire'])) {
            return false;
        }

        return true;
    }
}
