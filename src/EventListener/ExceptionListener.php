<?php

namespace App\EventListener;

use App\Service\MailerService;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ExceptionListener
{

    /**
     * @var MailerService
     */
    private $mailer;

    /**
     * @var String
     */
    private $environment;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * ExceptionListener constructor.
     * @param ContainerInterface $container
     * @param MailerService $mailer
     * @param String $environment
     */
    public function __construct(String $environment, ContainerInterface $container, MailerService $mailer)
    {
        $this->twig = $container->get('twig');
        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    /**
     * @param ExceptionEvent $event
     * @return string|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function onKernelException(ExceptionEvent $event)
    {
        if ($this->environment === 'dev') {
            return;
        }

        $exception = $event->getException();
        $response = new Response();

        if ($exception instanceof NotFoundHttpException) {
            $response->setStatusCode($exception->getStatusCode());
            $twig = $this->twig->render('bundles/TwigBundle/Exception/error404.html.twig');
            $response->setContent($twig);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $this->mailer->sendExceptionEmail($exception);
            $twig = $this->twig->render('bundles/TwigBundle/Exception/error.html.twig');
            $response->setContent($twig);
        }

        $event->setResponse($response);
    }
}