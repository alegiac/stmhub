<?php
namespace Platform\V1\Rpc\GetPageByName;

class GetPageByNameControllerFactory
{
    public function __invoke($controllers)
    {
        return new GetPageByNameController();
    }
}
