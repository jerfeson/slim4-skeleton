<?php

namespace App\Console\Migration;

use App\Helpers\Crypto;
use App\Helpers\Password;
use App\Model\Model;
use DateTime;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Builder;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MigrationsCommand.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 */
class DefaultCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container The container
     * @param string|null $name The name
     */
    public function __construct(ContainerInterface $container, ?string $name = null)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    /**
     * Configure.
     *
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->setName('migrations');

        $this->addArgument(
            'down',
            InputArgument::OPTIONAL,
            'Do you want to delete existing data(N/y) ?',
            false
        );
        $this->setDescription("A migratons command \n Arguments: \n * down - Drop tables");
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input The input
     * @param OutputInterface $output The output
     *
     * @return int The error code, 0 on success
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(sprintf('<info>Starting migration</info>'));
        $connection = DB::connection('default');
        $schema = $connection->getSchemaBuilder();

        if ($input->getArgument('down') == true) {
            $output->writeln(sprintf('<info>Deleting data/info>'));
            $this->down($schema);
        }

        $output->writeln(sprintf('<info>Creating tables/info>'));
        $this->up($schema);
        $output->writeln(sprintf('<info>Inserting initial data/info>'));

        if ($input->getArgument('down') == true) {
            $this->data($connection);
        }

        $output->writeln(sprintf('<info>Migration completed</info>'));

        return 0;
    }

    /**
     * @param Builder $schema
     */
    private function down($schema)
    {
        //oauth_scope
        $schema->dropIfExists('migration');
        $schema->dropIfExists('oauth_scope');
        $schema->dropIfExists('oauth_refresh_token');
        $schema->dropIfExists('oauth_access_token');
        $schema->dropIfExists('user');
        $schema->dropIfExists('user_profile');
        $schema->dropIfExists('oauth_auth_code');
        $schema->dropIfExists('oauth_session');
        $schema->dropIfExists('client');
        $schema->dropIfExists('oauth_client');
    }

    /**
     * @param Builder $schema
     */
    private function up($schema)
    {
        if (!$schema->hasTable('migration')) {
            $schema->create('migration', function ($table) {
                $table->increments('id')->unsigned();
                $table->string('path', 255)->unique();
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }


        if (!$schema->hasTable('oauth_scope')) {
            $schema->create('oauth_scope', function ($table) {
                $table->increments('id')->unsigned();
                $table->string('description', 255);
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('oauth_client')) {
            //oauth_client
            $schema->create('oauth_client', function ($table) {
                $table->increments('id')->unsigned();
                $table->string('identifier', 255);
                $table->string('secret', 255);
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('oauth_session')) {
            //oauth_session
            $schema->create('oauth_session', function ($table) {
                $table->increments('id')->unsigned();
                $table->integer('oauth_client_id')->unsigned();
                $table->foreign('oauth_client_id')->references('id')->on('oauth_client');
                $table->string('owner_type', 255);
                $table->string('owner_id', 255);
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('oauth_auth_code')) {
            //oauth_auth_code
            $schema->create('oauth_auth_code', function ($table) {
                $table->increments('id')->unsigned();
                $table->integer('oauth_session_id')->unsigned();
                $table->foreign('oauth_session_id')->references('id')->on('oauth_session');
                $table->integer('expire_time')->nullable();
                $table->string('client_redirect_id', 255);
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('user_profile')) {
            //user
            $schema->create('user_profile', function ($table) {
                $table->increments('id')->unsigned();
                $table->string('name', 45);
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('client')) {
            //client
            $schema->create('client', function ($table) {
                $table->increments('id')->unsigned();

                //FK
                $table->integer('oauth_client_id')->unsigned();
                $table->foreign('oauth_client_id')->references('id')->on('oauth_client');
                $table->string('name', 45);
                $table->tinyInteger('status');
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('user')) {
            //user
            $schema->create('user', function ($table) {
                $table->increments('id')->unsigned();

                //FK
                $table->integer('user_profile_id')->unsigned();
                $table->foreign('user_profile_id')->references('id')->on('user_profile');
                $table->integer('client_id')->unsigned();
                $table->foreign('client_id')->references('id')->on('client');

                $table->string('username', 45);
                $table->string('password', 255);
                $table->tinyInteger('status');
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('oauth_access_token')) {
            //oauth_access_token
            $schema->create('oauth_access_token', function ($table) {
                $table->increments('id')->unsigned();
                $table->string('oauth_client_id', 255);

                //FK
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('user');

                $table->string('access_token', 255);
                $table->dateTime('expiry_date_time');
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }

        if (!$schema->hasTable('oauth_refresh_token')) {
            //oauth_access_token
            $schema->create('oauth_refresh_token', function ($table) {
                $table->increments('id')->unsigned();

                //FK
                $table->integer('oauth_access_token_id')->unsigned();
                $table->foreign('oauth_access_token_id')->references('id')->on('oauth_access_token');

                $table->integer('expire_time')->nullable();
                $table->string('refresh_token', 255);
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }


    }

    private function data($connection)
    {
        $date = new DateTime();

        $connection->table('user_profile')->insert([
            'name' => 'Administrator',
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $connection->table('oauth_client')->insert([
            //  Define your secret pattern
            'identifier' => Crypto::getToken(40),
            'secret' => Crypto::getToken(60),
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $connection->table('client')->insert([
            'name' => 'Administration',
            'oauth_client_id' => 1,
            'status' => Model::STATUS_ACTIVE,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        $connection->table('user')->insert([
            'user_profile_id' => 1,
            'client_id' => 1,
            'username' => 'admin',
            'password' => Password::hash('admin'),
            'status' => Model::STATUS_ACTIVE,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
