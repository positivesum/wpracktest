<?php
class DatabaseTest extends PhpRack_Test
{
    public function testConnectionIsAlive()
    {
        // we validate that the DB is accessible
        $this->assert->db->mysql
            ->connect($host, $port, $username, $password) // we can connect
            ->dbExists($dbname) // DB exists
            ->tableExists('user'); // table "user" exists
    }
    public function testViewDatabaseSchema()
    {
        // we can use application-specific INI file, with "production" section inside
        $ini = new phpRack_Adapters_Config_Ini(APPLICATION_PATH . '/config/app.ini', 'production');
        $this->assert->db->mysql
            ->connect(
                $ini->resources->db->params->host,
                $ini->resources->db->params->port,
                $ini->resources->db->params->username,
                $ini->resources->db->params->password
            )
            ->dbExists($ini->resources->db->params->dbname)
            ->showSchema() // show entire DB schema
            ->showConnections(); // show full list of currently open connections
    }
    public function testViewSomeData()
    {
        $this->assert->db->mysql
            ->connect($host, $port, $username, $password)
            ->query('SELECT * FROM user LIMIT 5');
    }
}
