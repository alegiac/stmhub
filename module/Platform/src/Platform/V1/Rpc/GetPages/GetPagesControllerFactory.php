<?php
namespace Platform\V1\Rpc\GetPages;

class GetPagesControllerFactory
{
    public function __invoke($controllers)
    {
        return new GetPagesController();
    }
}
