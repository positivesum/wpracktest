<?php
class FilesTest extends PhpRack_Test
{
    public function testShowFiles()
    {
        // show directory structure
        $this->assert->disc
            ->showDirectory(
                '/home/product', // list all files in this directory and beneath
                array(
                    'exclude' => array( // exclude files that match this pattern(s)
                        '/\.svn/', 
                    ),
                    'maxDepth' => 1, // maximum directory depth to show
                )
            );
    }
    public function testFreeSpace()
    {
        // test that we have enough free disc space
        $this->assert->disc->freeSpace
            ->atLeast(10); // 10 Mb at least
    }
    public function testSingleFile()
    {
        $file = '../../test.log';
        $this->assert->disc->file
            ->cat($file) // show full content of the file
            ->head($file, 10) // show 10 first lines of the file
            ->tail($file, 5) // show 5 last lines of the file
            ->exists($file) // OK if file exists
            ->isReadable($file) // OK if this file is readable
            ->isWritable($file) // OK if this file is writable (has enough permissions)
            ->isDir($file); // OK if it's a directory

    }
    public function testIncrementalLogView()
    {
        // in AJAX web front this assertion will show a file, keeping its latest 25 lines visible
        // and guaranteeing that any lines stays visible for at least 10 seconds
        $this->assert->disc->file
            ->tailf('../my-log.txt', 25, 10);
    }
}

