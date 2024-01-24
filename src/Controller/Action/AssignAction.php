<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Controller\Action;

use Setono\SyliusCalloutPlugin\Message\Command\AssignCallouts;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AssignAction
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly TranslatorInterface $translator,
        private readonly RouterInterface $router,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->commandBus->dispatch(new AssignCallouts());

        $session = $request->getSession();
        if ($session instanceof Session) {
            $flashBag = $session->getFlashBag();

            $flashBag->add(
                'success',
                $this->translator->trans('setono_sylius_callout.callout.assign_started', [], 'flashes'),
            );
        }

        return new RedirectResponse($this->router->generate('setono_sylius_callout_admin_callout_index'));
    }
}
