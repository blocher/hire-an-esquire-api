<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Candidate;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


    	$this->call(UserSeeder::class);
    	$this->call(CandidateSeeder::class);




    }
}
