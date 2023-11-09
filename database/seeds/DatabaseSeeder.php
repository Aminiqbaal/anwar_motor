<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $tables = ['workshops', 'customers', 'users', 'cashiers', 'managers', 'mechanics', 'services', 'categories', 'suppliers', 'spareparts', 'stocks', 'reports', 'loans', 'notifications'];//, 'transactions', 'sparepart_transaction_items', 'service_transaction_items', 'salaries'];
        foreach ($tables as $table) {
            $path = base_path('sql/' . $table . '.sql');
            DB::unprepared(file_get_contents($path));
        }
    }
}
