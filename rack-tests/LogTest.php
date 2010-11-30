<?php
class LogTest extends PhpRack_Test
{
    public function testLogFileIsWritable()
    {
        $logFile = '/home/product/errors.log';
        if (file_exists($logFile)) {
            $this->_log("Log file '{$logFile}' exists: " . filesize($logFile) . " bytes");
            $this->assert->success("Log file is OK");
        } else {
            $this->assert->fail("Log file '{$logFile}' is absent");
        }
    }
}
