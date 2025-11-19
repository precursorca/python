<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class PythonInit extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('python', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('label')->nullable();
            $table->string('path')->nullable();
            $table->string('version')->nullable();
            $table->text('notes')->nullable();

            $table->index('serial_number');
            $table->index('label');
            $table->index('path');
            $table->index('version');
            $table->index('notes');

        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('python');
    }
}
