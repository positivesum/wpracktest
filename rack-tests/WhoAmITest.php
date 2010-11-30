<?php
class WhoAmITest extends PhpRack_Test
{
    public function testWhoAmI()
    {
        // validate the current user is "apache"
        $this->assert->shell->exec('whoami', '/apache/');
    }
}
