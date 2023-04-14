<?php
session_start();
include "../../global/conexion.php";
$Fecha_Actual = date("Y-m-d H:i:s");
//Variables
$User = isset($_POST['User']) ? $_POST['User'] : "";
$Password = isset($_POST['Password']) ? $_POST['Password'] : "";
switch ($_GET['op']) {
        //Caso donde se verifica al usuario
    case 'login':
        // Validamos si existe el usuario
        $COUNT = ejecutarConsultaSimpleFila("SELECT COUNT(*) FROM User WHERE Usuario='$User';")[0];
        if ($COUNT > 0) {
            $query = ejecutarConsultaSimpleFila("SELECT * FROM User WHERE Usuario='$User';");
            if (password_verify($Password, $query['Contraseña'])) {
                $_SESSION['Id_Empleado'] = $query['Id_Empleado'];
                $_SESSION['Usuario'] = $query['Usuario'];
                $_SESSION['Permiso'] = $query['Rol'];
                $_SESSION['Id_Usuario'] = $query['Id'];
                $sql = ejecutarConsulta("UPDATE User SET UF_Activo='$Fecha_Actual', Activo='1' WHERE Usuario='$User';");
                echo $sql ? 200 : 203;
            } else {
                echo 202; // Contraseña incorrecta
            }
        } else {
            echo 201; // Usuario no existe
        }
        break;
    case 'Cerrar_Sesion':
        $query = ejecutarConsulta("UPDATE User SET Activo='0' WHERE Usuario='" . $_SESSION['Id_Empleado'] . "';");
        if ($query) {
            session_destroy();
            header('location: ../../../../index.php');
        } else {
            echo 201;
        }
        break;
}
