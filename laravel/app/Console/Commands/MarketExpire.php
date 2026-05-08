<?php

namespace App\Console\Commands;

use App\Enums\MarketStatus;
use App\Models\MarketListing;
use Illuminate\Console\Command;

class MarketExpire extends Command
{
    protected $signature = 'market:expire';
    protected $description = 'Expire market listings past their expiry date';

    public function handle(): int
    {
        $count = MarketListing::where('status', MarketStatus::Active)
            ->where('expires_at', '<', now())
            ->update(['status' => MarketStatus::Expired]);

        $this->info("Expired {$count} listings.");

        return Command::SUCCESS;
    }
}
