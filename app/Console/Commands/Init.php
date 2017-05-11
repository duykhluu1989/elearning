<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class Init extends Command
{
    protected $signature = 'init';

    protected $description = 'Init core data';

    public function handle()
    {
        Setting::initCoreSettings();

        User::initCoreUser();

        Role::initCoreRoles();

        UserRole::initCoreUserRoles();
    }
}