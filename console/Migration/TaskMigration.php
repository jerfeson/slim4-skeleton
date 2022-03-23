<?php

namespace Console\Migration;

use App\Helpers\Password;
use Console\Console;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskMigration extends Console
{
    /**
     * @var Builder
     */
    private Builder $schemaBuilder;

    /**
     * @var Connection
     */
    private Connection $connection;

    protected function configure()
    {
        $this->setName('app:create-database');
        $this->addArgument('drop', InputArgument::OPTIONAL, 'Delete created tables', false);
        $this->setDescription('Create full database');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting migration');
        $this->connection = DB::connection('default');
        $this->schemaBuilder = $this->connection->getSchemaBuilder();
        if ($input->getArgument('drop') == true) {
            $output->writeln([
                'Deleting tables',
                '============',
                '',
            ]);
        }

        $output->writeln([
            'Create tables',
            '============',
            '',
        ]);
        $this->createTables();
        $output->writeln([
            'Insert data',
            '============',
            '',
        ]);
        $this->insertData();
        $output->writeln([
            'Process successfully completed',
            '============',
            '',
        ]);

        return Command::SUCCESS;
    }

    private function createTables()
    {
        if (!$this->schemaBuilder->hasTable('task')) {
            $this->schemaBuilder->create('task', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('description', 255)->unique();
                $table->tinyInteger('done', false, true);
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
                $table->softDeletes('deleted_at', 0);
            });
        }
    }

    /**
     * Enter standard data.
     */
    private function insertData()
    {
        $date = new \DateTime();

        for ($i = 1; $i < 10; $i++) {
            $this->connection->table('task')->insert([
                'description' => "Task number {$i}",
                'done' => 0,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
