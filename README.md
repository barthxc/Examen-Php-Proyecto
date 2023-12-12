# Proyecto Examen - **Desarrollo web entorno servidor**

La aplicación que he pensado se trata de una web la cual la persona puede registrase.
Cuando el usuario se ha podido registrado puede acceder a un servicio para almacenar información sobre sus peliculas y series,
almacenandolo en una tabla con filtros pudiendo tener un control de todas las peliculas vistas y pendientes.

Si el usuario no ha iniciado sesión solo podrá acceder al inicio de la web donde explica su funcionamiento.

La persistencia del usuario se hará usando sesiones.

El proyecto se utilizará para poder crear una interfaz agradable tailwind usando una importación con un CDN.
Así me comprometo a que la interfaz visual sea clara y de calidad sin perder tiempo en el proyecto.

- Actualmente al entrar en nuestra web podemos observar que tenemos un mensaje previo para saber si la conexión a la base de datos fue exitosa
- Tenemos también un menu de los lugares a los que podemos navegar MENOS Vault que solo tendremos acceso si estamos logueado. También tenemos que tipo de usuario somos : invitado o no

- Tenemmos un botón para hacer logín que llama una función, dicha función cambia una variable para mostrar un modal con un formulario para logearse.
Tenemos mensajes referenciados por la validación en la propia página. También dentro del modal tenemos un link para acceder al registro.

- Se ha creado el registro de usuarios y el login de forma correcta, creando así también la persistencia de datos con la sesiones para acceder a vault.php y también 
mostrando el nombre de la persiona que ha iniciado sesión sustituyendo "invitado" y la función de "login" por "logout"

- Se ha creado una función que muestra todas las peliculas que espera el parámetro de la sesión para mostrar los elementos audiovisuales exclusivo del usuario.

- Se ha implementado correctamente la función de agregar pelicula y su eliminación

- Se ha implementado el modal absoluto que muestra el formulario para modificar la pelicula y los datos correspondientes de cada una


- Falta implementar los filtros relacionados con los elementos audiovisuales y los nombres de los usuarios fidelizados
        - Mas tarde se debe crear el formulario de registro de los datos del vault, las peliculas/series. Crear también filtros si es posible de los datos como:
                - Mostrar solo series
                - Mostrar solo vistas
                - Mostrar solo siguiendo
                - Mostrr solo pendientes

        - También se debe crear la página de users donde mostramos el nombre del usuario para mostrarlos en la web como fieles usuarios
        

Se irá actualizando el readme con todas novedades del proyecto.

## Este proyecto se ha creado el mismo día que ha creado el profesor el comunicado: 05/12/2023







ACTUALIZACIÓN 12/12/2023

Por problemas de las rutas absolutas del comportamiento de la web se ha tenido que sacar la carpeta de páginas para poder hacer el proyecto efectivo para hacer deploy en un servidor real de hosteo.


Se ha añadido el filtro de los audiovisuales y la cookie para cambiar los estilos.

Faltaría crear la página para mostrar usuarios y la web de documentación para explicar el funcionamiento de lamisma y el procedimiento más detallado.
