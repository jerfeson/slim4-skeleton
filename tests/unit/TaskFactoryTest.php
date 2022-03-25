<?php

namespace App\Test\unit;

use App\Entity\Task\Task;
use App\Factory\TaskFactory;
use Codeception\Test\Unit;
use Exception;
use InvalidArgumentException;
use UnitTester;

class TaskFactoryTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var TaskFactory
     */
    protected TaskFactory $taskFactory;

    /**
     * @throws Exception
     */
    protected function _before()
    {
        $this->taskFactory = new TaskFactory();
    }

    protected function _after()
    {
    }

    /**
     * @dataProvider jsonValidProvider
     * @return void
     */
    public function testCreateTaskFromAValidArray(string $array)
    {
        $task = $this->taskFactory::fromArray((array) json_decode($array));

        $this->assertEquals(Task::class, get_class($task));
        $this->assertNotNull($task->description);
    }

    /**
     * @dataProvider jsonInvalidProvider
     * @return void
     */
    public function testCreateTaskFromAInvalidArray(string $array)
    {
        $this->expectException(InvalidArgumentException::class);

        $this->taskFactory::fromArray((array) json_decode($array));
    }

    /**
     * @return string[]
     */
    public function jsonValidProvider(): array
    {
        return [
            [
                file_get_contents("tests/_data/Factory/task/valid_task_1.json")
            ],
            [
                file_get_contents("tests/_data/Factory/task/valid_task_2.json")
            ],
            [
                file_get_contents("tests/_data/Factory/task/valid_task_3.json")
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function jsonInvalidProvider(): array
    {
        return [
            [
                file_get_contents("tests/_data/Factory/task/invalid_task_1.json")
            ],
            [
                file_get_contents("tests/_data/Factory/task/invalid_task_2.json")
            ],
            [
                file_get_contents("tests/_data/Factory/task/invalid_task_3.json")
            ],
        ];
    }
}
