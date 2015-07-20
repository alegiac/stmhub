<?php
namespace Platform\V1\Rpc\GetWidgets;

class GetWidgetsControllerFactory
{
    public function __invoke($controllers)
    {
        return new GetWidgetsController();
    }
}
