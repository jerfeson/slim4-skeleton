<?php


namespace App\Console;


/**
 * Class Test
 * @package App\Console
 * @author  Jerfeson Guerreiro <jerfeson@codeis.com.br>
 * @since   1.0.0
 * @version 1.0.0
 */
class Test extends Command
{
    /**
     * @param $a
     * @param string $b
     * @return string
     */
    public function method($a, $b = 'foobar')
    {
        $this->getLogger()->info("logging a message");

        return
            "\nEntered console command with params: \n" .
            "a= {$a}\n" .
            "b= {$b}\n";
    }
}