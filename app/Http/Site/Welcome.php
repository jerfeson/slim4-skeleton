<?php

namespace App\Http\Site;

use App\Http\Controller;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Welcome
 * @package App\Http\Site
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 * @since   1.0.0
 * @version 1.0.0
 */
class Welcome extends Controller
{
    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index()
    {

        // log some message
        $this->getLogger()->info("log a message");

        return $this->getView()->render($this->getResponse(), "@site/test/welcome.twig");
    }
}