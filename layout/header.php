<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WatchVault</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
    <!-- Tengo que añadir los estilos de forma internar porque por algún motivo si ejecuto 
    alguna página donde tengo incluido mis layouts da un error de estilos.
    Así que me veo obligado a introducir los diseños en la etiqueta directamente en el header -->
    <style>
        body {
            background-color: #111827;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0 2rem 0 2rem;
        }

        .table {
            border-spacing: 0 15px;
        }

        i {
            font-size: 1rem !important;
        }

        .table tr {
            border-radius: 20px;
        }

        h1 {
            color: #e1dfd1;
            font-size: 50px;
        }

        tr td:nth-child(n + 5),
        tr th:nth-child(n + 5) {
            border-radius: 0 0.625rem 0.625rem 0;
        }

        tr td:nth-child(1),
        tr th:nth-child(1) {
            border-radius: 0.625rem 0 0 0.625rem;
        }

        /**HOVER DE SVG's**/
        .crear {
            background-color: #1f2937;
        }

        .crear svg {
            transition: fill 0.3s ease;
            /* Agrega una transición suave al cambio de color */
        }

        /* Cambia el color del path al hacer hover sobre el SVG */
        .crear svg:hover path {
            fill: #16a34a;
            /* Cambia este valor al color que desees */
        }
    </style>
</head>

<body>
    <?php
    include '../bd/bd.php';
    session_start();

    ?>


    <div class="flex flex-col mb-10 justify-evenly text-gray-400 items-center sm:flex-row">
        <div class="flex flex-row gap-10 py-10 justify-center font-bold text-4xl">
            <a href="../pages/index.php" class="hover:text-gray-300">Inicio</a>
            <a href="../pages/vault.php" class="hover:text-gray-300 <?php echo isset($_SESSION['usuario']) ? 'text-green-400 hover:text-green-200' : ''; ?>">Vault</a>
            <a href="../pages/doc.php" class="hover:text-gray-300">Doc</a>
        </div>

        <div class="flex flex-col gap-2 text-base">
            <p class="font-bold <?php echo isset($_SESSION['usuario']) ? 'text-green-400' : ''; ?>">
                <?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Invitado'; ?>
            </p>
            <form action="" method="POST">
                <?php
                if (isset($_SESSION['usuario'])) {
                    echo "<button class='hover:text-gray-300' name='logout'>Logout</button>";
                } else {
                    echo "<button class='hover:text-gray-300' name='openloginmodal'>Login</button>";
                }
                ?>

            </form>
        </div>
    </div>
    <?php
    //MENSAJES PARA EL USUARIO REFERENTES A LA VALIDACIÓN DE DATOS
    if (isset($_GET['errorform']) && $_GET['errorform'] === 'true') {
        $modal = true;
        echo "<p class='text-red-500 font-bold text-center text-base'>Campos vacios</p>";
    }

    if (isset($_GET['errorcred']) && $_GET['errorcred'] === 'true') {
        $modal = true;
        echo "<p class='text-red-500 font-bold text-center text-base'>Credenciales incorrectas</p>";
    }

    if (isset($_GET['errordb']) && $_GET['errordb'] === 'true') {
        $modal = true;
        echo "<p class='text-red-500 font-bold text-center text-base'>Error insperado, intentalo más tarde</p>";
    }

    if (isset($_GET['success']) && $_GET['success'] === 'true') {
        echo "<p class='text-green-500 font-bold text-center text-base'>Sesión Iniciada</p>";
    }


    ?>

    <?php
    if ($modal) {
        echo "
        <div class='absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/4 bg-gray-800 p-4 rounded-lg shadow-lg text-white'>
        <div class='flex flex-row justify-between'>
                <p class='text-gray-50'>Login</p>
                <div>
                    <form action='' method='POST'>
                        <button type='submit' name='closeloginmodal'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 256 256'>
                                <path fill='#9ca3af' d='M128 24a104 104 0 1 0 104 104A104.11 104.11 0 0 0 128 24Zm37.66 130.34a8 8 0 0 1-11.32 11.32L128 139.31l-26.34 26.35a8 8 0 0 1-11.32-11.32L116.69 128l-26.35-26.34a8 8 0 0 1 11.32-11.32L128 116.69l26.34-26.35a8 8 0 0 1 11.32 11.32L139.31 128Z' />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            ";

        echo "
            <form action='index.php' method='POST'>
            <div class='flex flex-col justify-center gap-1'>
            <label for='' class='text-gray-300 font-bold'>Email</label>
            <input type='mail' name='email' class='mb-2 bg-gray-400 text-gray-800'>
            
            <label for='' class='text-gray-300 font-bold'>Contraseña</label>
            <input type='password' name='password' class='mb-4 bg-gray-400 text-gray-800'>
            
            <button type='submit' name='login' class='bg-gray-600 rounded-lg hover:bg-gray-500 hover:'>Iniciar</button>
            <p class='text-center'>¿No tienes una cuenta?</p>
            
            </div>
            </form>
            <p class='text-center'>Crear una <a href='../pages/registro.php' class='text-gray-400 hover:text-gray-800'>aquí!</a></p>
        </div>
    ";
    }
    ?>