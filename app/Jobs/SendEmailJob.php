<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Ingredient;
use App\Notifications\IngredientOutOfStockNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Ingredient $ingredient) { }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::alert("Handling Email notification through Job...");
        # create a default User
        $customer = Customer::factory()->create();
        $customer->notify((new IngredientOutOfStockNotification($this->ingredient))->delay(2));
    }
}
