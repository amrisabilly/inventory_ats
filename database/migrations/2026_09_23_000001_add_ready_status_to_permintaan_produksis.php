<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('permintaan_produksis')) {
            DB::statement("ALTER TABLE permintaan_produksis MODIFY status_permintaan ENUM('pending','siap_diproduksi','menunggu_po','menunggu_material','work_in_process','material_po_cacat','selesai') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('permintaan_produksis')) {
            DB::statement("UPDATE permintaan_produksis SET status_permintaan = 'pending' WHERE status_permintaan = 'siap_diproduksi'");
            DB::statement("ALTER TABLE permintaan_produksis MODIFY status_permintaan ENUM('pending','menunggu_po','menunggu_material','work_in_process','material_po_cacat','selesai') NOT NULL DEFAULT 'pending'");
        }
    }
};
