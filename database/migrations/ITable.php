<?php

namespace App\Database\Migrations;

interface ITable
{
    public function up();

    public function down();
}
