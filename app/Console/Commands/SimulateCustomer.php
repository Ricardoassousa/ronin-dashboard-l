<?php

namespace App\Console\Commands;

use App\Events\CustomerCreated;
use App\Models\Customer;
use Illuminate\Console\Command;

class SimulateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulates the registration of a new external customer';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customer = Customer::factory()->create();

        event(new CustomerCreated($customer));

        $this->info('Customer simulated successfully!');

        return Command::SUCCESS;
    }

}
