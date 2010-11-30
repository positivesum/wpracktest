<?php
class QosTest extends PhpRack_Test
{
    public function testLatency()
    {
        $this->assert->qos->latency(
			// list of URL-s to test
            array('scenario' => array('http://www.example.com'),
                'averageMs' => 500, // 500ms average per request
                'peakMs' => 2000, // 2s maximum per request
            ));
    }
}

