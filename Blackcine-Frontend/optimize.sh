#!/bin/bash
# BlackCiné - Script d'optimisation frontend
# Minification CSS/JS, optimisation images, cache

set -e

FRONTEND_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$FRONTEND_DIR"

echo "🚀 Optimisation BlackCiné Frontend"
echo "=================================="

# ==================== MINIFICATION CSS/JS ====================

echo ""
echo "📦 Minification des fichiers..."

# Créer le dossier dist
mkdir -p dist/css dist/js

# Minifier CSS avec terser (ou cssnano via npx)
echo "  → Minification CSS..."
for file in assets/css/*.css; do
    filename=$(basename "$file")
    # Simple minification : supprimer commentaires et espaces
    sed 's/\/\*.*\*\///g' "$file" | \
    sed 's/[[:space:]]*{[[:space:]]*/{/g' | \
    sed 's/[[:space:]]*}[[:space:]]*/}/g' | \
    sed 's/[[:space:]]*:[[:space:]]*/:/g' | \
    sed 's/[[:space:]]*;[[:space:]]*/;/g' | \
    sed 's/[[:space:]]*,[[:space:]]*/,/g' | \
    tr -s ' ' | \
    sed 's/^ //' | \
    sed 's/ $//' > "dist/css/$filename"
    echo "    ✓ $filename"
done

# Minifier JS
echo "  → Minification JS..."
for file in assets/js/*.js; do
    filename=$(basename "$file")
    # Simple minification : supprimer commentaires et espaces inutiles
    sed 's/\/\/.*$//g' "$file" | \
    sed 's/\/\*.*\*\///g' | \
    sed 's/[[:space:]]*{[[:space:]]*/{/g' | \
    sed 's/[[:space:]]*}[[:space:]]*/}/g' | \
    sed 's/[[:space:]]*;[[:space:]]*/;/g' | \
    sed 's/[[:space:]]*,[[:space:]]*/,/g' | \
    tr -s ' ' | \
    sed 's/^ //' | \
    sed 's/ $//' > "dist/js/$filename"
    echo "    ✓ $filename"
done

# ==================== OPTIMISATION IMAGES ====================

echo ""
echo "🖼️  Optimisation des images..."

# Compter les images
IMAGE_COUNT=$(find assets/images -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.webp" \) 2>/dev/null | wc -l)
echo "  → $IMAGE_COUNT images trouvées"

# Vérifier si des outils d'optimisation sont disponibles
if command -v jpegoptim &> /dev/null; then
    echo "  → Compression JPEG..."
    find assets/images -name "*.jpg" -o -name "*.jpeg" | while read img; do
        jpegoptim --strip-all --max=85 "$img" 2>/dev/null || true
    done
fi

if command -v optipng &> /dev/null; then
    echo "  → Compression PNG..."
    find assets/images -name "*.png" | while read img; do
        optipng -o2 "$img" 2>/dev/null || true
    done
fi

if command -v cwebp &> /dev/null; then
    echo "  → Conversion WebP..."
    find assets/images -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" | while read img; do
        cwebp -q 80 "$img" -o "${img%.*}.webp" 2>/dev/null || true
    done
fi

echo "  ✓ Images optimisées"

# ==================== GENERATION CACHE BUSTING ====================

echo ""
echo "🔧 Génération des hash pour cache busting..."

# Générer un manifest CSS
echo "{" > dist/css/manifest.json
first=true
for file in dist/css/*.css; do
    filename=$(basename "$file")
    hash=$(md5sum "$file" 2>/dev/null | cut -d' ' -f1 || md5 -r "$file" 2>/dev/null | cut -d' ' -f1)
    if [ "$first" = true ]; then
        first=false
    else
        echo "," >> dist/css/manifest.json
    fi
    echo "  \"$filename\": \"${filename}?v=${hash}\"" >> dist/css/manifest.json
done
echo "}" >> dist/css/manifest.json

# Générer un manifest JS
echo "{" > dist/js/manifest.json
first=true
for file in dist/js/*.js; do
    filename=$(basename "$file")
    hash=$(md5sum "$file" 2>/dev/null | cut -d' ' -f1 || md5 -r "$file" 2>/dev/null | cut -d' ' -f1)
    if [ "$first" = true ]; then
        first=false
    else
        echo "," >> dist/js/manifest.json
    fi
    echo "  \"$filename\": \"${filename}?v=${hash}\"" >> dist/js/manifest.json
done
echo "}" >> dist/js/manifest.json

echo "  ✓ Manifests générés"

# ==================== RÉSULTATS ====================

echo ""
echo "📊 Résultats :"

# Calculer les tailles
ORIGINAL_CSS=$(du -sh assets/css/ 2>/dev/null | cut -f1)
MINIFIED_CSS=$(du -sh dist/css/ 2>/dev/null | cut -f1)
ORIGINAL_JS=$(du -sh assets/js/ 2>/dev/null | cut -f1)
MINIFIED_JS=$(du -sh dist/js/ 2>/dev/null | cut -f1)

echo "  CSS:    $ORIGINAL_CSS → $MINIFIED_CSS"
echo "  JS:     $ORIGINAL_JS → $MINIFIED_JS"
echo "  Images: $IMAGE_COUNT fichiers"

echo ""
echo "✅ Optimisation terminée !"
echo ""
echo "Pour appliquer les versions minifiées :"
echo "  cp -r dist/css/* assets/css/"
echo "  cp -r dist/js/* assets/js/"
