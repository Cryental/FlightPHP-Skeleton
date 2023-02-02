<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;

class Table_1 implements ITable
{
    public function up()
    {
        DB::schema('mysql')->create('tableName', function (Blueprint $table) {
            $table->id();
        });
    }

    public function down()
    {
        DB::schema('mysql')->dropIfExists('tableName');
    }
}
