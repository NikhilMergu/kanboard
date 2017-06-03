Guia de sintaxis
============

Kanboard usa la [ sintaxis Markdown](http://en.wikipedia.org/wiki/Markdown) para comentarios y descripci�n de tareas.
Aqu� hay unos ejemplos:

Bold y italic
----------------

- Texto en Bold : Usar 2 asteriscos o 2 subrayados
- Texto en Italic : Usar 1 asterico o 1 subrayado

### Cod�go
```
Esta **palabra** es muy __importante__.

Y aqui, una palabra *italica* con un _subrayado_.
```

### Resultado

Esta **palabra** es muy __importante__.

Y aqui, una palabra *italica* con un _subrayado_.

Las listas no ordenadas
---------------

Lista desordenada puede utilizar asteriscos, desventajas o ventajas.

### Cod�go
```
- Item 1
- Item 2
- Item 3

o

* Item 1
* Item 2
* Item 3
```

### Resultado

- Item 1
- Item 2
- Item 3

Listas ordenadas
-------------

Las listas ordenadas tienen el prefijo de un numero:

### Cod�go

```
1. Primero hacer esto
2. Y despues esto
3. Y esto
```

### Resultado

1. Primero hacer esto
2. Y despues Hacer esto
3. Y esto

Links
-----

### Cod�go

```
[Mi t�tulo del link](https://kanboard.net/)

<https://kanboard.net>

```

### Resultado

[Mi t�tulo del link](https://kanboard.net/)

<https://kanboard.net>

Cod�go fuente
--------------

### Inline code

Usar un backtick.

```
Ejecutar este comando: `tail -f /var/log/messages`.
```

### Resultado

Ejecutar este comando: `tail -f /var/log/messages`.

### Bloque de cod�gos

Usar 3 backticks con el tiempo el nombre del lenguaje.

<pre>
<code class="language-markdown">```php
&lt;?php

phpinfo();

?&gt;
```
</code>
</pre>

### Resultado

```
<?php

phpinfo();

?>
```

Titulos
------

### codigo

```
# Titulo nivel 1

## Titulo nivel 2

### Titulo nivel 3
```
