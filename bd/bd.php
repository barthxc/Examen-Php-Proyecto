<?php
/*  En el archivo bd.php se creará la conexión a la base de datos y todas las funciones necesarias
    De esta forma tengo toda la lógica principal con la base de datos en este archivo y tengo un mejor control en funciones
    También de esta forma para manejar la sesión y persistencia lo único que debo añadir en los demás archivos y páginas solo sería
    la sesión, esto me genera mucha limpieza de código.

    INFORMACIÓN DEL SERVIDOR DEL HOST:
    host:localhost
    000webhost db
    dbname = id21634655_proyectoexamen
    nombreusuario = id21634655_root
    password = Solpablowatchvault123!
*/
//Información de mi DSN
$host = 'localhost:3308'; 
$dbname = 'proyectoexamen';
$username = 'root';
$password = '';

/*
Variables en relación a a los modales. Si alguna variable está en true podré mostrar 
los modales que son los formularios de inyección de datos
*/
$modal = false;
$modalaudiovisual=false;
$modalaudiovisualmodificar=false;

/* Necesito setear los datos seleccionados desde el principio para que 
el formulario no de error y así poder esa variables en la página vault.php
Estos datos son los futuros filtros que utilizaré para buscar en el formulario principal las series y las peliculas
*/
$tipoFiltro = isset($_POST['tipofiltro']) ? $_POST['tipofiltro'] : null;
$estadofiltro = isset($_POST['estadofiltro']) ? $_POST['estadofiltro'] : null;


/*
He utilizado una cookie con el nombre 'Pablo' o 'Sol' para poder cambiar los estilos de toda la web
Quiero que por defecto la web tenga los estilos por defecto así que uso por defecto el estilo pablo
Si cambiase los estilos a 'sol' ya hay definido unas clases con el nombre SOL y una serie de reglas para cambiar los estilos.
También tengo un botón que controla el estado de al cookie
*/
if (!isset($_COOKIE['estilo'])) {
    setcookie('estilo', 'pablo');
}



/*Conexión a la base de datos*/
try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<p class="text-green-400">BD Conectada</p>';
} catch (PDOException $e) {
    // echo "Error de conexión: " . $e->getMessage();
    echo '<p class="text-red-400">Error de conexión</p>';
}


/*
Mi modus operandi es:
Primero intento tener todos los 'escuchadores de peticiones POST y empiezo a elegir que hacer con ellos.
Estos son manejadores de peticiones POST para mostrar modales y cerrarlos
*/
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['openloginmodal'])) {
    $modal = true;
}

//CerrarModal LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['closeloginmodal'])) {
    $modal = false;
}

//AbrirModal Audiovisual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['openmodalaudiovisual'])) {
    $modalaudiovisual = true;
}
//CerrarModal Audiovisual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['closemodalaudiovisual'])) {
    header('Location: vault.php?audiovisualvacio=false');
    exit;
}

/*Esta petición POST no alberga ningún tipo de función es solo para mostrar el modar cuando selección el botón modificar
La gran diferencia de mostrar el modal para agregar es que agarro los datos guardados y los pongo en el formulario
De esta forma el usuario sabe bien que está modificando y es mucho mas intuitivo 
*/


//AbrirModalModificar Audiovisual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['openmodalmodificar'])) {
    $idAudiovisual= $_POST['idAudiovisual'];
    $tipo= $_POST['tipo'];
    $nombre= $_POST['nombre'];
    $descripcion= $_POST['descripcion'];
    $estado= $_POST['estado'];
    $modalaudiovisualmodificar = true;
}



/* Aquí sigo el mismo modus operandi 
La única diferencia es que utilizo una estructura de:
Recojo el nombre de la petición POST y sus datos. Más tarde llamo a la función necesaria y escribo la función justo abajo.
A partir de ahora todos el código cuando entra en una función manejará envíos a las páginas correspondientes con una variable que recojo
para mostrar mensajes de error cuando ocurran diversas cosas. He decidido crear validaciones sencillas en todos los formularios.
un ejemplo es:
        header('Location: registro.php?errorformregistro=true');
Muevo al usuario a la página registro con errorformregistro en TRUE cuando hay un error en el formulario


He manejado la sesión de usuario de 2 formas distintas:
    1- La he recogido en un input invisible en front y la intercepto en la respuesta del POST 
    2- También la he usado en los bindparams sin necesidad de recogerla. Usé al principio el primero método siguiendo la lógica.
    Más tarde lo cambié para experimentar y me gusta mucho mas ya que puedo crear funciones con muy pocos parámetros ya que
    el servidor controla el dato de la sesión
*/

