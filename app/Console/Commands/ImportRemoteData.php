<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportRemoteData extends Command
{
    /**
     * This scheduler command imports remote ms-sql data & stores in our local tables.
     * @access Rights : Code Level
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
    */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importRemoteData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This scheduler command imports remote ms-sql data & stores in our local tables.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \App::call('App\Http\Controllers\CRON\ImportController@import_procedures');
    }
}
