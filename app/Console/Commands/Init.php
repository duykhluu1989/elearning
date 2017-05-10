<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Models\User;

class Init extends Command
{
    protected $signature = 'init';

    protected $description = 'Init core data';

    public function handle()
    {
        Setting::initCoreSettings();

        User::initCoreUser();
    }
}