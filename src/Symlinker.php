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
use Composer\Util\Platform;
use Symfony\Component\Filesystem\Exception\IOException;

final class Symlinker
{
    const EXTRA_PARAMETER = 'ajgl-symlinks';

    /** @var Filesystem */
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function symlinkOnEvent(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        if (!isset($extra[self::EXTRA_PARAMETER])) {
            return;
        }

        $event->getIO()->write('<info>Creating symlinks</info>');
        foreach ($extra[self::EXTRA_PARAMETER] as $packageName => $symlinkDefinitions) {
            $package = $event->getComposer()->getRepositoryManager()->getLocalRepository()->findPackage($packageName, '*');
            if (null === $package) {
                $event->getIO()->write(sprintf(' <warning>Package "%s" not installed</warning>', $packageName));
                continue;
            }

            $event->getIO()->write(sprintf(' Symlinking package <info>"%s"</info>', $package->getName()));
            foreach ($symlinkDefinitions as $source => $destination) {
                $source = Filesystem::trimTrailingSlash($source);
                $destination = Filesystem::trimTrailingSlash($destination);

                if ($this->filesystem->isAbsolutePath($source)) {
                    throw new \InvalidArgumentException(sprintf('Invalid symlink source absolute path "%s" for package "%s". It must be a relative path.', $source, $package->getName()));
                }

                if ($this->filesystem->isAbsolutePath($destination)) {
                    throw new \InvalidArgumentException(sprintf('Invalid symlink destination absolute path "%s" for package "%s". It must be a relative path.', $destination, $package->getName()));
                }

                $packageInstallPath = $event->getComposer()->getInstallationManager()->getInstallPath($package);
                $sourcePath = realpath($packageInstallPath.DIRECTORY_SEPARATOR.$source);
                if (false === $sourcePath || !file_exists($sourcePath)) {
                    throw new \RuntimeException(sprintf('Source path "%s" for package "%s" does not exist.', $sourcePath, $package->getName()));
                }

                $destinationPath = getcwd().DIRECTORY_SEPARATOR.$destination;

                $this->link($sourcePath, $destinationPath, $event->getIO());
            }
        }
    }

    private function link($sourcePath, $destinationPath, IOInterface $io)
    {
        $io->write(sprintf('  Symlinking <info>"%s"</info> to <info>"%s"</info>', $sourcePath, $destinationPath), false, IOInterface::VERBOSE);

        if (file_exists($destinationPath) && is_link($destinationPath) && $sourcePath === realpath($destinationPath)) {
            $io->write(': already exists', true, IOInterface::VERBOSE);

            return;
        }

        $this->filesystem->remove($destinationPath);
        try {
            $this->filesystem->ensureDirectoryExists(dirname($destinationPath));
            if (Platform::isWindows()) {
                // Implement symlinks as NTFS junctions on Windows
                $this->filesystem->junction($sourcePath, $destinationPath);
                $io->write(': <comment>junction created</comment>', true, IOInterface::VERBOSE);
            } else {
                $result = $this->filesystem->relativeSymlink($sourcePath, $destinationPath);
                if (false === $result) {
                    throw new IOException(sprintf('Relative symlink from "%s" to "%s" failed.', $sourcePath, $destinationPath));
                }
                $io->write(': <comment>symlink created</comment>', true, IOInterface::VERBOSE);
            }
        } catch (IOException $e) {
            throw new \RuntimeException(sprintf('   Symlink from "%s" to "%s" failed! Error: "%s"', $sourcePath, $destinationPath, $e->getMessage()), 0, $e);
        }
    }
}
