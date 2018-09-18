<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $records = 
    	[
	    	[
				'name'=>'Benjamin Locher',
				'email'=>'blocher@gmail.com',
				'email_verified_at'=>Carbon::now(),
				'password'=> Hash::make('PGv2Wqfa0C1C'),
				'api_token'=>'85655d32-77d8-46ba-890e-779fb7f615d0',
	    	],
	    	[
				'name'=>'Lenny Pratt',
				'email'=>'lenny.pratt@hireanesquire.com>',
				'email_verified_at'=>Carbon::now(),
				'password'=>Hash::make('jcKVTqwkNB9s'),
				'api_token'=>'1ee567a5-83ae-4309-8f2b-3ad94bcc94dd',
	    	],

	    	
    	];

    	foreach($records as $record) {

    		$record = User::create($record);

    	}
    }
}
