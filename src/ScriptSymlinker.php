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
use Composer\Util\Filesystem;

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
        $fs = new Filesystem();

        foreach ($event->getComposer()->getRepositoryManager()->getLocalRepository()->getPackages() as $package) {
            if (isset($symlinks[$package->getName()])) {
                $packageDir = $event->getComposer()->getInstallationManager()->getInstallPath($package);

                $symlinkDefinitions = $symlinks[$package->getName()];
                foreach ($symlinkDefinitions as $target => $link) {
                    if ($fs->isAbsolutePath($target)) {
                        throw new \InvalidArgumentException(
                            "Invalid symlink target path '$target' for package'{$package->getName()}'."
                            .' It must be relative.'
                        );
                    }
                    if ($fs->isAbsolutePath($link)) {
                        throw new \InvalidArgumentException(
                            "Invalid symlink link path '$link' for package'{$package->getName()}'."
                            .' It must be relative.'
                        );
                    }

                    $targetPath = $packageDir.DIRECTORY_SEPARATOR.$target;
                    $linkPath = getcwd().DIRECTORY_SEPARATOR.$link;

                    if (!file_exists($targetPath)) {
                        throw new \RuntimeException(
                            "The target path '$targetPath' for package'{$package->getName()}' does not exist."
                        );
                    }

                    $event->getIO()->write("Symlinking <comment>$targetPath</comment> to <comment>$linkPath</comment>");
                    $fs->ensureDirectoryExists(dirname($linkPath));
                    $fs->relativeSymlink($targetPath, $linkPath);
                }
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
