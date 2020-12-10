<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\Google\SheetsService;

class ExportPArticipants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-participants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all participants data to Google Sheets';

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
    public function handle(SheetsService $service)
    {
        $service->exportParticipants();
    }
}
