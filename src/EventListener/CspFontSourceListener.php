<?php

declare(strict_types=1);

namespace Avando\AveBundle\EventListener;

use Contao\CoreBundle\Routing\ResponseContext\Csp\CspHandler;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContextAccessor;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * When a Content-Security-Policy is active, allow same-origin fonts for EVERY
 * visitor.
 *
 * The theme can self-host woff2 files (the Theme-Editor's download-on-save writes
 * @font-face rules into files/theme/CSS/theme-vars.css, or a site can add them by
 * hand in custom.css). Under a CSP those need `font-src 'self'` — and unlike the
 * editor drawer (which is back-end-user only), the fonts are served to all
 * visitors, so this has to run for every frontend response. No-op when no CSP is
 * configured.
 */
#[AsEventListener(event: KernelEvents::RESPONSE, priority: -512)]
class CspFontSourceListener
{
    public function __construct(private readonly ResponseContextAccessor $responseContextAccessor)
    {
    }

    public function __invoke(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if ('frontend' !== $event->getRequest()->attributes->get('_scope')) {
            return;
        }

        $context = $this->responseContextAccessor->getResponseContext();

        if (null === $context || !$context->has(CspHandler::class)) {
            return; // no CSP configured → nothing to relax
        }

        /** @var CspHandler $csp */
        $csp = $context->get(CspHandler::class);
        $csp->addSource('font-src', 'self');
    }
}
