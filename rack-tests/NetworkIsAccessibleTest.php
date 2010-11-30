<?php
class NetworkIsAccessibleTest extends PhpRack_Test
{
    public function testPortsAreOpen()
    {
        // the port is open and accessible
        $this->assert->network->ports
            ->isOpen(80, 'aws.amazon.com');
        // incoming port is open
        $this->assert->network->ports
            ->isOpen(80);
    }
    public function testUrlIsAccessible()
    {
        // validate that the URL is accessible
        $this->assert->network->url
            ->url('http://www.google.com') // set URL (and validate it here)
            ->regex('/google\.com/'); // make HTTP call and find pattern in result
    }
}
