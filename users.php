<?php include 'layout/header.php' ?>
<h1 class="text-6xl text-gray-400 text-center mb-5 underline">Usuarios</h1>

<div class="flex flex-col items-center justify-center">
    <?php
    $usuarios = mostrarUsuarios($conexion);
    if (empty($usuarios)){
        echo "<p class='text-green-400'>Create una cuenta para ser nuestro primer usuario!</p>";
    }else{
        foreach($usuarios as $usuario){
            echo'
            <p class="text-gray-400 text-3xl">'. ($usuario['nombre']) .'</p>
            ';
        }
    }
    ?>
</div>
