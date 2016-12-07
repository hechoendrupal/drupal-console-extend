<?php

/**
 * @file
 * Contains Drupal\Console\Extend\Plugin\ScriptHandler.
 */

namespace Drupal\Console\Extend\Plugin;

use Composer\Script\Event;
use Composer\Util\ProcessExecutor;

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
        $sync = __DIR__.'/../../vendor/mustangostang/spyc/Spyc.php';

        if (!file_exists($sync)) {
            return;
        }

        include_once $sync;

        $packages = array_keys($event->getComposer()->getPackage()->getRequires());
        if (!$packages) {
            return;
        }

        $config = __DIR__.'/../../config.yml';
        $directory = dirname($config);
        $configurationData = file_exists($config)?\Spyc::YAMLLoad($config):[];

        foreach ($packages as $package) {
            $configFile = $directory.'/vendor/'.$package.'/config.yml';
            if (is_file($configFile)) {
                $libraryData = \Spyc::YAMLLoad($configFile);
                if (!static::isValid($libraryData)) {
                    continue;
                }
                $configurationData = array_merge_recursive(
                    $configurationData,
                    $libraryData
                );
            }
        }
        if ($configurationData) {
            file_put_contents(
                $directory . '/extend.yml',
                \Spyc::YAMLDump($configurationData, false, 0, true)
            );
        }
    }

    public static function isValid($libraryData)
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
