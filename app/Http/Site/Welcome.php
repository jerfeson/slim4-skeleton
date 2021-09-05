<?php

namespace App\Http\Site;

use App\Http\Controller;
use App\Message\Message;
use DI\Annotation\Inject;
use Exception;
use Psr\Log\LoggerInterface;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Welcome.
 *
 * @author  Jerfeson Guerreiro <jerfeson_guerreiro@hotmail.com>
 *
 * @since   3.0.0
 *
 * @version 3.0.0
 */
class Welcome extends Controller
{
    /**
     * @Inject
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @Inject
     *
     * @var Twig
     */
    private Twig $view;

    /**
     * @inject
     *
     * @var Messages
     */
    private Messages $messages;

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function index()
    {
        // log some message
        $this->logger->info('log a message');

        //flash message
        $this->messages->addMessage(Message::STATUS_SUCCESS, 'A Flash message');

        return $this->view->render(
            $this->getResponse(),
            '@site/homepage/welcome.twig'
        );
    }
}
