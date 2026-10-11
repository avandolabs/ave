<?php

declare(strict_types=1);

namespace Avando\AveBundle\EventListener;

use Contao\CoreBundle\Event\LayoutEvent;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FrontendTemplate;
use Contao\PageRegular;
use Contao\System;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Holt für Twig-Layouts die vier Seiten-Hooks nach, die Contao dort nicht ruft.
 *
 * AVE rendert über ein modernes Layout (tl_layout.type = 'modern'). Dessen Weg
 * (ContentCompositionBuilder) ruft weder getPageLayout noch generatePage, und
 * die Antwort entsteht ohne FrontendTemplate::compile(), also auch ohne
 * outputFrontendTemplate und modifyFrontendPage. Erweiterungen, die sich dort
 * einhängen, laufen ins Leere — die Oveleon-Cookiebar etwa baut ihr Banner in
 * generatePage, ohne den Hook fehlt es (oveleon/contao-cookiebar#284).
 *
 * Zeitpunkt: getPageLayout und generatePage laufen im LayoutEvent. Das kommt,
 * nachdem das Layout steht und bevor die Seite gerendert wird — derselbe Ort
 * wie im alten Layout. Was ein Hook per {% add %}, TL_CSS oder TL_BODY anmeldet,
 * landet so noch im <head> bzw. vor </body>. Die beiden Ausgabe-Hooks laufen
 * auf der fertigen Antwort, und die bekommt dabei ihren Content-Type schon so
 * früh wie im alten Layout (siehe onResponse).
 *
 * Doppelt laufen darf nichts. Ein Hook wird übersprungen, wenn seine Klasse
 * selbst auf LayoutEvent hört — dann kennt sie moderne Layouts schon (Contaos
 * News-/Kalender-Feeds, numero2/contao-opengraph3). Für Fälle, die das nicht
 * erkennt, gibt es SKIP und avando_ave.legacy_hooks.skip.
 */
class LegacyPageHooksListener
{
    /**
     * Rückrufe mit eigenem Weg für moderne Layouts, den die Regel oben nicht
     * sieht. Der Frontend-Helper ruft seinen Ausgabe-Hook aus einer anderen
     * Klasse (ModernLayoutResponseListener) selbst auf.
     */
    public const SKIP = [
        'rocksolid_frontend_helper.frontend_hooks::outputFrontendTemplateHook',
    ];

    private const MARKER = '_ave_legacy_page_hooks';

    /** @var array<class-string, bool> */
    private array $handlesLayoutEvent = [];

    /**
     * @param list<string> $skip
     */
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly RequestStack $requestStack,
        #[Autowire('%avando_ave.legacy_hooks.enabled%')]
        private readonly bool $enabled = true,
        #[Autowire('%avando_ave.legacy_hooks.skip%')]
        private readonly array $skip = [],
        #[Autowire('%kernel.debug%')]
        private readonly bool $debug = false,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    #[AsEventListener]
    public function onLayout(LayoutEvent $event): void
    {
        if (!$this->enabled || !$layout = $event->getLayout()) {
            return;
        }

        $this->requestStack->getCurrentRequest()?->attributes->set(self::MARKER, true);

        $page = $event->getPage();
        $pageRegular = null;

        foreach (['getPageLayout', 'generatePage'] as $hook) {
            foreach ($this->callbacks($hook) as [$listener, $method, $name]) {
                // Erst bei Bedarf: ohne alte Hooks kein Frontend-Objekt.
                $pageRegular ??= $this->createPageRegular();

                $this->call($name, static fn () => $listener->{$method}($page, $layout, $pageRegular));
            }
        }

        // Das moderne Layout gibt TL_CSS aus, TL_FRAMEWORK_CSS und TL_USER_CSS
        // nicht (ContentCompositionBuilder, end_of_head). Erweiterungen wie
        // erdmannfreunde/contao-grid-bundle legen ihr CSS aber dort ab.
        // Reihenfolge wie im alten Layout: Framework, Erweiterungen, Nutzer.
        $GLOBALS['TL_CSS'] = [
            ...$GLOBALS['TL_FRAMEWORK_CSS'] ?? [],
            ...$GLOBALS['TL_CSS'] ?? [],
            ...$GLOBALS['TL_USER_CSS'] ?? [],
        ];
        $GLOBALS['TL_FRAMEWORK_CSS'] = [];
        $GLOBALS['TL_USER_CSS'] = [];
    }

    /**
     * Früh (Priorität 64), weil das alte Layout beides schon im Controller
     * erledigt, also vor jedem kernel.response-Listener:
     *
     * Content-Type: Template::getResponse() setzt ihn sofort, LayoutTemplate
     * nicht — den trägt erst Symfonys ResponseListener nach (Priorität 0, aber
     * hinter den meisten anderen). Listener, die vorher prüfen, ob die Antwort
     * HTML ist, sehen bei modernen Layouts keinen Typ und tun nichts. Genau daran
     * scheitert auch das Einfügen der Oveleon-Cookiebar, nicht nur am
     * fehlenden generatePage.
     *
     * Ausgabe-Hooks: Sie liefen im alten Layout auf dem Seiten-Template, bevor
     * die Antwort das Kernel verließ.
     */
    #[AsEventListener(event: KernelEvents::RESPONSE, priority: 64)]
    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest() || !$event->getRequest()->attributes->get(self::MARKER)) {
            return;
        }

        $response = $event->getResponse();

        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'text/html; charset='.($response->getCharset() ?? 'UTF-8'));
        }

        if (!str_contains((string) $response->headers->get('Content-Type', ''), 'text/html')) {
            return;
        }

        $content = $response->getContent();

        if (!\is_string($content) || false === stripos($content, '</body>')) {
            return;
        }

        // Im alten Layout heißt das Seiten-Template fe_page, und genau darauf
        // prüfen Ausgabe-Hooks, wenn sie den Namen überhaupt ansehen.
        foreach (['outputFrontendTemplate', 'modifyFrontendPage'] as $hook) {
            foreach ($this->callbacks($hook) as [$listener, $method, $name]) {
                $this->call($name, static function () use ($listener, $method, &$content): void {
                    $result = $listener->{$method}($content, 'fe_page');

                    if (\is_string($result)) {
                        $content = $result;
                    }
                });
            }
        }

        $response->setContent($content);
    }

    /**
     * @return iterable<array{object, string, string}>
     */
    private function callbacks(string $hook): iterable
    {
        foreach ($GLOBALS['TL_HOOKS'][$hook] ?? [] as $callback) {
            if (!\is_array($callback) || !isset($callback[0], $callback[1]) || !\is_string($callback[0])) {
                continue;
            }

            [$id, $method] = $callback;
            $name = $id.'::'.$method;

            if (\in_array($name, self::SKIP, true) || \in_array($name, $this->skip, true)) {
                continue;
            }

            $listener = $this->framework->getAdapter(System::class)->importStatic($id);

            if ($this->handlesLayoutEvent($listener) || \in_array($listener::class.'::'.$method, $this->skip, true)) {
                continue;
            }

            yield [$listener, $method, $name];
        }
    }

    private function handlesLayoutEvent(object $listener): bool
    {
        return $this->handlesLayoutEvent[$listener::class] ??= (static function (object $listener): bool {
            foreach ((new \ReflectionObject($listener))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                $type = ($method->getParameters()[0] ?? null)?->getType();

                if ($type instanceof \ReflectionNamedType && is_a($type->getName(), LayoutEvent::class, true)) {
                    return true;
                }
            }

            return false;
        })($listener);
    }

    private function createPageRegular(): PageRegular
    {
        $pageRegular = $this->framework->createInstance(PageRegular::class);

        // Hooks schreiben gern in $pageRegular->Template. Im alten Layout ist
        // das fe_page; hier gibt es kein Seiten-Template, also eins, das niemand
        // ausgibt — Schreiben schlägt nicht fehl, bewirkt aber nichts.
        $pageRegular->Template = $this->framework->createInstance(FrontendTemplate::class, ['fe_page']);

        return $pageRegular;
    }

    /**
     * Ein nachgeholter Hook ist für Code geschrieben, der nie mit einem
     * modernen Layout gerechnet hat. Wirft er, soll das nicht die ganze Seite
     * kosten: protokollieren und weiter. Im Debug-Modus durchreichen, damit es
     * auffällt.
     */
    private function call(string $name, \Closure $call): void
    {
        try {
            $call();
        } catch (\Throwable $e) {
            if ($this->debug) {
                throw $e;
            }

            $this->logger?->error('Avando AVE: nachgeholter Seiten-Hook {hook} ist fehlgeschlagen: {message}', [
                'hook' => $name,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
        }
    }
}
