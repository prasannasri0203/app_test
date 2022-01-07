<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UserTemplate;
class UserTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('users')->whereIn('user_role_id',['1','2','3'])->where('parent_id',0)->where('plan_id','!=','0')->where('status',1)->get();

        foreach ($users as $user) {
            $chkUserExist = UserTemplate::where('user_id',$user->id)->first();
            if(!$chkUserExist){
                $template = \App\Models\UserTemplate::create([
                    'user_id' => $user->id,
                    'template_name' => 'Algorithm flowchart',
                    'status'=>'1'
                ]);
                $templateTrack = \App\Models\UserTemplateTrack::create([
                    'user_id' => $user->id,
                    'user_template_id' => $template['id'],
                    'status'=>'1'
                ]);
            }
        }
    }
}
