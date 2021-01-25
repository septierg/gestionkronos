<?php

use Illuminate\Database\Seeder;

class PretabcTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Connect to production database
        $live_database = DB::connection('mysql');
        // Get table data from production
        foreach($live_database->table('membres')->get() as $data){
            // Save data to staging database - default db connection
            DB::table('pretabc_membres')->insert((array) $data);
        }
        // Get table_2 data from production
        foreach($live_database->table('prets')->get() as $data){
            // Save data to staging database - default db connection
            DB::table('pretabc_prets')->insert((array) $data);
        }

    }
}
