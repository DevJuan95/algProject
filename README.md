# AlgBackend
## Descripción
Esta es la propuesta de documentación para el backend del reto técnico de Alegra. En mi solución basada en microservicios, implementé tres imágenes de Docker que se detallarán a continuación.

Cada microservicio incluye pruebas unitarias y de integración usando el framework PEST, junto con una base de datos SQLite en memoria para maximizar la velocidad de las pruebas. Los microservicios exponen sus recursos internamente bajo los prefijos **/v1/***, mientras que el Gateway utiliza un prefijo basado en el nombre del microservicio, por ejemplo: ***/kitchen/orders*** o **/*warehouse/ingredients**.*

### Microservicio “Gateway”

Es una aplicación que funciona como un proxy. Se llama "Gateway" solamente para ilustrar el concepto, pero en realidad es un punto de entrada a la red interna de Docker llamada "alegra-network". Este microservicio verifica tokens JWT y redirecciona las peticiones según la URL hacia los otros dos microservicios: "warehouse" y "kitchen".

Como posibilidades de mejora para la aplicación, se puede considerar el uso de un Gateway más robusto que implemente control de throttling y funcionalidades de autodescubrimiento, entre otras. También se puede hacer una implementación más robusta de autenticación y tokens CSRF mediante proveedores de autenticación.

### Microservicio “Kitchen”

Este microservicio se encarga de tomar una cantidad de pedidos, crearlos en la base de datos y realizar la solicitud al microservicio "Warehouse" para crear una orden de entrega de los ingredientes necesarios para la receta.

Como posibilidad de mejora, se podría implementar un sistema de colas para dar una respuesta más rápida al usuario al crear la orden. Actualmente, se limita a 5 órdenes por petición debido a la naturaleza del problema (considerando que una mesa típicamente tiene hasta 5 sillas). Sin embargo, si se requiriera procesar más órdenes, se podría implementar un sistema asíncrono para la creación de recursos.

### Microservicio “Warehouse”

Este microservicio se encarga de recibir solicitudes de ingredientes, procesarlas de forma asíncrona mediante una cola y retornar la entrega al microservicio Kitchen.

Se podría mejorar la implementación de la clase PurchasesProcessor para desacoplar más las responsabilidades. Por ejemplo, abstraer la tarea de enviar los ingredientes a la cocina mediante Event listeners (síncronos o asíncronos) mejoraría el conjunto de pruebas automáticas y haría el código más reutilizable. También se podría mejorar el manejo de errores en las colas asíncronas para emitir eventos de cancelación o, en este caso de comunicación por REST, hacer solicitudes de cancelación al microservicio de cocina.

## Instalación
Para instalar y ejecutar la aplicación, se requiere tener Docker y Docker Compose instalados en el sistema. A continuación, se detallan los pasos para instalar y ejecutar la aplicación.

1. Clonar el repositorio de GitHub:
```bash
git clone
```
2. Copiar los archivos de configuración de ejemplo de cada microservicio:
```bash
cp .env.example .env
```
3. Ejecutar el comando de Docker Compose para construir las imágenes y levantar los contenedores:
```bash
docker-compose up -d
```
4. Ejecutar las migraciones de la base de datos en cada microservicio:
```bash
docker-compse exec gateway php artisan migrate --seed
docker-compose exec kitchen php artisan migrate --seed
docker-compose exec warehouse php artisan migrate --seed
```

5. Ejecutar el worker de colas en el microservicio "Warehouse":
```bash
docker-compose exec warehouse php artisan queue:work -v
```

## Pruebas
Cada prueba se ejecuta con una copia en memoria de SQLite. Se pueden ejecutar las pruebas sin necesidad de docker.
Para ejecutar las pruebas unitarias y de integración, se debe ejecutar el siguiente comando dentro de cada subdirectorio de los microservicios:
```bash
php artisan test 
php artisan test --coverage
```