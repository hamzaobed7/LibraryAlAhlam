<?php

namespace App\Observers;

use App\Models\Book;
use App\Models\Payment;
use App\Models\Rental;
use App\Notifications\SendEmailRental;
use Illuminate\Support\Facades\DB;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        //
    }

   
    public function updated(Payment $payment): void
    {
        if ($payment->status === 'completed') {
            $bill = $payment->bill()->with("bill_items")->first();
            DB::transaction(function () use ($bill) {
                $bill->update(['status' => 'paid']);
                foreach ($bill->bill_items as $item) {
                    Rental::create([
                        'book_id' => $item->book_id,
                        'customer_id' => $bill->customer_id,
                        'due_date' => now(),
                        'returned_at' => now()->addDays(14),
                        'status' => 'borrowed',
                        'bill_item_id' => $item->id,
                    ]);
                }
                Book::whereIn('id', $bill->bill_items->pluck('book_id'))->decrement('stock', 1);
                $user=$bill->customer->user;
                $user->notify(new SendEmailRental());
            });
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
