<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EncryptionKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encryption:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates API encrption key';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $newKey = \bin2hex(\random_bytes(32));
        $this->info("Encryption Key: $newKey");
        $this->info("Encryption ket genrated successfully, kindly copy and past in .env ENCRYPTION_KEY=$newKey");
    }
}
