<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Profile;

class Init extends Command
{
    protected $signature = 'init';

    protected $description = 'Init core data';

    public function handle()
    {
        try
        {
            DB::beginTransaction();

            Setting::initCoreSettings();

            User::initCoreUser();

            Profile::initCoreProfile();

            Role::initCoreRoles();

            UserRole::initCoreUserRoles();

            DB::commit();

            echo 'Init Succeed';
        }
        catch(\Exception $e)
        {
            DB::rollBack();

            echo 'Init Failed: ' . $e->getMessage();
        }
    }
}