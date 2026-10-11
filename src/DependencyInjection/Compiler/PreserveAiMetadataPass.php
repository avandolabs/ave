<?php

declare(strict_types=1);

namespace Avando\AveBundle\DependencyInjection\Compiler;

use Contao\Image\Metadata\XmpFormat;
use Contao\Image\ResizeOptions;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Erhält beim Verkleinern die KI-Herkunftsangabe der Originaldatei.
 *
 * Generatoren und Bildprogramme tragen in KI-Bilder die IPTC-Angabe
 * DigitalSourceType ein (XMP, Namensraum Iptc4xmpExt). Contao schreibt in
 * seine verkleinerten Fassungen aber nur Copyright, Urheber, Quelle und
 * Credit — die Kennzeichnung des Generators fehlte damit in jedem Bild, das
 * die Website tatsächlich ausliefert. Hier kommt das Feld zur Liste dazu.
 *
 * Warum ein Compiler-Pass statt contao.image.preserve_metadata_fields: Das
 * Konfigurationsschema erlaubt je Format nur eine flache Liste, XMP braucht
 * aber Namensraum → Felder. Und die Option ERSETZT Contaos Vorgaben, statt
 * sie zu ergänzen. Der Pass hängt das Feld an das an, was die Bildfabriken
 * ohnehin bekommen: eine Projektkonfiguration oder Contaos Vorgaben.
 *
 * Es wird nichts erfunden: Fehlt die Angabe im Original, steht sie auch in
 * den Kopien nicht. C2PA lässt sich so nicht erhalten — jede Verkleinerung
 * bricht dessen Signatur.
 *
 * Mit `avando_ave: ai_label: false` entfällt der Pass (siehe AiLabelExtension).
 */
final class PreserveAiMetadataPass implements CompilerPassInterface
{
    public const NAMESPACE = 'http://iptc.org/std/Iptc4xmpExt/2008-02-29/';
    public const FIELD = 'DigitalSourceType';

    private const FACTORIES = ['contao.image.factory', 'contao.image.picture_factory'];

    public function process(ContainerBuilder $container): void
    {
        if ($container->hasParameter('avando_ave.ai_label') && !$container->getParameter('avando_ave.ai_label')) {
            return;
        }

        foreach (self::FACTORIES as $id) {
            if (!$container->hasDefinition($id)) {
                continue;
            }

            $definition = $container->getDefinition($id);

            if (!method_exists((string) $definition->getClass(), 'setPreserveMetadataFields')) {
                continue;
            }

            $fields = (new ResizeOptions())->getPreserveCopyrightMetadata();
            $calls = [];

            foreach ($definition->getMethodCalls() as [$method, $arguments]) {
                if ('setPreserveMetadataFields' === $method) {
                    $fields = $arguments[0];
                    continue;
                }

                $calls[] = [$method, $arguments];
            }

            $calls[] = ['setPreserveMetadataFields', [self::withAiField($fields)]];
            $definition->setMethodCalls($calls);
        }
    }

    /**
     * @param array<string, mixed> $fields
     *
     * @return array<string, mixed>
     */
    public static function withAiField(array $fields): array
    {
        $existing = $fields[XmpFormat::NAME][self::NAMESPACE] ?? [];

        if (!\in_array(self::FIELD, $existing, true)) {
            $fields[XmpFormat::NAME][self::NAMESPACE] = [...$existing, self::FIELD];
        }

        return $fields;
    }
}
