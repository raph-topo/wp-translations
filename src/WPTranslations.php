<?php

namespace RaphTopo\WPTranslations;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Installer\PackageEvent;
use Composer\Package\PackageInterface;

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
     * @var Composer
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * Composer plugin activation.
     *
     * @param Composer $composer
     * @param IOInterface $io
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
     * @param PackageInterface $package
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
                    $this->io->write('      - ' . sprintf('No translations updated for %s', $package->getName()));
                } else {
                    foreach ($results as $result) {
                        $this->io->write('      - ' . sprintf('Updated translation to %1$s for %2$s', $result, $package->getName()));
                    }
                }
            }
        } catch (\Exception $e) {
            $this->io->writeError('      - ' . 'ERROR: ' . $e->getMessage());
        }
    }
}
