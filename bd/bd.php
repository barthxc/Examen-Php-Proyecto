<?php
/*  En el archivo bd.php se creará la conexión a la base de datos y todas las funciones necesarias
    De esta forma tengo toda la lógica principal con la base de datos en este archivo y tengo un mejor control en funciones
    También de esta forma para manejar la sesión y persistencia lo único que debo añadir en los demás archivos y páginas solo sería
    la sesión, esto me genera mucha limpieza de código.
*/
$host = 'localhost:3308'; // Puede ser 'localhost' si la base de datos está en el mismo servidor || 127.0.0.1:3308
$dbname = 'proyectoexamen';
$username = 'root';
$password = '';

//Variables para controlar
$modal = false;

//Conexión a la base de datos
try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<p class="text-green-400">BD Conectada</p>';
} catch (PDOException $e) {
    // echo "Error de conexión: " . $e->getMessage();
    echo '<p class="text-red-400">Error de conexión</p>';
}


//AbrirModal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['openloginmodal'])) {
    $modal = true;
}

//CerrarModal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['closeloginmodal'])) {
    $modal = false;
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
        header('Location: ../pages/registro.php?errorformregistro=true');
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
        header('Location: ../pages/index.php?errorform=true');
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

                header('Location: ../pages/index.php?success=true');
                exit;
            } else {
                // Credenciales inválidas, mostrar mensaje de error
                header('Location: ../pages/index.php?errorcred=true');
                exit;
            }
        } catch (PDOException $e) {
            header('Location: ../pages/index.php?errordb=true');
            exit;
        }
    }
}




//Logout Usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_start();
    session_destroy();

    header('Location: ../pages/index.php');
    exit;  
}

