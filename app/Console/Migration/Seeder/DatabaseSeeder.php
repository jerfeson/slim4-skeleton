<?php


namespace App\Console\Migration\Seeder;

use _HumbugBoxbde535255540\Roave\BetterReflection\Reflection\ReflectionClass;
use Illuminate\Database\Capsule\Manager as DB;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class DatabaseSeeder extends Command
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

        $this->setName('seeder');
        $this->setDescription("A seeder command");
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
        try {
            $output->writeln(sprintf('<info>Starting migration</info>'));
            $connection = DB::connection('default');
            $schema = $connection->getSchemaBuilder();

            $seederPath = MIGRATION_PATH . 'Seeder/';
            $files = scandir($seederPath);
            $connection->beginTransaction();
            foreach ($files as $file) {
                if ($file === '.' || $file === '..' ||  $file === 'DatabaseSeeder.php') {
                    continue;
                }

                include $file;
                
                $php_code = file_get_contents($seederPath . $file);
                $class = "App\\Console\Migration\\Seeder\\" . $this->get_php_classes($php_code)[0];
                $output->writeln(sprintf("<info>{$class}</info>"));

                $date = new \DateTimeImmutable();

                $connection->table('migration')->insert([
                    'path' => $class,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                $inscanceClasss = new $class();
                $inscanceClasss->run($connection, $output);

            }
            $connection->commit();
            $output->writeln(sprintf('<info>Seeder completed</info>'));
        } catch (\Exception $exception) {
            $connection->rollBack();
            $output->writeln(sprintf('<error>Seeder Aborted, no data saved, run composer console:seeder</error>'));
            $output->writeln($exception->getMessage());
            return Command::FAILURE;
        }


        return Command::SUCCESS;
    }

    private function get_php_classes($php_code) {
        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if (   $tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }
        return $classes;
    }
}