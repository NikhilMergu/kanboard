Crear tareas por email
=====================

Tu puedes crear tareas directamente enviando un email.
Esta caracteristica esta disponible usando plugins.


Por el momento, Kanboard est� integrado con 3 servicios externos:

- [Mailgun](https://github.com/kanboard/plugin-mailgun)
- [Sendgrid](https://github.com/kanboard/plugin-sendgrid)
- [Postmark](https://github.com/kanboard/plugin-postmark)

Estos servicios manejan mensajes de correo electr�nico entrantes sin necesidad de configurar cualquier servidor SMTP.

Cuando se recibe un correo electr�nico, Kanboard recibido el mensaje a un punto final espec�fico.
Todos los trabajos complicados ya son manejados por esos servicios.

Workflow de correos electr�nicos entrantes 
------------------------------------------

1. Se env�a un correo electr�nico a una direcci�n espec�fica, por ejemplo **something+myproject@inbound.mydomain.tld**
2. Su correo electr�nico se env�a a los servidores SMTP de terceros
3. Los proveedores SMTP llaman a un web hook del Kanboard con el email en JSON o en formatos multipart/form-data
4. Kanboard analiza el correo electr�nico recibido y creaa la tarea para el proyecto.

Nota: Las tareas nuevas son automaticamente creadas en la primera columna.

Formato de Email
----------------

- La parte local de la direcci�n de correo electr�nico debe utilizar el separador adem�s, por ejemplo **kanboard+project123**
- La cadena definida despu�s del signo m�s [+] debe coincidir con un identificador de proyecto, por ejemplo **project123** si el identificador del proyecto es **Project 123**
- El asunto del correo electr�nico se convierte en el t�tulo de la tarea
- El cuerpo del correo electr�nico se convierte en la descripci�n de la tarea (formato Markdown)

Los correos electr�nicos entrantes se pueden escribir en formatos de texto o HTML.
**Kanboard es capaz de convertir mensajes de correo electr�nico HTML en lenguaje Markdown**.

La seguridad y los requisitos
-------------------------

- El web hook de Kanboard est� protegido por un token aleatorio
- La direcci�n de correo electr�nico del remitente debe coincidir con un usuario Kanboard
- El proyecto Kanboard debe tener un identificador �nico, por ejemplo **MYPROJECT** 
- El usuario Kanboard debe ser un miembro del proyecto

