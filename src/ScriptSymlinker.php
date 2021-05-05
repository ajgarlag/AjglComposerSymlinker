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

use Composer\IO\IOInterface;
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

        foreach ($symlinks as $packageName => $symlinkDefinitions) {
            $package = $event->getComposer()->getRepositoryManager()->getLocalRepository()->findPackage($packageName, '*');
            if (null !== $package) {
                $packageDir = $event->getComposer()->getInstallationManager()->getInstallPath($package);

                foreach ($symlinkDefinitions as $target => $link) {
                    if ($fs->isAbsolutePath($target)) {
                        throw new \InvalidArgumentException("Invalid symlink target path '$target' for package'{$package->getName()}'.".' It must be relative.');
                    }
                    if ($fs->isAbsolutePath($link)) {
                        throw new \InvalidArgumentException("Invalid symlink link path '$link' for package'{$package->getName()}'.".' It must be relative.');
                    }

                    $targetPath = $packageDir.DIRECTORY_SEPARATOR.$target;
                    $linkPath = getcwd().DIRECTORY_SEPARATOR.$link;

                    if (!file_exists($targetPath)) {
                        throw new \RuntimeException("The target path '$targetPath' for package'{$package->getName()}' does not exist.");
                    }

                    self::link($fs, $targetPath, $linkPath, $event->getIO());
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

    protected static function link(Filesystem $fs, $source, $destination, IOInterface $io)
    {
        if (file_exists($destination) && is_link($destination)) {
            $fs->unlink($destination);
        }

        $fs->ensureDirectoryExists(dirname($destination));

        $io->write("  Symlinking <comment>$source</comment> to <comment>$destination</comment>");
        if ($fs->relativeSymlink($source, $destination)) {
            return;
        }

        $io->write("  Symlinking failed, try joining <comment>$source</comment> to <comment>$destination</comment>");
        if ($fs->junction($source, $destination)) {
            return;
        }

        throw new \RuntimeException("Unable to link '$source' to '$destination'. Does your filesystem support links?");
    }
}
