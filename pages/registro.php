<?php include '../layout/header.php' ?>

<?php
if (isset($_GET['errorformregistro']) && $_GET['errorformregistro'] === 'true') {
    echo "<p class='text-red-500 font-bold text-center text-base'>Campos vacios</p>";
}

if (isset($_GET['successregistro']) && $_GET['successregistro'] === 'true') {
    echo "<p class='text-green-500 font-bold text-center text-base'>Cuenta creada correctamente!</p>";
}
?>

<div class='flex flex-col mx-10 justify-center bg-gray-800 p-4 rounded-lg shadow-lg text-white'>
            <form action='' method='POST'>
            <div class='flex flex-col justify-center gap-1'>
            <label for='' class='text-gray-300 font-bold'>Nombre</label>
            <input type='text' name='nombre' class='mb-2 bg-gray-400 text-gray-800'>

            <label for='' class='text-gray-300 font-bold'>Email</label>
            <input type="mail" name='email' class='mb-2 bg-gray-400 text-gray-800'>
            
            <label for='' class='text-gray-300 font-bold'>Contraseña</label>
            <input type='password' name='password' class='mb-4 bg-gray-400 text-gray-800'>

            <button type='submit' name='registrousuario' class='bg-gray-600 rounded-lg hover:bg-gray-500 hover:'>Registro</button>
            </div>
            </form>
        </div>

<?php include '../layout/footer.php' ?>