//Registro Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrousuario'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    registrarUsuario($conexion, $nombre, $email, $password);
}

function registrarUsuario($conexion, $nombre, $email, $password)
{
    //Elimino los espacios innecesarios por si el usuario crea demasiados
    $nombre = trim($nombre);
    $email = trim($email);
    $password = trim($password);

    if (empty($nombre) || empty($email) || empty($password)) {
        /*si hay un campo vacio enviamos al usuario a la página registro.php 
        con el array asociativo GET lleno con ese dato para valorarlo y mostrar un mensaje*/
        header('Location: registro.php?errorformregistro=true');
        exit;
    } else {
        try {
            $query = "INSERT INTO usuarios (nombre , email , contrasena) VALUES (:nombre , :email , :contrasena)";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':contrasena', $password, PDO::PARAM_STR);
            $statement->execute();
            header('Location: registro.php?successregistro=true');
        } catch (PDOException $e) {
            header('Location: registro.php?errordb=true');
        }
    }
}


//Login Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    loginUsuario($conexion, $email, $password);
}
/*
El login del usuario funciona de la siguiente forma. Para empezar verifico si están vacios. 
Una vez esa condición entre en el else:
    He decidido poner toda la programación en el else.
    He decidido crear la condición normal y no añadir un ! simplente por estructurar bien la función y añadir el "largo de la funcion" al final
    Estoy creando una query que es un select from de los datos exacto que ha puesto el usuario.
    Si existe el usuario con dicha contraseña, crearé la sesión del usuario así que lo que ocurre en el fetch es traerme el dato del usuario y su contraseña

*/

function loginUsuario($conexion, $email, $password)
{
    if (empty($email) || empty($password)) {
        header('Location: ../index.php?errorform=true');
        exit;
    } else {
        try {
            $query = "SELECT * FROM usuarios WHERE email = :email AND contrasena = :contrasena";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':contrasena', $password, PDO::PARAM_STR);
            $statement->execute();
            $usuario = $statement->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Usuario autenticado, establecer la sesión
                session_start();
                $_SESSION['usuario'] = $usuario['nombre'];
                $_SESSION['id'] = $usuario['id'];

                header('Location: ../index.php?success=true');
                exit;
            } else {
                // Credenciales inválidas, mostrar mensaje de error
                header('Location: ../index.php?errorcred=true');
                exit;
            }
        } catch (PDOException $e) {
            header('Location: ../index.php?errordb=true');
            exit;
        }
    }
}




//Logout Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header('Location: ../index.php');
    exit;  
}



//Registro Audiovisual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregaraudiovisual'])) {
    $tipo = $_POST['tipo'];
    $nombre = mb_strtoupper(trim($_POST['nombre']));
    $descripcion = trim($_POST['descripcion']);
    $estado = $_POST['estado'];
    agregarAudiovisual($conexion, $tipo,$nombre,$descripcion,$estado);
}

function agregarAudiovisual($conexion, $tipo,$nombre,$descripcion,$estado){
    if (empty($tipo) || empty($nombre) || empty($descripcion) || empty($estado)) {
        header("Location: vault.php?audiovisualvacio=true");
        exit;
    }

    if (!in_array($tipo, ['pelicula', 'serie']) || !in_array($estado, ['vista', 'viendo', 'pendiente'])) {
        header("Location: vault.php?audiovisualvacio=false");
        exit;
    }else{
        try {
            $query = "INSERT INTO audiovisual (idUsuario, tipo, nombre, descripcion,estado) VALUES (:idUsuario, :tipo , :nombre , :descripcion, :estado)";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':idUsuario', $_SESSION['id'], PDO::PARAM_INT);
            $statement->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $statement->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $statement->bindParam(':estado', $estado, PDO::PARAM_STR);
            $statement->execute();
            header('Location vault.php');
        } catch (PDOException $e) {
            header('Location: vault.php?errordb=true');
            exit;
        }
    }
}


