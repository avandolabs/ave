<?php

declare(strict_types=1);

namespace Avando\AveBundle\Controller\FrontendModule;

use Contao\ArticleModel;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(type: 'ave_onepage_nav', category: 'navigationModules', template: 'frontend_module/mod_ave_onepage_nav')]
class OnepageNavController extends AbstractFrontendModuleController
{
    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        // A manual "Label | #anchor" list takes precedence. When it is empty
        // (the default) the nav is built automatically from every published
        // article on the current page that carries a CSS ID.
        $items = self::parseLinks((string) ($model->onepage_links ?? ''));

        if (!$items) {
            $items = $this->autoItems();
        }

        $template->items = $items;

        return $template->getResponse();
    }

    /**
     * Auto-discovers the onepage sections: every published article in the main
     * column of the current page that has a CSS ID. The article title becomes
     * the label, the CSS ID becomes the "#anchor". Articles without an ID are
     * skipped — they cannot be linked to.
     *
     * @return list<array{label: string, href: string}>
     */
    private function autoItems(): array
    {
        $page = $this->getPageModel();

        if (null === $page) {
            return [];
        }

        $articles = ArticleModel::findPublishedByPidAndColumn((int) $page->id, 'main');

        if (null === $articles) {
            return [];
        }

        $items = [];

        foreach ($articles as $article) {
            $cssID = StringUtil::deserialize($article->cssID, true);
            $id = trim((string) ($cssID[0] ?? ''));

            if ('' === $id) {
                continue;
            }

            $items[] = [
                'label' => $article->title ?: $id,
                'href'  => '#' . $id,
            ];
        }

        return $items;
    }

    /**
     * Parses the optional "Label | #anchor" textarea into a list of nav items.
     *
     * Lines without a pipe, or with an empty label or anchor, are skipped.
     * The anchor is always normalised to a single leading '#', so a
     * javascript:/http: URL can never reach the template.
     *
     * @return list<array{label: string, href: string}>
     */
    public static function parseLinks(string $raw): array
    {
        $items = [];

        foreach (StringUtil::trimsplit("\n", $raw) as $line) {
            if (!str_contains($line, '|')) {
                continue;
            }

            [$label, $anchor] = array_map('trim', explode('|', $line, 2));
            $anchor = '#' . ltrim($anchor, '#');

            if ($label && strlen($anchor) > 1) {
                $items[] = ['label' => $label, 'href' => $anchor];
            }
        }

        return $items;
    }
}
