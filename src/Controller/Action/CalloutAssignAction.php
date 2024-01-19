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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CalloutAssignAction
{
    private CalloutAssignerInterface $calloutAssigner;

    /** @var RequestStack|SessionInterface */
    private $requestStackOrSession;

    private TranslatorInterface $translator;

    private RouterInterface $router;

    public function __construct(
        CalloutAssignerInterface $calloutAssigner,
        $requestStackOrSession,
        TranslatorInterface $translator,
        RouterInterface $router,
    ) {
        if ($requestStackOrSession instanceof SessionInterface) {
            trigger_deprecation('setono/sylius-callout-plugin', '1.2', 'Passing a SessionInterface to "%s" is deprecated, pass a RequestStack instead.', __METHOD__);
        }
        $this->calloutAssigner = $calloutAssigner;
        $this->requestStackOrSession = $requestStackOrSession;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function __invoke(Request $request): Response
    {
        $this->calloutAssigner->assign();

        if ($this->requestStackOrSession instanceof SessionInterface) {
            /** @var FlashBagInterface $flashBag */
            $flashBag = $this->requestStackOrSession->getBag('flashes');
        } elseif ($this->requestStackOrSession instanceof RequestStack) {
            /** @var Session $session */
            $session = $this->requestStackOrSession->getSession();
            /** @var FlashBagInterface $flashBag */
            $flashBag = $session->getBag('flashes');
        } else {
            throw new \LogicException(sprintf('The $requestStackOrSession argument must be an instance of "%s" or "%s", "%s" given.', RequestStack::class, SessionInterface::class, get_debug_type($this->requestStackOrSession)));
        }

        $flashBag->add(
            'success',
            $this->translator->trans('setono_sylius_callout.callout.assign_started', [], 'flashes'),
        );

        return new RedirectResponse(
            $this->router->generate('setono_sylius_callout_admin_callout_index'),
        );
    }
}
