<?php

declare(strict_types=1);

namespace Setono\SyliusCalloutPlugin\Controller\Action;

use Setono\SyliusCalloutPlugin\Callout\Assigner\CalloutAssignerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CalloutAssignAction
{
    private CalloutAssignerInterface $calloutAssigner;

    private RequestStack $requestStack;

    private TranslatorInterface $translator;

    private RouterInterface $router;

    public function __construct(
        CalloutAssignerInterface $calloutAssigner,
        RequestStack $requestStack,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $this->calloutAssigner = $calloutAssigner;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function __invoke(Request $request): Response
    {
        $this->calloutAssigner->assign();

        /** @var Session $session */
        $session = $this->requestStack->getSession();
        /** @var FlashBagInterface $flashBag */
        $flashBag = $session->getBag('flashes');
        $flashBag->add(
            'success',
            $this->translator->trans('setono_sylius_callout.callout.assign_started', [], 'flashes')
        );

        return new RedirectResponse(
            $this->router->generate('setono_sylius_callout_admin_callout_index')
        );
    }
}
