Kanboard Performances
=====================

De acuerdo con su configuraci�n, algunas caracter�sticas pueden alentar el uso de Kanboard.
Por defecto, todas las operaciones son s�ncronas y en algunos thread realizan peticiones HTTP
Esta es una limitaci�n PHP.
Sin embargo, es posible mejorar eso.

Dependiendo de los plugins instalados, la comunicaci�n a los servicios externos puede llevar cientos de milisegundos o incluso segundos.
Para evitar el bloqueo del thread principal, es posible delegar estas operaciones para un grupo de trabajadores [fondo] (worker.markdown).
Esta configuraci�n requiere una instalaci�n de software adicional en su infraestructura.


C�mo detectar un cuello de botella?
-----------------------------

- Activar el modo de depuraci�n
- Supervisar el archivo de registro
- Hacer algo en Kanboard (arrastrar y soltar una tarea por ejemplo)
- Todas las operaciones se registran con el tiempo de ejecuci�n (solicitudes HTTP, las notificaciones de correo electr�nico, solicitudes SQL)



Mejorar la velocidad de notificaciones por correo electr�nico
---------------------------------

Utilizando el m�todo de SMTP con un servidor externo puede ser muy lento.

Soluciones posibles:

- Utilizar los trabajadores de fondo si usted a�n desea utilizar SMTP
- Utilice un rel� de correo electr�nico local con Postfix y utilizar el transporte "correo"
- Utiliza un proveedor de correo electr�nico que utilizan una API HTTP para enviar mensajes de correo electr�nico (SendGrid, Mailgun o Postmark)


Mejorar el rendimiento Sqlite
---------------------------

Soluciones posibles:

- No utilizar SQLite cuando se tiene una gran cantidad de concurrencia (varios usuarios), seleccione Postgres o MySQL en vez
- No utilizar SQLite en un montaje NFS compartido
- No utilizar SQLite en un disco con IOPS pobres, siempre es preferible utilizar unidades SSD locales
