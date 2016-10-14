Background Workers
==================

**Esta caracteristica es experimental**.

Dependiendo de tu configuraci�n, algunas caracteristicas pueden hacer lenta la aplicacion si se esta ejecutando algunos procesos como peticiones HTTP.
Kanboard puede delegar aquella tarea a un background worker que escucha los eventos entrantes.

Ejemplo de caracteristicas que hacen lento a kanboard:

- El env�o de correos electr�nicos a trav�s de un servidor SMTP externo puede tardar varios segundos
- Env�o de notificaciones a servicios externos

Esta caracter�stica es opcional y requiere la instalaci�n de un demonio de cola en el servidor.

### Beanstalk

[Beanstalk](http://kr.github.io/beanstalkd/) es una sencilla cola de trabajo, r�pido.

- Para instalar Beanstalk, puede simplemente usar el gestor de paquetes de su distribuci�n de Linux
- Install el [Kanboard plugin para Beanstalk](https://kanboard.net/plugin/beanstalk)
- Iniciar el trabajador con la herramienta de l�nea de comandos Kanboard: `./kanboard worker`

### RabbitMQ

[RabbitMQ](https://www.rabbitmq.com/) es un sistema de mensajer�a robusta que es m�s adecuado para la infraestructura de alta disponibilidad..

- Sigue la documentaci�n oficial de RabbitMQ para la instalaci�n y la configuraci�n
- Instalar el [Kanboard plugin para RabbitMQ](https://kanboard.net/plugin/rabbitmq)
- Iniciar el trabajador con la herramienta de l�nea de comandos Kanboard: `./kanboard worker`

### Notes

- Debe comenzar el trabajador Kanboard con un supervisor de procesos (systemd, upstart or supervisord)
- El proceso debe ser tiene acceso a la carpeta de datos si almacena los archivos en el sistema de archivos local y tienen SQLite
