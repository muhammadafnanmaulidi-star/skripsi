<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetMenuStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:reset-stock';

    protected $description = 'Reset stock menu harian (Minuman 50, Makanan 50)';

    public function handle()
    {
        $this->info('Memulai reset stok menu...');

        // Reset Foods (ID: 1)
        \App\Models\Menu::where('category_id', 1)->update([
            'stock' => 50,
            'is_available' => true
        ]);
        $this->info('Stok Makanan berhasil direset ke 50.');

        // Reset Drinks (ID: 2)
        \App\Models\Menu::where('category_id', 2)->update([
            'stock' => 50,
            'is_available' => true
        ]);
        $this->info('Stok Minuman berhasil direset ke 50.');

        $this->info('Reset stok selesai!');
    }
}
