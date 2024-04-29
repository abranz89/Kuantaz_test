# Información del Endpoint

Este endpoint proporciona información sobre beneficios ordenados por año, con detalles como el monto total por año, el número de beneficios por año y la ficha asociada a cada beneficio. Los beneficios se filtran para mostrar solo aquellos que cumplen con los montos máximos y mínimos especificados.

## Endpoint

- **URL**: `/information`
- **Método HTTP**: GET

## Parámetros de consulta

- Ninguno

## Respuesta

La respuesta estará en formato JSON y contendrá los siguientes datos:

```json
[
    {
        "year": "2023",
        "num": 8,
        "total": 295608,
        "beneficios": [
            {
                "id_programa": 147,
                "monto": 40656,
                "fecha_recepcion": "09/11/2023",
                "fecha": "2023-11-09",
                "ano": "2023",
                "view": true,
                "ficha": {
                    "id": 922,
                    "nombre": "Emprende",
                    "id_programa": 147,
                    "url": "emprende",
                    "categoria": "trabajo",
                    "descripcion": "Fondos concursables para nuevos negocios"
                }
            },
            // Otros beneficios...
        ]
    },
    // Otros años...
]
```

# Pruebas

Se han realizado pruebas unitarias y de funcionalidad para garantizar el correcto funcionamiento del endpoint.

```bash
php artisan test
