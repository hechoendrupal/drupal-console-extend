<?php

/**
 * @file
 * Contains Drupal\Console\GlobalExtend\Plugin\ScriptHandler.
 */

namespace Drupal\Console\GlobalExtend\Plugin;

use Composer\Script\Event;
use Composer\Util\ProcessExecutor;

class ScriptHandler {

    /**
     * Register
     *
     * @param \Composer\Script\Event $event
     *   The script event.
     */
    public static function postInstall(Event $event) {
        $extra = $event->getComposer()->getPackage()->getExtra();
    }

}