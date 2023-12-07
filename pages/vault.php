<?php
include '../layout/header.php';

//El usuario debe iniciar sesión para poder acceder a la página vault.php
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
}

if (isset($_GET['audiovisualvacio']) && $_GET['audiovisualvacio'] === 'true') {
    $modalaudiovisual = true;
    echo "<p class='text-red-500 font-bold text-center text-base'>Campos vacios</p>";
}



?>



<div class="flex flex-col items-center justify-center">
    <div class="flex flex-row gap-4 items-center">
        <h1>WatchVault</h1>
        <form action="" method="POST">
            <button type="submit" name="openmodalaudiovisual" class="crear text-base bg-white rounded-full mt-3">
                <input type="hidden" name="accion" value="agregar">

                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                    <path fill="#000000" d="M11 13H5v-2h6V5h2v6h6v2h-6v6h-2v-6Z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="overflow-auto lg:overflow-visible">
        <table class="table text-gray-400 border-separate space-y-6 text-sm">
            <tbody>


                <?php
                $audiovisuales = mostrarAudiovisuales($conexion, $_SESSION['id']);
                if (empty($audiovisuales)) {
                    echo "<p class='text-green-400'>Agrega una pelicula o una serie!</p>";
                } else {
                    foreach ($audiovisuales as $audiovisual) {
                        echo '
                        <tr class="bg-gray-800">
                            <td class="p-3">
                                <div class="flex align-items-center" title="' . ($audiovisual['tipo'] == 'pelicula' ? 'Pelicula' : 'Serie') . '">
                                    <svg
                                        class="rounded-full h-12 w-12 object-cover"
                                        width="30"
                                        height="30"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g
                                            id="SVGRepo_tracerCarrier"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <rect width="24" height="24"></rect>
                                            <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M7.25007 2.38782C8.54878 2.0992 10.1243 2 12 2C13.8757 2 15.4512 2.0992 16.7499 2.38782C18.06 2.67897 19.1488 3.176 19.9864 4.01358C20.824 4.85116 21.321 5.94002 21.6122 7.25007C21.9008 8.54878 22 10.1243 22 12C22 13.8757 21.9008 15.4512 21.6122 16.7499C21.321 18.06 20.824 19.1488 19.9864 19.9864C19.1488 20.824 18.06 21.321 16.7499 21.6122C15.4512 21.9008 13.8757 22 12 22C10.1243 22 8.54878 21.9008 7.25007 21.6122C5.94002 21.321 4.85116 20.824 4.01358 19.9864C3.176 19.1488 2.67897 18.06 2.38782 16.7499C2.0992 15.4512 2 13.8757 2 12C2 10.1243 2.0992 8.54878 2.38782 7.25007C2.67897 5.94002 3.176 4.85116 4.01358 4.01358C4.85116 3.176 5.94002 2.67897 7.25007 2.38782Z"
                                                fill="' . ($audiovisual['tipo'] == 'pelicula' ? '#df0707' : '#ea741a') . '"
                                            ></path>
                                        </g>
                                    </svg>
                                </div>
                            </td>
                            <td class="p-2 font-bold">' . $audiovisual['nombre'] . '</td>
                            <td class="p-2">' . $audiovisual['descripcion'] . '</td>
                            <td class="text-center">
                            <span class="' . ($audiovisual['estado'] == 'vista' ? 'bg-green-400' : ($audiovisual['estado'] == 'pendiente' ? 'bg-red-400' : 'bg-yellow-400')) . ' text-gray-50 rounded-md px-2" title="' . ($audiovisual['estado'] == 'vista' ? 'vista' : ($audiovisual['estado'] == 'pendiente' ? 'pendiente' : 'viendo')) . '"></span>
                            </td>
                            <td class="p-3">
                            <form action="" method="POST">
                                <button type="submit" name="openmodalmodificar" class="text-base mr-2" title="Modificar">
                                <input type="hidden" name="idAudiovisual" value="' . $audiovisual['id'] . '">
                                <input type="hidden" name="idUsuario" value="' . $_SESSION['id'] . '">
                                <input type="hidden" name="tipo" value="' . $audiovisual['tipo'] . '">
                                <input type="hidden" name="nombre" value="' . htmlspecialchars($audiovisual['nombre']) . '">
                                <input type="hidden" name="descripcion" value="' . htmlspecialchars($audiovisual['descripcion']) . '">
                                <input type="hidden" name="estado" value="' . $audiovisual['estado'] . '">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="30"
                                        height="30"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            fill="#9ca3af"
                                            d="M3 21v-4.25L16.2 3.575q.3-.275.663-.425t.762-.15q.4 0 .775.15t.65.45L20.425 5q.3.275.438.65T21 6.4q0 .4-.138.763t-.437.662L7.25 21H3ZM17.6 7.8L19 6.4L17.6 5l-1.4 1.4l1.4 1.4Z"
                                        />
                                    </svg>
                                </button>


                                <button type="submit" name="eliminaraudiovisual" class="text-base mr-2" title="Eliminar">
                                    <input type="hidden" name="idAudiovisual" value="' . $audiovisual['id'] . '">
                                    <input type="hidden" name="idUsuario" value="' . $_SESSION['id'] . '">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="30"
                                        height="30"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            fill="#9ca3af"
                                            d="M7 21q-.825 0-1.413-.588T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.588 1.413T17 21H7ZM17.6 7.8L19 6.4L17.6 5l-1.4 1.4l1.4 1.4Z"
                                        />
                                    </svg>
                                </button>
                                </form>
                            </td>
                        </tr>';
                    }
                }


                ?>
            </tbody>
        </table>
    </div>

