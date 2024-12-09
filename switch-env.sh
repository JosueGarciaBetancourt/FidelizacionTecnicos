#!/bin/bash

# Cambia el archivo .env según el entorno
change_env() {
    local env=$1

    case $env in
        development)
            cp .env.development .env
            ;;
        production)
            cp .env.production .env
            ;;
        *)
            echo "Entorno no válido"
            exit 1
            ;;
    esac

    echo "Archivo .env actualizado para $env"
}

change_env "$1"