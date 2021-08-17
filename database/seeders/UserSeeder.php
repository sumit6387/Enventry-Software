<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newuser = new User();
        $newuser->name = "Admin";
        $newuser->email = "sumitkumar993618@gmail.com";
        $newuser->password = Hash::make("sumitkumar@12");
        $newuser->role = "Admin";
        $newuser->company_name = "Smart Enventry";
        $newuser->logo = "https://smartenventry.ml/public/images/invoicelogo.png";
        $newuser->save();
    }
}
