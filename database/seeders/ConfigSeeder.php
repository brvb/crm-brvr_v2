<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Tenant\Config::create([
            'id' => 1,
            'company_name' => '* demo *',
            'vat' => "000",
            'contact' => "000",
            'email' => "demo@demo.demo",
            'address' => "* demo *",
            'logotipo' => "",
            'sender_email' => "demo@demo.demo",
            'sender_name' => "* demo *",
            'sender_cc_email' => ' ',
            'sender_cc_name' => ' ',
            'sender_bcc_email' => ' ',
            'sender_bcc_name' => ' ',
            'signature' => ''
        ]);
    }
}
