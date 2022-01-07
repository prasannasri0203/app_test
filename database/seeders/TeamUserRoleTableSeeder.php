<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TeamUserRoleTableSeeder extends Seeder
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
	            'role' 		=> 'Admin',
	        ],
	        [
	            'role' 		=> 'Editor',
	        ],
	        [
	            'role' 		=> 'Approver',
	        ],
	        [
	            'role' 		=> 'Viewer',
	        ],
	    ];
         DB::table('team_user_roles')->insert($users);
    }
}
