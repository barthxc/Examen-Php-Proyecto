<?php
/*  En el archivo bd.php se creará la conexión a la base de datos y todas las funciones necesarias
    De esta forma tengo toda la lógica principal con la base de datos en este archivo y tengo un mejor control en funciones
    También de esta forma para manejar la sesión y persistencia lo único que debo añadir en los demás archivos y páginas solo sería
    la sesión, esto me genera mucha limpieza de código.


    host:localhost
    000webhost db
    dbname = id21634655_proyectoexamen
    nombreusuario = id21634655_root
    password = Solpablowatchvault123!
*/
$host = 'localhost:3308'; // Puede ser 'localhost' si la base de datos está en el mismo servidor || 127.0.0.1:3308
$dbname = 'proyectoexamen';
$username = 'root';
$password = '';

//Variables para controlar
$modal = false;
$modalaudiovisual=false;
$modalaudiovisualmodificar=false;

// Necesito setear los datos seleccionados desde el principio para que el formulario no de error y así poder esa variables en la página vault.php
$tipoFiltro = isset($_POST['tipofiltro']) ? $_POST['tipofiltro'] : null;
$estadofiltro = isset($_POST['estadofiltro']) ? $_POST['estadofiltro'] : null;


//Establezco la cookie con valor pablo si no existe
if (!isset($_COOKIE['estilo'])) {
    setcookie('estilo', 'pablo');
}



//Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<p class="text-green-400">BD Conectada</p>';
} catch (PDOException $e) {
    // echo "Error de conexión: " . $e->getMessage();
    echo '<p class="text-red-400">Error de conexión</p>';
}


//AbrirModal LOGIN
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

//AbrirModalModificar Audiovisual
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['openmodalmodificar'])) {
    $idAudiovisual= $_POST['idAudiovisual'];
    $tipo= $_POST['tipo'];
    $nombre= $_POST['nombre'];
    $descripcion= $_POST['descripcion'];
    $estado= $_POST['estado'];
    $modalaudiovisualmodificar = true;
}







//Registro Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrousuario'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    registrarUsuario($conexion, $nombre, $email, $password);
}

function registrarUsuario($conexion, $nombre, $email, $password)
{
    //Elimino los espacios innecesarios
    $nombre = trim($nombre);
    $email = trim($email);
    $password = trim($password);

    if (empty($nombre) || empty($email) || empty($password)) {
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
}




//Búsqueda de filtro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['busquedafiltro'])) {
    $tipoFiltro = !empty($_POST['tipofiltro']) ? $_POST['tipofiltro'] : null;
    $estadofiltro = !empty($_POST['estadofiltro']) ? $_POST['estadofiltro'] : null;
    busquedaFiltro($conexion, $tipoFiltro, $estadofiltro);
}

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
    exit();  // Asegúrate de salir después de la redirección
}