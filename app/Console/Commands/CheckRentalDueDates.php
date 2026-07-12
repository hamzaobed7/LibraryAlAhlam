<?php

namespace App\Console\Commands;

use App\Models\Rental;
use App\Notifications\RentalDueSoonNotification;
use Illuminate\Console\Command;

class CheckRentalDueDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-rental-due-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rentals = Rental::whereNull('returned_at')
            ->whereDate('due_date', now()->addDay())
            ->with('customer.user')
            ->get();

        foreach ($rentals as $rental) {
            $rental->customer->user
                ->notify(new RentalDueSoonNotification());
        }
    }
}
