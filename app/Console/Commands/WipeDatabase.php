<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WipeDatabase extends Command
{
    protected $signature = 'db:wipe-all';
    protected $description = 'Menghapus semua data dari semua tabel';

    public function handle()
    {
        Schema::disableForeignKeyConstraints();

        foreach (DB::select('SHOW TABLES') as $table) {
            $tableName = array_values((array) $table)[0];
            DB::table($tableName)->truncate();
            $this->info("Table '$tableName' truncated.");
        }

        Schema::enableForeignKeyConstraints();

        $this->info('✅ Semua data berhasil dihapus.');
    }
}