//Mostrar audiovisuales
/* 
Esta función no depende de ninguna petición POST o GET es una función que ejecute cuando vault.php se ejecuta.
De forma que mostrarAudiovisuales es propiamente un dato ya que retorna y puedo usarlo en un foreach.
Cuando ejecuto la función también pregunto si está vacio para poder mostrar un mensaje de que el usuario no tiene audiovisuales y puede agregar mas
*/
function mostrarAudiovisuales($conexion){
        try{
            $query="SELECT * FROM audiovisual WHERE idUsuario = :idUsuario ";
            $statement= $conexion->prepare($query);
            $statement->bindParam(':idUsuario', $_SESSION['id'], PDO::PARAM_INT);
            $statement->execute();
            $audiovisuales = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $audiovisuales;
        }catch(PDOException $e){
            exit;
        }
}

//Eliminar audiovisual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminaraudiovisual'])) {
    $idAudiovisual = $_POST['idAudiovisual'];
    $idUsuario = $_POST['idUsuario'];
    eliminarAudiovisual($conexion, $idAudiovisual, $idUsuario);
}

function eliminarAudiovisual($conexion, $idAudiovisual, $idUsuario){
    try{
        $query = "DELETE FROM audiovisual WHERE id = :idAudiovisual AND idUsuario = :idUsuario";
        $statement= $conexion->prepare($query);
        $statement->bindParam(':idAudiovisual', $idAudiovisual, PDO::PARAM_INT);
        $statement->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $statement->execute();
        header('Location ../pages/vault.php');
    }catch(PDOException $e){
        exit;
    }
}


//Modificar audiovisual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modificaraudiovisual'])) {
        $idAudiovisual= $_POST['idAudiovisual'];
        $idUsuario= $_POST['idUsuario'];
        $tipo= $_POST['tipo'];
        $nombre = mb_strtoupper(trim($_POST['nombre']));
        $descripcion = trim($_POST['descripcion']);
        $estado= $_POST['estado'];
    modificarAudiovisual($conexion, $idAudiovisual,  $tipo, $nombre, $descripcion,$estado);
}

function modificarAudiovisual($conexion, $idAudiovisual,  $tipo, $nombre, $descripcion, $estado)
{
    if (empty($tipo) || empty($nombre) || empty($descripcion) || empty($estado)) {
        header("Location: vault.php?audiovisualvacio=true");
        exit;
    }

    if (!in_array($tipo, ['pelicula', 'serie']) || !in_array($estado, ['vista', 'viendo', 'pendiente'])) {
        header("Location: vault.php?audiovisualvacio=false");
        exit;
    } else {
        try {
            $query = "UPDATE audiovisual SET tipo = :tipo, nombre = :nombre, descripcion = :descripcion, estado = :estado WHERE id = :idAudiovisual AND idUsuario = :idUsuario";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':idUsuario', $_SESSION['id'], PDO::PARAM_INT); 
            $statement->bindParam(':idAudiovisual', $idAudiovisual, PDO::PARAM_INT);
            $statement->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $statement->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $statement->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $statement->bindParam(':estado', $estado, PDO::PARAM_STR);
            $statement->execute();
            header('Location: vault.php'); 
            exit;
        } catch (PDOException $e) {
            header('Location: vault.php?errordb=true');
            exit;
        }
    }
}




// Cambiar estilo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cambiarsestilo'])) {
    $nuevoEstilo = ($_COOKIE['estilo'] == 'pablo') ? 'sol' : 'pablo';
    setcookie('estilo', $nuevoEstilo, time() + 5000);
    header("Location: index.php");
}

