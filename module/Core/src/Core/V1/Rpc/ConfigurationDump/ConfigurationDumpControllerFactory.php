<?php
namespace Core\V1\Rpc\ConfigurationDump;

class ConfigurationDumpControllerFactory
{
    public function __invoke($controllers)
    {
        return new ConfigurationDumpController();
    }
}
