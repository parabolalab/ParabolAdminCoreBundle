<?php

namespace Parabol\AdminCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class CustomExceptionController extends \Symfony\Bundle\TwigBundle\Controller\ExceptionController
{
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {
        // $code = $exception->getStatusCode();

        // if(!$this->debug && $code == 404)
        // {
        //      return new RedirectResponse('/');
        // }

        return parent::showAction($request, $exception, $logger);
    }

}
