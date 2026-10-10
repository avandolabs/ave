<?php

declare(strict_types=1);

namespace Avando\AveBundle\Twig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * asset() plus a cache-busting stamp taken from the file's own mtime.
 *
 * Without an asset version strategy the theme's stylesheets are served as plain
 * static files, so a browser has no reason to re-fetch them. That is a problem
 * for two different kinds of file:
 *
 *  - the bundle's own CSS/JS, where an update would otherwise reach visitors
 *    only when their cache happens to expire;
 *  - files/theme/CSS/theme-vars.css, which the Design Studio REWRITES on every
 *    save. Without a stamp an admin changes the theme, sees the new look
 *    (the drawer busts its own cache by hand) and everyone else keeps the old
 *    one — the failure is invisible from the place where it is caused.
 *
 * The stamp is the mtime in hex: stable across deploys that do not touch the
 * file, so caches are not thrown away for nothing.
 *
 * The Contao Managed Edition already registers an mtime version strategy for
 * the default package, so asset() returns `…?v=<mtime>`. Then the URL is kept
 * as is — a second stamp would only duplicate the first.
 */
final class AssetVersionExtension extends AbstractExtension
{
    /** @var array<string, string> */
    private array $cache = [];

    public function __construct(
        private readonly Packages $packages,
        #[Autowire('%contao.web_dir%')]
        private readonly string $webDir,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ave_asset', $this->asset(...)),
        ];
    }

    public function asset(string $path): string
    {
        return $this->cache[$path] ??= $this->build($path);
    }

    private function build(string $path): string
    {
        $url = $this->packages->getUrl($path);

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        if (isset($query['v'])) {
            return $url;
        }

        $mtime = @filemtime($this->webDir.'/'.$path);

        // A file we cannot stat (missing, or served from somewhere else) simply
        // keeps the plain URL — a stamp is an optimisation, never a precondition.
        if (false === $mtime) {
            return $url;
        }

        return $url.(str_contains($url, '?') ? '&' : '?').'v='.dechex($mtime);
    }
}
