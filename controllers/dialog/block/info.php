<?php
namespace Concrete\Package\AdvancedBlockMenu\Controller\Dialog\Block;

use Concrete\Controller\Backend\UserInterface\Block;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Validation\CSRF\Token;
use Symfony\Component\HttpFoundation\JsonResponse;

class Info extends Block
{
    protected $viewPath = '/dialogs/block/info';

    public function view()
    {
    }

    public function clearCache()
    {
        /** @var ErrorList $error */
        $error = $this->app->make('error');
        /** @var Token $token */
        $token = $this->app->make(Token::class);
        if (!$token->validate('block-cache-clear')) {
            $error->add($token->getErrorMessage());
        }
        if (!is_object($this->block)) {
            $error->add(t('The block instance not found.'));
        }

        if (!$error->has()) {
            $this->block->refreshBlockOutputCache();
            $response = new \stdClass();
            $response->title = t('Block output cache');
            $response->message = t('Block output cache successfully cleared.');

            return new JsonResponse($response);
        }

        return new JsonResponse($error);
    }

    protected function canAccess()
    {
        return $this->permissions->canEditBlock();
    }
}
