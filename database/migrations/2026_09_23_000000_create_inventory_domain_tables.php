<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk', 150);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('nama_material', 150);
            $table->string('satuan', 30);
            $table->unsignedInteger('stok_sistem')->default(0);
            $table->unsignedInteger('stok_wip')->default(0);
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->string('nama_bom', 150);
            $table->timestamps();
            $table->index('produk_id');
        });

        Schema::create('detail_boms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained('boms')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_kebutuhan');
            $table->timestamps();
            $table->unique(['bom_id', 'material_id']);
            $table->index('material_id');
        });

        Schema::create('permintaan_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_produksi');
            $table->enum('status_permintaan', [
                'pending',
                'siap_diproduksi',
                'menunggu_po',
                'menunggu_material',
                'work_in_process',
                'material_po_cacat',
                'selesai',
            ])->default('pending');
            $table->date('tanggal_permintaan');
            $table->timestamps();
            $table->index('status_permintaan');
            $table->index('produk_id');
            $table->index('user_id');
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_po', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('permintaan_produksi_id')->nullable()->constrained('permintaan_produksis')->nullOnDelete();
            $table->enum('status_po', ['diajukan', 'approved', 'rejected'])->default('diajukan');
            $table->date('tanggal_po');
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
            $table->index('status_po');
            $table->index('user_id');
        });

        Schema::create('detail_pos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_material');
            $table->unsignedInteger('jumlah_diterima')->nullable();
            $table->unsignedInteger('jumlah_cacat')->default(0);
            $table->text('keterangan_cacat')->nullable();
            $table->timestamps();
            $table->unique(['purchase_order_id', 'material_id']);
            $table->index('material_id');
        });

        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('materials')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('stok_sistem');
            $table->unsignedInteger('stok_fisik');
            $table->integer('selisih_stok');
            $table->date('tanggal_opname');
            $table->timestamps();
            $table->index('material_id');
            $table->index('user_id');
            $table->index('tanggal_opname');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opnames');
        Schema::dropIfExists('detail_pos');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('permintaan_produksis');
        Schema::dropIfExists('detail_boms');
        Schema::dropIfExists('boms');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('produk');
    }
};
