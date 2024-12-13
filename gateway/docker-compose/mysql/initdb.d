CREATE DATABASE IF NOT EXISTS gateway;
CREATE DATABASE IF NOT EXISTS kitchen;
CREATE DATABASE IF NOT EXISTS warehouse;

-- Crear el usuario 'laravel' con su contraseña
CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED BY 'root';

-- Dar permisos al usuario 'laravel' sobre las bases de datos
GRANT ALL PRIVILEGES ON gateway.* TO 'laravel'@'%';
GRANT ALL PRIVILEGES ON kitchen.* TO 'laravel'@'%';
GRANT ALL PRIVILEGES ON warehouse.* TO 'laravel'@'%';

-- Refrescar los permisos
FLUSH PRIVILEGES;
