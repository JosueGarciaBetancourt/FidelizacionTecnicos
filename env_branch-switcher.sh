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
    # Verificar cambios en staging y no confirmados
    if [[ -n "$(git status -s)" ]]; then
        return 1
    fi

    return 0
}

# Solicitar mensaje de commit
prompt_commit_message() {
    local changes
    changes=$(git status -s)
    
    echo "Cambios detectados:"
    echo "$changes"
    
    read -p "Introduce un mensaje de commit: " commit_message
    
    if [[ -z "$commit_message" ]]; then
        echo "❌ El mensaje de commit no puede estar vacío"
        return 1
    fi
    
    return 0
}

# Cambiar de rama y archivo .env según el entorno
change_environment() {
    local env=$1
    local branch
    local env_file

    # Determinar la rama y archivo .env según el entorno
    case $env in
        development)
            branch="development"
            env_file=".env.development"
            ;;
        production)
            branch="main"
            env_file=".env.production"
            ;;
        *)
            usage
            ;;
    esac

    # Verificar si hay cambios locales
    if ! check_uncommitted_changes; then
        # Mostrar cambios y preguntar por commit
        if prompt_commit_message; then
            # Agregar todos los cambios
            git add .
            
            # Hacer commit con el mensaje proporcionado
            git commit -m "$commit_message"
            
            echo "✅ Cambios confirmados exitosamente"
        else
            read -p "¿Deseas hacer stash de tus cambios antes de cambiar de rama? (s/n): " stash_choice
            
            if [[ $stash_choice == "s" || $stash_choice == "S" ]]; then
                # Crear un stash con un mensaje descriptivo
                git stash push -m "Cambios antes de switch de rama - $(date)"
                echo "✅ Cambios guardados en stash"
            else
                echo "❌ Operación cancelada. Permaneciendo en la rama actual."
                return 1
            fi
        fi
    fi

    # Cambiar a la rama
    git checkout "$branch" || handle_error "No se pudo cambiar a la rama $branch"
    echo "✅ Cambiado exitosamente a la rama $branch"

    # Verificar existencia del archivo .env
    if [[ ! -f "$env_file" ]]; then
        handle_error "No se encuentra el archivo $env_file"
    fi

    # Copiar archivo .env
    cp "$env_file" .env || handle_error "No se pudo copiar $env_file a .env"
    echo "✅ Archivo .env actualizado para entorno $env"
}

# Verificar que se proporcione un argumento
if [ $# -eq 0 ]; then
    usage
fi

# Ejecutar el cambio de entorno
change_environment "$1"