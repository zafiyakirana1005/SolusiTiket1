<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin';

    protected $description = 'Create or promote an administrator account';

    public function handle(): int
    {
        $name = trim((string) $this->ask('Admin name'));
        $email = strtolower(trim((string) $this->ask('Admin email')));

        if ($name === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->error('Name dan alamat email yang valid wajib diisi.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Password (minimal 12 karakter)');
        $confirmation = (string) $this->secret('Ulangi password');

        if (strlen($password) < 12 || ! hash_equals($password, $confirmation)) {
            $this->error('Password harus minimal 12 karakter dan kedua input harus sama.');

            return self::FAILURE;
        }

        $user = User::firstOrNew(['email' => $email]);
        $user->forceFill([
            'name' => $name,
            'password' => Hash::make($password),
            'role' => 'admin',
        ])->save();

        $this->info("Akun admin {$email} siap digunakan.");

        return self::SUCCESS;
    }
}