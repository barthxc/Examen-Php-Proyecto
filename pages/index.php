<?php include '../layout/header.php' ?>

<?php
if (isset($_GET['errorform']) && $_GET['errorform'] === 'true') {
    $modal=true;
    $error=true;
}
?>

<section class="text-center">
    <h1>Proyecto para el examen de <span class="text-purple-600">Desarrollo web Entorno Servidor</span></h1>
    <p class="text-gray-400">Se trata de un proyecto para guardar información de los usuarios de sus peliculas y series para llevar un seguimiendo exausto y poder agregar opiniones de su contenido audiovisual consumido</p>
</section>


<?php include '../layout/footer.php' ?>