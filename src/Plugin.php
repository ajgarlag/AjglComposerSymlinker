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

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;

final class Plugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    public function onPostInstallCmd(Event $event)
    {
        (new Symlinker())->symlinkOnEvent($event);
    }

    public function onPostUpdateCmd(Event $event)
    {
        (new Symlinker())->symlinkOnEvent($event);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'post-install-cmd' => array('onPostInstallCmd', 0),
            'post-update-cmd' => array('onPostUpdateCmd', 0),
        );
    }
}