</div>




<?php
if ($modalaudiovisual) {
    echo '
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/4 bg-gray-800 p-4 rounded-lg shadow-lg text-white">
            <div class="flex flex-row justify-between">
                <p class="text-green-400">Añadir</p>
                <div>
                    <form action="" method="POST">
                        <button type="submit" name="closemodalaudiovisual">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 256 256">
                                <path fill="#9ca3af" d="M128 24a104 104 0 1 0 104 104A104.11 104.11 0 0 0 128 24Zm37.66 130.34a8 8 0 0 1-11.32 11.32L128 139.31l-26.34 26.35a8 8 0 0 1-11.32-11.32L116.69 128l-26.35-26.34a8 8 0 0 1 11.32-11.32L128 116.69l26.34-26.35a8 8 0 0 1 11.32 11.32L139.31 128Z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <form action="" method="POST">
                <div class="flex flex-col justify-center gap-3">
                    <label for="" class="text-gray-300 font-bold">Tipo:</label>
                    <div class="flex flex-row gap-4">
                        <div class="flex flex-row items-center gap-2">
                            <label for="">Pelicula</label>
                            <input type="radio" name="tipo" value="pelicula" class="bg-gray-400 text-gray-800">
                        </div>
                        <div class="flex flex-row items-center  gap-2">
                            <label for="">Serie</label>
                            <input type="radio" name="tipo" value="serie" class="bg-gray-400 text-gray-800">
                        </div>
                    </div>
                    <div class="flex flex-row gap-2">
                        <label for="" class="text-gray-300 font-bold">Nombre</label>
                        <input type="text" name="nombre" class="bg-gray-400 text-gray-800">
                    </div>
                    <div class="flex flex-row gap-2">
                        <label for="" class="text-gray-300 font-bold">Descripción</label>
                        <textarea name="descripcion" id="" cols="25" rows="4"  class="bg-gray-400 text-gray-800"></textarea>
                    </div>
                    <div class="flex flex-row gap-2">
                        <label for="" class="text-gray-300 font-bold">Estado:</label>
                        <select name="estado" id="" class="bg-gray-400">
                            <option value="" disabled selected>-Estado-</option>
                            <option value="vista">Vista</option>
                            <option value="viendo">Viendo</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>
                    <button type="submit" name="agregaraudiovisual" class="bg-gray-600 rounded-lg hover:bg-gray-500 hover:">Agregar</button>
                </div>
            </form>
        </div>';
} else if ($modalaudiovisualmodificar) {
    echo '
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-3/4 md:w-1/2 lg:w-1/3 xl:w-1/4 bg-gray-800 p-4 rounded-lg shadow-lg text-white">
        <div class="flex flex-row justify-between">
            <p class="text-green-400">Modificar</p>
            <div>
                <form action="" method="POST">
                    <button type="submit" name="closemodalaudiovisual">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 256 256">
                            <path fill="#9ca3af" d="M128 24a104 104 0 1 0 104 104A104.11 104.11 0 0 0 128 24Zm37.66 130.34a8 8 0 0 1-11.32 11.32L128 139.31l-26.34 26.35a8 8 0 0 1-11.32-11.32L116.69 128l-26.35-26.34a8 8 0 0 1 11.32-11.32L128 116.69l26.34-26.35a8 8 0 0 1 11.32 11.32L139.31 128Z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        <form action="" method="POST">
            <div class="flex flex-col justify-center gap-3">
                <label for="" class="text-gray-300 font-bold">Tipo:</label>
                <div class="flex flex-row gap-4">
                    <div class="flex flex-row items-center gap-2">
                        <label for="">Pelicula</label>
                        <input type="radio" name="tipo" value="pelicula" ' . ($tipo  === 'pelicula' ? 'checked' : '') . ' class="bg-gray-400 text-gray-800">
                    </div>
                    <div class="flex flex-row items-center  gap-2">
                        <label for="">Serie</label>
                        <input type="radio" name="tipo" value="serie" ' . ($tipo  === 'serie' ? 'checked' : '') . ' class="bg-gray-400 text-gray-800">
                    </div>
                </div>
                <div class="flex flex-row gap-2">
                    <label for="" class="text-gray-300 font-bold">Nombre</label>
                    <input type="text" name="nombre" value="' . $nombre . '" class="bg-gray-400 text-gray-800">
                </div>
                <div class="flex flex-row gap-2">
                    <label for="" class="text-gray-300 font-bold">Descripción</label>
                    <textarea name="descripcion" id=""  cols="25" rows="4" class="bg-gray-400 text-gray-800">' . $descripcion . '</textarea>
                </div>
                <div class="flex flex-row gap-2">
                    <label for="" class="text-gray-300 font-bold">Estado:</label>
                    <select name="estado" id="" class="bg-gray-400">
                        <option value="" disabled selected>-Estado-</option>
                        <option value="vista" ' . ($estado === 'vista' ? 'selected' : '') . '>Vista</option>
                        <option value="viendo" ' . ($estado === 'viendo' ? 'selected' : '') . '>Viendo</option>
                        <option value="pendiente" ' . ($estado === 'pendiente' ? 'selected' : '') . '>Pendiente</option>
                    </select>
                </div>
                <button type="submit" name="modificaraudiovisual" class="bg-gray-600 rounded-lg hover:bg-gray-500 hover:">Modificar</button>
                <input type="hidden" name="idAudiovisual" value="' . $idAudiovisual . '">

            </div>
        </form>
    </div>';
}
?>






<?php include '../layout/footer.php' ?>