//Búsqueda de filtro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['busquedafiltro'])) {
    $tipoFiltro = !empty($_POST['tipofiltro']) ? $_POST['tipofiltro'] : null;
    $estadofiltro = !empty($_POST['estadofiltro']) ? $_POST['estadofiltro'] : null;
    busquedaFiltro($conexion, $tipoFiltro, $estadofiltro);
}
/* Esta es la función mas especial y dificil con diferencia a todas. Estoy MUY contento de encontrar la información sobre esta función
    Esta función tiene de peculiar que maneja 2 FILTROS totalmente distintos de una misma tabla al mismo tiempo

    - Al principio el proyecto era mucho más simple y pequeño por lo tanto usé un truco en SQL el cual escribía:
        SELECT * FROM tabla WHERE 1 . Justo apartir de esta query yo podría agregar muchas mas condiciones añadiendo 
        trozos y segmentos de código a la query que empiezen por un AND ya que WHERE 1 es positivo.
        De esta forma puedo tener varias condiciones y agregarlas o no dependiendo de los datos del filtro y podría tener varios:
            AND table.algo = valor
            AND table.algo = valor
        Hice exactamente el mismo diseño de query pero agregando una condición que debe ser true de forma obligatoria y es:
            que el usuario coincida con le sesión ya que solo el úsuario tiene acceso a SUS DATOS.
        Me he visto obligado a hacerlo de esta forma como buena práctica aunque dentro de mi página vault.php pregunto de nuevo si existe
        una sesión con un nombre. Si existe la sesión podré ejecutar el código que hay dentro que corresponde tanto a mostrar sus audiovisuales
        como poder filtrar.
        En cuyo caso esa condición sea negativa, Redirige automáticamente al Inicio. Haciendo que el usuario registrado no pueda ver contenido alguno del vault.php
*/
function busquedaFiltro($conexion, $tipoFiltro, $estadoFiltro)
{
    try {
        $query = "SELECT * FROM audiovisual WHERE idUsuario = :idUsuario";
        
        if ($tipoFiltro == 'pelicula' || $tipoFiltro == 'serie') {
            $query .= " AND tipo = :tipo";
        }

        if ($estadoFiltro == 'vista' || $estadoFiltro == 'pendiente' || $estadoFiltro == 'viendo') {
            $query .= " AND estado = :estado";
        }

        $statement = $conexion->prepare($query);
        $statement->bindParam(':idUsuario', $_SESSION['id'], PDO::PARAM_INT);

        if ($tipoFiltro == 'pelicula' || $tipoFiltro == 'serie') {
            $statement->bindParam(':tipo', $tipoFiltro, PDO::PARAM_STR);
        }

        if ($estadoFiltro == 'vista' || $estadoFiltro == 'pendiente' || $estadoFiltro == 'viendo') {
            $statement->bindParam(':estado', $estadoFiltro, PDO::PARAM_STR);
        }

        $statement->execute();
        $filtro = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $filtro;
    } catch (PDOException $e) {
        header('Location: index.php?errordb=true');
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resetfiltro'])) {
    $tipoFiltro = null;
    $estadofiltro = null;
    header('Location: vault.php');
    exit();
}

//Añadir comentario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crearcomentario'])) {
    $mensaje= trim($_POST['mensaje']);
    crearComentario($conexion,$mensaje);
}

function crearComentario($conexion,$mensaje){

    if(!isset($_SESSION['id'])){
        header("Location: feed.php?noregistrado=true");
        exit;
    }

    if (empty($mensaje)) {
        header("Location: feed.php?mensajevacio=true");
        exit;
    }else{
        try {
            $query = "INSERT INTO mensajes (idUsuario, mensaje) VALUES (:idUsuario, :mensaje)";
            $statement = $conexion->prepare($query);
            $statement->bindParam(':idUsuario', $_SESSION['id'], PDO::PARAM_INT);
            $statement->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
            $statement->execute();
            header('Location: feed.php');
        } catch (PDOException $e) {
            header('Location: feed.php?errordb=true');
            exit;
        }
    }
}

//Mostrar mensaje
/*Ambas funciones son llamadas en páginas distintas para obtener dicha información. Solo se ejecuta al cargar la págin que recuerda la función
Para mas tarde recorrer el dato con un forEach
*/
function mostrarMensajes($conexion) {
    try {
        $query = "SELECT mensajes.idMensaje, mensajes.mensaje, usuarios.nombre AS nombreUsuario
                  FROM mensajes
                  JOIN usuarios ON mensajes.idUsuario = usuarios.id";
        $statement = $conexion->prepare($query);
        $statement->execute();
        $mensajes = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $mensajes;
    } catch (PDOException $e) {
        exit;
    }
}

//Mostrar usuarios
/*Ambas funciones son llamadas en páginas distintas para obtener dicha información. Solo se ejecuta al cargar la págin que recuerda la función
Para mas tarde recorrer el dato con un forEach
*/
function mostrarUsuarios($conexion){
    try{
        $query ="SELECT * FROM usuarios";
        $statement = $conexion->prepare($query);
        $statement->execute();
        $usuarios = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $usuarios;
    }catch(PDOException $e){
        exit;
    }
}