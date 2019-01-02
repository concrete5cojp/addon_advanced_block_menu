<?php

namespace Concrete\Package\AdvancedBlockMenu;

use Concrete\Core\Block\Menu\Manager;
use Concrete\Core\Package\Package;
use Concrete\Core\Routing\RouterInterface;
use Concrete\Core\Support\Facade\Route;
use Concrete5cojp\AdvancedBlockMenu\Block\Menu\AdvancedManager;
use Concrete5cojp\AdvancedBlockMenu\Routing\RouteList;

class Controller extends Package
{
    protected $pkgHandle = 'advanced_block_menu';
    protected $appVersionRequired = '8.5.0a2';
    protected $pkgVersion = '0.1';
    protected $pkgAutoloaderRegistries = [
        'src/Concrete5cojp/AdvancedBlockMenu' => '\Concrete5cojp\AdvancedBlockMenu'
    ];

    /**
     * @inheritDoc
     */
    public function getPackageName()
    {
        return t('Advanced Block Menu');
    }

    /**
     * @inheritDoc
     */
    public function getPackageDescription()
    {
        return t('Add "Information" menu item useful for developers in the block menu.');
    }

    public function on_start()
    {
        // Override Core Block Menu Manager
        $this->app->bind(Manager::class, AdvancedManager::class);

        /** @var RouterInterface $router */
        $router = Route::getFacadeRoot();
        // Add package routes by RouteList
        $router->loadRouteList(new RouteList());
    }
}
