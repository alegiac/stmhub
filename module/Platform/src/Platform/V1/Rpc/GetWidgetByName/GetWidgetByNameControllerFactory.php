<?php
namespace Platform\V1\Rpc\GetWidgetByName;

class GetWidgetByNameControllerFactory
{
    public function __invoke($controllers)
    {
        return new GetWidgetByNameController();
    }
}
