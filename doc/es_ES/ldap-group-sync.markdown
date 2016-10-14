LDAP Sincronizaci�n de grupos
=============================

Requerimientos
------------

- Disponer de autenticaci�n LDAP configurado correctamente
- Usar un servidor LDAP que soporte `memberOf` o `memberUid` (PosixGroups)

Definir automaticamente los rolos de los usuarios basados en los grupos LDAP
----------------------------------------------------------------------------

Utilizar estas constantes en su archivo de configuraci�n:

- `LDAP_GROUP_ADMIN_DN`: Los nombres distintivos para los administradores de aplicaciones
- `LDAP_GROUP_MANAGER_DN`:Los nombres distintivos para los manejadores de aplicaciones

### Ejemplo para un Active Directory:

```php
define('LDAP_GROUP_ADMIN_DN', 'CN=Kanboard Admins,CN=Users,DC=kanboard,DC=local');
define('LDAP_GROUP_MANAGER_DN', 'CN=Kanboard Managers,CN=Users,DC=kanboard,DC=local');
```

- Miembro de popular "Kanboard Admins" tendra el rol "Administrator"
- Miembro de Popular"Kanboard Managers" tendra el rol "Managers"
- Cualquier miembro tendr� el papel "Usuario"

### Ejemplo OpenLDAP con Posix Groups:

```php
define('LDAP_GROUP_BASE_DN', 'ou=Groups,dc=kanboard,dc=local');
define('LDAP_GROUP_USER_FILTER', '(&(objectClass=posixGroup)(memberUid=%s))');
define('LDAP_GROUP_ADMIN_DN', 'cn=Kanboard Admins,ou=Groups,dc=kanboard,dc=local');
define('LDAP_GROUP_MANAGER_DN', 'cn=Kanboard Managers,ou=Groups,dc=kanboard,dc=local');
```

Solo debes **definir los parametros** `LDAP_GROUP_USER_FILTER` si tu servidor LDAP usa `memberUid` instanciada de `memberOf`.
Todos los parametros de este ejemplo son obligatorios.

Automaticamente cargar los grupos LDAP para los permisos de los proyectos
--------------------------------------------------------------------------

Esta caracter�stica le permite sincronizar autom�ticamente grupos LDAP con grupos Kanboard.
Cada grupo puede tener un papel de un proyecto asignado diferente .

En la p�gina permisos de proyecto, la gente puede entrar en grupos en el campo de autocompletar y Kanboard pueden buscar grupos con cualquier proveedor habilitado.

Si el grupo no existe en la base de datos local, que se sincronizar� autom�ticamente.

- `LDAP_GROUP_PROVIDER`: Habilitar el grupo de proveedores LDAP
- `LDAP_GROUP_BASE_DN`: Los nombres distintivos para buscar los grupos LDAP en el directorio
- `LDAP_GROUP_FILTER`: filtros LDAP usados para realizar consultas
- `LDAP_GROUP_ATTRIBUTE_NAME`: LDAP atributos usados para obtener los nombres de grupos


### Ejemplo para Active Directory:

```php
define('LDAP_GROUP_PROVIDER', true);
define('LDAP_GROUP_BASE_DN', 'CN=Groups,DC=kanboard,DC=local');
define('LDAP_GROUP_FILTER', '(&(objectClass=group)(sAMAccountName=%s*))');
```

Con el filtro dado como ejemplo anterior, Kanboard buscar� los grupos que responden a la consulta.
Si el usuario final introduzca el texto "my group" en la caja de autocompletar, Kanboard devolver� todos los grupos que coinciden con el patr�n: `(&(objectClass=group)(sAMAccountName=My group*))`.

- Nota 1: El caracter especial `*` es importante aqui , **de lo contrario se har� una coincidencia exacta**.
- Nota 2: Esta caracteristica es solamente compatible con la autenticaci�n LDAP authentication configurada en el  "proxy" o en modo "anonymous"

[Mas ejempos de filtros LDAP para Active Directory](http://social.technet.microsoft.com/wiki/contents/articles/5392.active-directory-ldap-syntax-filters.aspx)

### Ejemplo para OpenLDAP con Posix Groups:

```php
define('LDAP_GROUP_PROVIDER', true);
define('LDAP_GROUP_BASE_DN', 'ou=Groups,dc=kanboard,dc=local');
define('LDAP_GROUP_FILTER', '(&(objectClass=posixGroup)(cn=%s*))');
```
