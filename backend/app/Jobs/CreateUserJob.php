<?php

namespace App\Jobs;

use App\Mail\UserCreatedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CreateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Store user data here
    protected $userData;

    /**
     * Create a new job instance.
     *
     * @param array $userData
     * @return void
     */
    public function __construct(array $userData)
    {
        $this->userData = $userData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::create([
            'name' => $this->userData['name'],
            'email' => $this->userData['email'],
            'password' => bcrypt('test123456@'),
        ]);

        $user->assignRole('user');

        Mail::to($user->email)->send(new UserCreatedMail($user));
    }

}
