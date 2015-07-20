<?php
namespace Core\V1\Rpc\Heartbeat;

class HeartbeatControllerFactory
{
    public function __invoke($controllers)
    {
        return new HeartbeatController();
    }
}
