<?php

namespace Ruysu\Core\Commands\OAuth;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use LucaDegasperi\OAuth2Server\Storage\FluentClient;

class CreateClient extends Command implements SelfHandling
{

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'oauth:create-client {client_id : The client id, should be a string} {name :  The client friendly name} {--public : Wether this client is public}';

    /**
     * Command description
     * @var string
     */
    protected $description = 'Create an oauth client';

    /**
     * Client storage
     * @var FluentClient
     */
    protected $clients;

    /**
     * @param FluentClient $clients
     */
    public function __construct(FluentClient $clients)
    {
        $this->clients = $clients;
        parent::__construct();
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $secret = substr(sha1(time() . mt_rand()), 0, 40);

        $this->clients->create(
            $this->argument('name'),
            $this->argument('client_id'),
            ($public = $this->option('public')) ? '' : $secret
        );

        if (!$public) {
            $this->info("Here is the key for your new client:");
            $this->comment($secret);
        } else {
            $this->info("Your new client is ready");
        }

    }

}
