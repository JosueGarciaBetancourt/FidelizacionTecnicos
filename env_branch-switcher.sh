#!/bin/bash

# Función para mostrar uso del script
usage() {
    echo "Uso: $0 [development|production]"
    echo "Ejemplo: $0 development"
    exit 1
}

# Función para manejar errores
handle_error() {
    echo "❌ Error: $1"
    exit 1
}

# Verificar si hay cambios locales sin confirmar
check_uncommitted_changes() {
    # Verificar cambios en el staging area
    if ! git diff-index --quiet HEAD --; then
        echo "❌ Hay cambios confirmados pero no pusheados"
        git status
        return 1
    fi

    # Verificar cambios no staging
    if [ -n "$(git ls-files --modified)" ]; then
        echo "❌ Hay cambios locales sin confirmar"
        git status
        return 1
    fi

    return 0
}

# Cambiar de rama según el entorno
change_environment() {
    local env=$1
    local branch

    # Determinar la rama según el entorno
    case $env in
        development)
            branch="development"
            ;;
        production)
            branch="main"
            ;;
        *)
            usage
            ;;
    esac

    # Verificar si hay cambios locales
    if ! check_uncommitted_changes; then
        read -p "¿Deseas hacer stash de tus cambios antes de cambiar de rama? (s/n): " stash_choice
        
        if [[ $stash_choice == "s" || $stash_choice == "S" ]]; then
            # Crear un stash con un mensaje descriptivo
            git stash push -m "Cambios antes de switch de rama - $(date)"
            echo "✅ Cambios guardados en stash"
        else
            echo "❌ Operación cancelada. Por favor, confirma o descarta tus cambios manualmente."
            exit 1
        fi
    fi

    # Cambiar a la rama
    git checkout "$branch" || handle_error "No se pudo cambiar a la rama $branch"
    echo "✅ Cambiado exitosamente a la rama $branch"

    # Cambiar el archivo .env
    ./switch-env.sh "$env" || handle_error "No se pudo cambiar el archivo .env"
    echo "✅ Archivo .env actualizado para entorno $env"
}

# Verificar que se proporcione un argumento
if [ $# -eq 0 ]; then
    usage
fi

# Ejecutar el cambio de entorno
change_environment "$1"