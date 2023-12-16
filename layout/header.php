<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WatchVault</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/layout/styles.css" />
    <!-- Tengo que añadir los estilos de forma internar porque por algún motivo si ejecuto 
    alguna página donde tengo incluido mis layouts da un error de estilos.
    Así que me veo obligado a introducir los diseños en la etiqueta directamente en el header -->
</head>

<body class="<?= ($_COOKIE['estilo'] == 'sol') ? 'sol' : '' ?>">
    <?php
    session_start();
    include 'bd/bd.php';
    ?>
    <div class="flex flex-row justify-end items-center mt-5 mr-10">
        <div>
            <form action="" method="POST">
                <button type="submit" name="cambiarsestilo" title="<?= ($_COOKIE['estilo'] == 'sol') ? 'Modo Pablo' : 'Modo Sol' ?>">
                    <?= ($_COOKIE['estilo'] == 'sol') ? '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 256 256"><path fill="#1f2937" d="M240 96a8 8 0 0 1-8 8h-16v16a8 8 0 0 1-16 0v-16h-16a8 8 0 0 1 0-16h16V72a8 8 0 0 1 16 0v16h16a8 8 0 0 1 8 8Zm-96-40h8v8a8 8 0 0 0 16 0v-8h8a8 8 0 0 0 0-16h-8v-8a8 8 0 0 0-16 0v8h-8a8 8 0 0 0 0 16Zm65.14 94.33A88.07 88.07 0 0 1 105.67 46.86a8 8 0 0 0-10.6-9.06A96 96 0 1 0 218.2 160.93a8 8 0 0 0-9.06-10.6Z"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="#561a45" d="M11 5V1h2v4h-2Zm6.65 2.75l-1.375-1.375l2.8-2.875l1.4 1.425L17.65 7.75ZM19 13v-2h4v2h-4Zm-8 10v-4h2v4h-2ZM6.35 7.7L3.5 4.925l1.425-1.4L7.75 6.35L6.35 7.7Zm12.7 12.8l-2.775-2.875l1.35-1.35l2.85 2.75L19.05 20.5ZM1 13v-2h4v2H1Zm3.925 7.5l-1.4-1.425l2.8-2.8l.725.675l.725.7l-2.85 2.85ZM12 18q-2.5 0-4.25-1.75T6 12q0-2.5 1.75-4.25T12 6q2.5 0 4.25 1.75T18 12q0 2.5-1.75 4.25T12 18Z"/></svg>' ?>
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-col mb-5 justify-evenly text-gray-400 items-center sm:flex-row">
        <div class="flex flex-row gap-10 py-10 justify-center font-bold text-4xl">
            <a href="/index.php" class="hover:text-gray-300">Inicio</a>
            <a href="/vault.php" class="hover:text-gray-300 <?php echo isset($_SESSION['usuario']) ? 'text-green-400 hover:text-green-200' : ''; ?>">Vault</a>
            <a href="/feed.php" class="hover:text-gray-300">Feed</a>
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
            <form action='' method='POST'>
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