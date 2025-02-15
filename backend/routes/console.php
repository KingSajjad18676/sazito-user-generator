<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:mark-offline', function () {
    $threshold = Carbon::now()->subSeconds(30);
    $affected = User::where('last_ping_at', '<', $threshold)
        ->update(['is_online' => false]);

    $this->info("{$affected} user(s) marked offline (no ping in the last 30 seconds).");
})->describe('Mark users offline if no ping received in the last 30 seconds');
