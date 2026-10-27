<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

#[Signature('admin:create {--name=} {--email=}')]
#[Description('Create a user who can access the portfolio management dashboard')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Name');
        $email = $this->option('email') ?: $this->ask('Email address');
        $password = $this->secret('Password (minimum 12 characters)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        User::create($validator->validated());
        $this->components->info('Dashboard user created successfully.');

        return self::SUCCESS;
    }
}
