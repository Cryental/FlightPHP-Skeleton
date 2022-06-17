<?php

namespace Ntric\Backend\Databases;

use SleekDB\Store;

class UserDatabase extends Database
{
    protected $database;

    public function __construct()
    {
        $this->database = new Store('users', __DIR__ . '/../../storage/databases', [
            "auto_cache" => true,
            "timeout" => false,
            "primary_key" => "_id"
        ]);
    }
}