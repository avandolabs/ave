<?php

declare(strict_types=1);

namespace Avando\AveBundle\EventListener;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Minifies HTML, inline CSS and inline JS for Contao frontend Twig responses.
 *
 * Contao's built-in minifyMarkup only runs for legacy PHP layouts (FrontendTemplate).
 * Modern Twig layouts go through LayoutTemplate::getResponse() which has no minification.
 * This listener fills that gap — it mirrors what Template::minifyHtml() does.
 */
#[AsEventListener(event: KernelEvents::RESPONSE, priority: -1024)]
class MinifyHtmlListener
{
    public function __construct(private readonly bool $debug) {}

    public function __invoke(ResponseEvent $event): void
    {
        if ($this->debug || !$event->isMainRequest()) {
            return;
        }

        if ($event->getRequest()->attributes->get('_scope') !== 'frontend') {
            return;
        }

        $response = $event->getResponse();

        if (!str_contains((string) $response->headers->get('Content-Type', ''), 'text/html')) {
            return;
        }

        $html = $response->getContent();

        if (!$html) {
            return;
        }

        $response->setContent($this->minify($html));
    }

    private function minify(string $html): string
    {
        $chunks = preg_split(
            '@(</?pre[^>]*>)|(</?script[^>]*>)|(</?style[^>]*>)|( ?</?textarea[^>]*>)@i',
            $html,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY,
        );

        // A PCRE failure (backtrack limit on a huge document, say) returns false.
        // Falling through with an empty chunk list would replace the response
        // body with an empty string — ship the unminified page instead.
        if (!\is_array($chunks)) {
            return $html;
        }

        $result       = '';
        $preserveNext = false;
        $optimizeNext = false;
        $type         = null;

        foreach ($chunks as $chunk) {
            if (strncasecmp($chunk, '<pre', 4) === 0 || strncasecmp(ltrim($chunk), '<textarea', 9) === 0) {
                $preserveNext = true;
            } elseif (strncasecmp($chunk, '<script', 7) === 0) {
                $optimizeNext = true;
                // Only minify real JavaScript. Skip JSON-LD, application/json,
                // import maps, x-templates etc. — running the JS minifier on them
                // corrupts the payload (e.g. schema.org structured data).
                $type         = $this->isJavaScript($chunk) ? 'js' : null;
            } elseif (strncasecmp($chunk, '<style', 6) === 0) {
                $optimizeNext = true;
                $type         = 'css';
            } elseif ($preserveNext) {
                $preserveNext = false;
            } elseif ($optimizeNext) {
                $optimizeNext = false;

                try {
                    if ($type === 'js') {
                        $minifier = new JS();
                        $minifier->add($chunk);
                        $chunk = $minifier->minify();
                    } elseif ($type === 'css') {
                        $minifier = new CSS();
                        $minifier->add($chunk);
                        $chunk = $minifier->minify();
                    }
                } catch (\Throwable) {
                    // A single malformed inline block must not 500 the whole
                    // response — keep the original chunk untouched.
                }
            } else {
                $chunk = str_replace("\r", '', $chunk);
                // Whitespace between two tags is NOT always insignificant: for
                // inline elements it is a word separator, so deleting it turns
                // "<b>world</b>  <i>again</i>" into "worldagain". These three
                // rules only ever shrink runs of whitespace down to a single
                // character, never to nothing — same set Contao's own
                // Template::minifyHtml() uses.
                $chunk = preg_replace(
                    [
                        '/^[\t ]+/m',   // strip leading whitespace per line
                        '/[\t ]+$/m',   // strip trailing whitespace per line
                        '/\n\n+/',      // collapse multiple blank lines
                        '/[\t ]{2,}/',  // collapse runs of spaces/tabs to one
                    ],
                    ['', '', "\n", ' '],
                    $chunk,
                ) ?? $chunk;
            }

            $result .= $chunk;
        }

        return trim($result);
    }

    /**
     * Returns true if a <script> opening tag denotes executable JavaScript.
     * A missing type attribute means classic JS; any explicit non-JS type
     * (application/ld+json, application/json, importmap, text/x-template, …)
     * must be left untouched.
     */
    private function isJavaScript(string $openingTag): bool
    {
        if (!preg_match('/\stype\s*=\s*("|\')?([^"\'\s>]+)/i', $openingTag, $m)) {
            return true;
        }

        return \in_array(strtolower(trim($m[2])), [
            'text/javascript',
            'application/javascript',
            'text/ecmascript',
            'application/ecmascript',
            'module',
        ], true);
    }
}
