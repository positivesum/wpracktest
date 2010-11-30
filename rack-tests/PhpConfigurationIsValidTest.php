<?php

class PhpConfigurationIsValidTest extends PhpRack_Test
{
    public function testPhpVersionIsCorrect()
    {
        // version of PHP is equal or higher than 5.2
        $this->assert->php->version
            ->atLeast('5.2');
    }
    public function testPhpExtensionsAreValid()
    {
        // validate the all required PHP extensions are loaded
        $this->assert->php->extensions
            ->isLoaded('simplexml')
            ->isLoaded('fileinfo')
            ->isLoaded('xsl');
        // validate that fileinfo is loaded and configured properly
        $this->assert->php->extensions->fileinfo->isAlive();
    }
    public function testViewPhpinfo()
    {
        // show plain-text version of phpinfo()
        $this->assert->php
            ->phpinfo();
    }
    public function testPhpLint()
    {
        $options = array(
            'extensions' => 'php,phtml',
            'exclude' => '/\.svn/',
        );
        // lint validation of all files in the directory
        $this->assert->php->lint('/../../application', $options);
    }
    public function testPear()
    {
        $this->assert->php->pear
            ->showList() // show full list of available PEAR packages
            ->package('phing/phing')->atLeast('2.4.1') // it exists and the version is at least 2.4.1
            ->package('pear.phpunit.de/PHPUnit')->exactly('3.4.12') // in this exact version
            ->package('VersionControl_SVN') // make sure it exists
            ->package('HTTP_Request2');
    }
    public function testPhpIni()
    {
        $this->assert->php
            ->ini('short_open_tag') // make sure it is set to TRUE in php.ini
            ->ini('memory_limit')->atLeast('128M'); // at least 128M is set for memory_limit
    }
    public function testPhpFunctions()
    {
        $this->assert->php
            ->fnExists('lcfirst') // validate that lcfirst() exists
            ->fnExists('imagejpeg'); // validate another function, etc.
    }
}
