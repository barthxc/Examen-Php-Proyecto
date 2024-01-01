<?php
include 'layout/header.php';

if (isset($_GET['noregistrado']) && $_GET['noregistrado'] === 'true') {
    $modalaudiovisual = true;
    echo "<p class='text-red-400 font-bold text-center text-base'>Para poner una reseña debes tener una cuenta y estar logeado</p>";
}

if (isset($_GET['mensajevacio']) && $_GET['mensajevacio'] === 'true') {
    $modalaudiovisual = true;
    echo "<p class='text-red-400 font-bold text-center text-base'>Comentario vacío</p>";
}
?>


<h1 class="text-4xl text-gray-400 text-center">Opiniones</h1>

<div class="flex flex-col items-center justify-center">
    <div class="overflow-auto lg:overflow-visible w-2/3">
        <table class="table w-full text-gray-400 border-separate text-sm">
            <tbody>
                <?php
                /*Aquí ejecuto la función de mostrar todos los mensajes*/
                $mensajes = mostrarMensajes($conexion);
                if (empty($mensajes)) {
                    echo "<p class='text-green-400'>Serás el primero en añadir un comentario!</p>";
                } else {
                    foreach ($mensajes as $mensaje) {
                        echo '
                    <tr class="flex flex-col bg-gray-800 mb-2 ' . (($_COOKIE['estilo'] == 'sol') ? 'sol' : '') . '">
                        <td class="p-2 px-4 font-bold underline">' . strtoupper(($mensaje['nombreUsuario'])) . '</td>
                        <td class="px-5 pb-2">' . ($mensaje['mensaje']) . '</td>
                    </tr>
                    ';
                    }
                }
                ?>
                <tr class="flex flex-col bg-gray-800 mb-2 <?= (($_COOKIE['estilo'] == 'sol') ? 'sol' : '') ?>">
                    <td class="p-2 font-bold text-2xl">Añade tu opinión</td>
                    <td class="px-4 pb-2">
                        <form action="" method="POST">
                            <textarea name="mensaje" class="w-full rounded"></textarea>
                            <button type="submit" name="crearcomentario" class="p-2 bg-gray-500 rounded-lg hover:text-white">ENVIAR</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include 'layout/footer.php' ?>