<?php include '../layout/header.php';

//El usuario debe iniciar sesión para poder acceder a la página vault.php
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
}
?>



<div class="flex flex-col items-center justify-center">
    <div class="flex flex-row gap-4 items-center">
        <h1>WatchVault</h1>
        <button class="crear text-base bg-white rounded-full mt-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                <path fill="#000000" d="M11 13H5v-2h6V5h2v6h6v2h-6v6h-2v-6Z" />
            </svg>
        </button>
    </div>


    
</div>
<?php include '../layout/footer.php' ?>