<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	State::create([
            'states_name' => 'Alberta',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'British Columbia',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Manitoba',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'New Brunswick',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Newfoundland',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Northwest Territories',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Nova Scotia',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Nunavut',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Ontario',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Prince Edward Island',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Quebec',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Saskatchewan',
            'status' =>1
        ]);
        State::create([
            'states_name' => 'Yukon',
            'status' =>1
        ]);
    }
}
