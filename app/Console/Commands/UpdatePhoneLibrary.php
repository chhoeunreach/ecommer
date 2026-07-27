<?php

namespace App\Console\Commands;

use App\Services\PhoneLibraryService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Update the independent phone library from a JSON source.
 */
class UpdatePhoneLibrary extends Command
{
    protected $signature = 'phone:update-library {--source= : Remote URL or local storage path to JSON payload}';

    protected $description = 'Update phone library brands, models, variants, specifications, and images without touching inventory.';

    /**
     * Execute the console command.
     */
    public function handle(PhoneLibraryService $service): int
    {
        $source = $this->option('source') ?: config('phone_library.source_url');

        if (!$source) {
            $this->error('No phone library source configured.');
            return self::FAILURE;
        }

        $payload = str_starts_with($source, 'http')
            ? Http::timeout(30)->get($source)->throw()->json()
            : json_decode(Storage::get($source), true);

        if (!is_array($payload)) {
            $this->error('The phone library source did not return valid JSON.');
            return self::FAILURE;
        }

        $log = $service->updateFromPayload($payload, null, $source);
        $this->info($log->message);

        return self::SUCCESS;
    }
}
