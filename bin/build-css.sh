#!/usr/bin/env bash
# Avando AVE — CSS build script
# Concatenates source files and minifies with lightningcss.
# Run from anywhere: ./bundle/bin/build-css.sh
set -e

BUNDLE="$(cd "$(dirname "$0")/.." && pwd)/public"
OPEN_PROPS="$BUNDLE/open-props"
THEME="$BUNDLE/theme"

echo "→ Concatenating open-props..."
cat \
    "$OPEN_PROPS/open-props.min.css" \
    "$OPEN_PROPS/normalize-layer.css" \
    > "$OPEN_PROPS/open-props.css"

echo "→ Concatenating avando-ave..."
cat \
    "$THEME/tokens.css" \
    "$THEME/base.css" \
    "$THEME/layout.css" \
    "$THEME/typography.css" \
    "$THEME/forms.css" \
    "$THEME/components.css" \
    "$THEME/navigation.css" \
    "$THEME/icons.css" \
    "$THEME/animations.css" \
    > "$THEME/avando-ave.css"

echo "→ Minifying..."
npx --yes lightningcss-cli --minify "$OPEN_PROPS/open-props.css" -o "$OPEN_PROPS/open-props.min.css"
npx --yes lightningcss-cli --minify "$THEME/avando-ave.css"      -o "$THEME/avando-ave.min.css"

echo "✓ Done:"
du -sh "$OPEN_PROPS/open-props.min.css" "$THEME/avando-ave.min.css"
