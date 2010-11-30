<?php
class MyTest extends phpRack_Test
{
    public function testPhpVersionIsCorrect()
    {
        $this->assert->php->version
            ->atLeast('5.2');
    }
    public function testPhpExtensionsExist()
    {
        $this->assert->php->extensions
            ->isLoaded('xsl')
            ->isLoaded('simplexml')
            ->isLoaded('fileinfo');
    }
}
