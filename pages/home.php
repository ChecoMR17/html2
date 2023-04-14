<?php
session_start();
if (isset($_SESSION['Id_Empleado'])) {
    include "../global/Header.php"; ?>
    <title>Home</title>
    </head>

    <body>
        <?php include "../global/menu.php"; ?>


        <?php include "../global/Fooder.php"; ?>
    </body>

    </html>
<?php
} else {
    header("location:../index.php");
}
?>