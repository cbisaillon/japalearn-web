<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export kanas, kanjis and everything.';

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
        $this->call("export:kanas");
        $this->call("export:kanjis");
    }
}
