<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = [
	        [
	            'role' 		=> 'Team',
	        ],
	        [
	            'role' 		=> 'Trial',
	        ],
	        [
	            'role' 		=> 'Individual',
	        ],
	        [
	            'role' 		=> 'Enterpriser',
	        ],
	    ];
         DB::table('user_roles')->insert($users);
    }
}
