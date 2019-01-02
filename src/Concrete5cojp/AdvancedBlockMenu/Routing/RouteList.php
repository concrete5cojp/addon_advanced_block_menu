<?php
namespace Concrete5cojp\AdvancedBlockMenu\Routing;

use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router->buildGroup()
            ->setNamespace('Concrete\Package\AdvancedBlockMenu\Controller\Dialog\Block')
            ->setPrefix('/ccm/package/advanced_block_menu/dialogs/block')
            ->routes('dialogs/blocks.php', 'advanced_block_menu')
        ;
    }
}
