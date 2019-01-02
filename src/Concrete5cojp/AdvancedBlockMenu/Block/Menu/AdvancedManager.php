<?php
namespace Concrete5cojp\AdvancedBlockMenu\Block\Menu;

use Concrete\Core\Application\UserInterface\ContextMenu\Item\DividerItem;
use Concrete\Core\Application\UserInterface\ContextMenu\Item\LinkItem;
use Concrete\Core\Block\Menu\Manager;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;

class AdvancedManager extends Manager
{
    public function getMenu($mixed)
    {
        /** @var ResolverManagerInterface $resolver URL Resolver */
        $resolver = $this->app->make(ResolverManagerInterface::class);

        // Get default block menu instance
        $menu = parent::getMenu($mixed);

        $menu->addItem(new DividerItem());
        $menu->addItem(new LinkItem('#', t('Information'), [
            'dialog-title' => t('Block Information'),
            'data-menu-action' => 'block_dialog',
            'data-menu-href' => $resolver->resolve(['/ccm/package/advanced_block_menu/dialogs/block/info']),
            'dialog-width' => 600,
            'dialog-height' => 400,
        ]));

        return $menu;
    }
}
