<?php

namespace App\Jobs;

use App\Exports\MSSQLDumps;
use App\Exports\ProductMasterMsSqlDump;
use App\Models\ProductMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class ExportProductsMasterTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $from_date;
    protected $to_date;
    // protected $query_array;
    protected $today;
    protected $rand_string;
    protected $export;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($from_date, $to_date)
    {
        $this->export = new ProductMasterMsSqlDump($from_date, $to_date);
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->today = Carbon::today()->toDateString();
        $this->rand_string = rand(10, 1000);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);


        // return $this->query_array;

        // return (new ProductMasterMsSqlDump($this->query_array))->store('exports/products.xlsx', 'public');
        $path = Excel::download($this->export, 'text.xlsx');
        echo $path;
    }
}
