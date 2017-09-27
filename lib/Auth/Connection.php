<?php
namespace App\Auth;

class Connection
{
    public function __construct($dbhost, $dbname, $dbuser, $dbpassword)
    {
        $this->host = $dbhost;
        $this->dbname = $dbname;
        $this->user = $dbuser;
        $this->pass = $dbpassword;
        $this->conn = false;
    
        return $this->connect();
    }

    private function connect()
    {
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->dbname);

        if (mysqli_connect_errno()) {
            return false;
        }
    }

    public function close()
    {
        return mysqli_close($this->conn);
    }

    public function getConnection()
    {
        return $this->conn;
    }

    /*
    CREATE TABLE `users` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `name` varchar(255) NOT NULL,
     `email` varchar(255) NOT NULL,
     `password` varchar(255) NOT NULL,
     `avatar` varchar(255) NULL DEFAULT NULL,
     `subscription_type` varchar(16) NOT NULL DEFAULT 'free',
     `is_admin` int(1) NOT NULL DEFAULT '0',
     `remember_token` varchar(100) DEFAULT NULL,
     `access_token` varchar(255) NOT NULL,
     `access_key` varchar(64) NOT NULL,
     `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `deleted_at` timestamp NULL DEFAULT NULL,
     PRIMARY KEY (`id`),
     UNIQUE KEY `email` (`email`),
     KEY `email_2` (`email`),
     KEY `id` (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1
    */
}

