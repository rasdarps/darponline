<?php

namespace App\Console\Commands;

use App\Http\Controllers\Backend\DailyTransactionEmailController;
use Illuminate\Console\Command;

class SendDailyTransactionEmail extends Command
{
    protected $signature = 'email:send-daily-transaction';
    protected $description = 'Send daily transaction email report (sales, expenses, cash shortage)';

    public function handle()
    {
        $this->info('Sending daily transaction email...');
        
        $controller = new DailyTransactionEmailController();
        $controller->sendAuto();
        
        $this->info('Daily transaction email sent successfully.');
    }
}
