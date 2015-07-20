<?php
namespace Platform\V1\Rpc\GetMenuItems;

class GetMenuItemsControllerFactory
{
    public function __invoke($controllers)
    {
        return new GetMenuItemsController();
    }
}
