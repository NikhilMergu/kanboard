configuraci�n de Postgresql 
==========================

Por default, Kanboard usa  Sqlite como almacenamiento de datos pero es posible usar Postgresql.

Requerimientos
------------

- Servidor Postgresql instalado y configurado
- la extensi�n `pdo_pgsql` de PHP instalada (Debian/Ubuntu: `apt-get install php5-pgsql`)

Nota: Kanboard esta testeado con la versi�n **Postgresql 9.3 and 9.4**

Configuraci�n
-------------

### Crear una base de datos vacias con el comando `pgsql`:

```sql
CREATE DATABASE kanboard;
```

### Crear el archivo de configuraci�n

el archivo `config.php` debe contener esos valores:

```php
<?php

// We choose to use Postgresql instead of Sqlite
define('DB_DRIVER', 'postgres');

// Mysql parameters
define('DB_USERNAME', 'REPLACE_ME');
define('DB_PASSWORD', 'REPLACE_ME');
define('DB_HOSTNAME', 'REPLACE_ME');
define('DB_NAME', 'kanboard');
```

Nota: Puedes renombrar el archivo plantilla `config.default.php` a `config.php`.

### Importando el SQL dump (metodo alternativo)

La primera vez, Kanboard ejecutara varias veces la migraci�n de base de datos y este proceso puede tardar alg�n tiempo de acuerdo a su configuraci�n.

Para evitar problemas potenciales o tiempos de espera se puede inicializar la base de datos directamente importando el esquema de SQL:

```bash
psql -U postgres my_database < app/Schema/Sql/postgres.sql
```

El archivo `app/Schema/Sql/postgres.sql` es un dump sql que representa la �ltima versi�n de la base de datos.
