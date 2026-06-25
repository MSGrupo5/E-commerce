<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Idempotente: en bases donde `info_entrega`/`provincia`/`ciudad` ya existen
     * (migradas manualmente fuera de git por otra rama) no hace nada; en una
     * base limpia, renombra `direccion_entrega` y agrega los campos nuevos.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'direccion_entrega') && ! Schema::hasColumn('users', 'info_entrega')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('direccion_entrega', 'info_entrega');
            });
        } elseif (! Schema::hasColumn('users', 'info_entrega')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('info_entrega', 500)->nullable()->after('apellido');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'provincia')) {
                $table->string('provincia', 100)->nullable()->after('info_entrega');
            }
            if (! Schema::hasColumn('users', 'ciudad')) {
                $table->string('ciudad', 100)->nullable()->after('provincia');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ciudad')) {
                $table->dropColumn('ciudad');
            }
            if (Schema::hasColumn('users', 'provincia')) {
                $table->dropColumn('provincia');
            }
        });

        if (Schema::hasColumn('users', 'info_entrega') && ! Schema::hasColumn('users', 'direccion_entrega')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('info_entrega', 'direccion_entrega');
            });
        }
    }
};
