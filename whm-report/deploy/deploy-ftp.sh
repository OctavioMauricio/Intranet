#!/bin/bash
# ============================================================
#  deploy-ftp.sh — Deploy de módulo/app via curl FTP
#  Uso: ./deploy-ftp.sh <ruta_local_modulo> [ruta_remota]
#
#  Ejemplos:
#    ./deploy-ftp.sh ../whm-report
#    ./deploy-ftp.sh ../kickoff_icontel kickoff_icontel
# ============================================================

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
ENV_FILE="$SCRIPT_DIR/.env"

# Cargar credenciales
if [ ! -f "$ENV_FILE" ]; then
  echo "❌ No se encontró $ENV_FILE"
  exit 1
fi
source "$ENV_FILE"

# Argumentos
LOCAL_PATH="${1}"
REMOTE_SUB="${2:-$(basename "$LOCAL_PATH")}"

if [ -z "$LOCAL_PATH" ]; then
  echo "Uso: $0 <ruta_local_modulo> [ruta_remota_subdir]"
  echo ""
  echo "Ejemplos:"
  echo "  $0 ../whm-report"
  echo "  $0 ../kickoff_icontel kickoff_icontel"
  exit 1
fi

if [ ! -e "$LOCAL_PATH" ]; then
  echo "❌ La ruta local no existe: $LOCAL_PATH"
  exit 1
fi

ERRORS=0
FILES=0

# --- Función para subir un archivo ---
upload_file() {
  local file="$1"
  local rel="$2"
  local remote_dir="ftp://${FTP_HOST}${FTP_REMOTE_BASE}/${REMOTE_SUB}/$(dirname "$rel")"
  # Si dirname es "." subir directo a la base del subdirectorio remoto
  if [ "$(dirname "$rel")" == "." ]; then
    remote_dir="ftp://${FTP_HOST}${FTP_REMOTE_BASE}/${REMOTE_SUB}"
  fi

  curl -s --ftp-create-dirs \
       --user "${FTP_USER}:${FTP_PASS}" \
       -T "$file" \
       "${remote_dir}/" \
       --retry 2 \
       --retry-delay 1

  if [ $? -eq 0 ]; then
    echo "  ✅ $rel"
    ((FILES++))
  else
    echo "  ❌ ERROR: $rel"
    ((ERRORS++))
  fi
}

# --- Archivo único o Subdirectorio específico ---
if [ -f "$LOCAL_PATH" ]; then
  LOCAL_ABS="$(cd "$(dirname "$LOCAL_PATH")" && pwd)/$(basename "$LOCAL_PATH")"
  FILENAME="$(basename "$LOCAL_PATH")"
  # Intentar determinar la ruta relativa si estamos dentro de un proyecto
  REL_PATH="$LOCAL_PATH"
  
  echo "============================================"
  echo "  🚀 Deploy FTP (Single File) — $FILENAME"
  echo "============================================"
  echo "  📁 Local  : $LOCAL_ABS"
  echo "  🌐 Remoto : ftp://${FTP_HOST}${FTP_REMOTE_BASE}/${REMOTE_SUB}"
  echo "  👤 Usuario: $FTP_USER"
  echo "--------------------------------------------"

  upload_file "$LOCAL_ABS" "$FILENAME"

# --- Directorio ---
elif [ -d "$LOCAL_PATH" ]; then
  LOCAL_ABS="$(cd "$LOCAL_PATH" && pwd)"
  REMOTE_BASE_URL="ftp://${FTP_HOST}${FTP_REMOTE_BASE}/${REMOTE_SUB}"

  echo "============================================"
  echo "  🚀 Deploy FTP (Directory) — $(basename "$LOCAL_ABS")"
  echo "============================================"
  echo "  📁 Local  : $LOCAL_ABS"
  echo "  🌐 Remoto : $REMOTE_BASE_URL"
  echo "  👤 Usuario: $FTP_USER"
  echo "--------------------------------------------"

  while IFS= read -r -d '' file; do
    REL="${file#$LOCAL_ABS/}"
    [[ "$REL" == .git* ]]      && continue
    [[ "$REL" == .DS_Store ]]  && continue
    [[ "$REL" == */_notes/* ]] && continue
    [[ "$REL" == *.log ]]      && continue
    [[ "$REL" == *.tmp ]]      && continue
    # No subir el propio script de deploy ni el .env
    [[ "$REL" == deploy/* ]]   && continue
    
    upload_file "$file" "$REL"
  done < <(find "$LOCAL_ABS" -type f -print0)
fi

echo "--------------------------------------------"
if [ $ERRORS -eq 0 ]; then
  echo "  ✅ Deploy completado: $FILES archivos subidos"
else
  echo "  ⚠️  Deploy con errores: $FILES OK / $ERRORS errores"
fi
echo "============================================"
