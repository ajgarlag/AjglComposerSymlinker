<?php

/*
 * AJGL Composer Symlinker
 *
 * Copyright (C) Antonio J. García Lagar <aj@garcialagar.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ajgl\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Script to symlink resources installed with composer.
 *
 * @author Antonio J. García Lagar <aj@garcialagar.es>
 */
class ScriptSymlinker
{
    public static function createSymlinks(Event $event)
    {
        $symlinks = static::getSymlinks($event);
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $fs = new Filesystem();

        foreach ($symlinks as $package => $symlinksDefinition) {
            foreach ($symlinksDefinition as $origin => $target) {
                $originPath = realpath($vendorDir)."/$package/$origin";
                $targetPath = realpath($vendorDir)."/$target";
                $event->getIO()->write("Symlinking <comment>$originPath</comment> --> <comment>$targetPath</comment>");
                $fs->symlink($originPath, $targetPath);
            }
        }
    }

    protected static function getSymlinks(Event $event)
    {
        $options = $event->getComposer()->getPackage()->getExtra();
        $symlinks = array();

        if (isset($options['ajgl-symlinks']) && is_array($options['ajgl-symlinks'])) {
            $symlinks = $options['ajgl-symlinks'];
        }

        return $symlinks;
    }
}
