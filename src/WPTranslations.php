<?php
/**
 * WPTranslations
 * 
 * @category ComposerPlugin
 * @package  RaphTopo\WPTranslations
 * @author   Raphael <raph-topo@posteo.net>
 * @license  GPL-3.0 https://github.com/raph-topo/wp-translations/blob/master/LICENSE
 * @link     https://github.com/raph-topo/wp-translations
 */

namespace RaphTopo\WPTranslations;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Installer\PackageEvent;
use Composer\Package\PackageInterface;

/**
 * WPTranslations
 * 
 * Main plugin logic.
 * 
 * @category ComposerPlugin
 * @package  RaphTopo\WPTranslations
 * @author   Raphael <raph-topo@posteo.net>
 * @license  GPL-3.0 https://github.com/raph-topo/wp-translations/blob/master/LICENSE
 * @link     https://github.com/raph-topo/wp-translations
 */
class WPTranslations implements PluginInterface, EventSubscriberInterface
{

    /**
     * Array of the languages we are using.
     *
     * @var array
     */
    protected $languages = [];

    /**
     * Full path to the language files target directory.
     *
     * @var string
     */
    protected $wpLanguagesDir = '';

    /**
     * Composer.
     * 
     * @var Composer
     */
    protected $composer;

    /**
     * IOInterface.
     * 
     * @var IOInterface
     */
    protected $io;

    /**
     * Composer plugin activation.
     *
     * @param Composer    $composer Composer
     * @param IOInterface $io       IOInterface
     *
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;

        $extra = $this->composer->getPackage()->getExtra();

        if (!empty($extra['wordpress-translations'])) {
            $this->languages = $extra['wordpress-translations'];
        }

        if (!empty($extra['wordpress-languages-dir'])) {
            $this->wpLanguagesDir = dirname(dirname(dirname(dirname(__DIR__)))) . '/' . $extra['wordpress-languages-dir'];
        }
    }


    /**
     * Subscribe to Composer events.
     *
     * @return array The events and callbacks.
     */
    public static function getSubscribedEvents()
    {
        return [
            'post-package-install' => [
                ['postPackageInstall', 0],
            ],
            'post-package-update' => [
                ['postPackageUpdate', 0],
            ],
        ];
    }

    /**
     * Our callback for the post-package-install event.
     *
     * @param PackageEvent $event The package event object.
     * 
     * @return void
     */
    public function postPackageInstall(PackageEvent $event)
    {
        $package = $event->getOperation()->getPackage();

        $this->getTranslations($package);
    }

    /**
     * Our callback for the post-package-update event.
     *
     * @param PackageEvent $event The package event object.
     * 
     * @return void
     */
    public function postPackageUpdate(PackageEvent $event)
    {
        $package = $event->getOperation()->getTargetPackage();

        $this->getTranslations($package);
    }

    /**
     * Get translations for a package, where applicable.
     *
     * @param PackageInterface $package PackageInterface
     * 
     * @return void
     */
    protected function getTranslations(PackageInterface $package)
    {

        try {
            $t = new \stdClass();

            list($provider, $name) = explode('/', $package->getName(), 2);

            switch ($package->getType()) {
            case 'wordpress-plugin':
                $t = new Translatable('plugin', $name, $package->getVersion(), $this->languages, $this->wpLanguagesDir);
                break;
            case 'wordpress-theme':
                $t = new Translatable('theme', $name, $package->getVersion(), $this->languages, $this->wpLanguagesDir);
                break;
            case 'wordpress-core':
                if ('roots' === $provider && 'wordpress' === $name) {
                    $t = new Translatable('core', $name, $package->getVersion(), $this->languages, $this->wpLanguagesDir);
                }
                break;

            default:
                break;
            }

            if (is_a($t, __NAMESPACE__ . '\Translatable')) {

                $results = $t->fetch();

                if (empty($results)) {
                    $this->io->write('    Translations were up to date');
                } else {
                    foreach ($results as $result) {
                        $this->io->write('    Updated translations to ' . $result);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->io->writeError('    ' . $e->getMessage());
        }
    }

    /**
     * Remove any hooks from Composer
     *
     * This will be called when a plugin is deactivated before being
     * uninstalled, but also before it gets upgraded to a new version
     * so the old one can be deactivated and the new one activated.
     *
     * @param Composer    $composer Composer
     * @param IOInterface $io       IOInterface
     * 
     * @return void
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        $composer;
        $io;
    }

    /**
     * Prepare the plugin to be uninstalled
     *
     * This will be called after deactivate.
     *
     * @param Composer    $composer Composer
     * @param IOInterface $io       IOInterface
     * 
     * @return void
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
        $composer;
        $io;
    }
}
