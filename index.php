<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./Library/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/login.css">
    <title>Login</title>
</head>

<body class="Fondo_D">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5 font_color">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block text-center">
                                <img src="../img/Logo.png" class="Imagen_Logo" alt="Logo" width="400">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="text-gray-900 mb-4 border border-success">Inicio de sesión</h1>
                                    </div>
                                    <div class="text-center">
                                        <span id="Alert_Login" class="text-danger"></span>
                                    </div>
                                    <form class="user" id="Form_Login" name="Form_Login">
                                        <div class="form-group">
                                            <label for="User">Usuario <i class="fa-solid fa-user-secret fa-beat"></i> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-user" id="User" name="User" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Password">Contraseña <i class="fa-solid fa-key fa-beat"></i> <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control form-control-user" id="Password" name="Password" required>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-outline-primary">Ingresar <i class="fa-solid fa-arrow-right-to-bracket"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./Library/jquery/jquery-3.6.3.min.js"></script>
    <script src="./Library/Popper/popper.min.js"></script>
    <script src="./Library/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./Library/fontawesome/all.min.js"></script>
    <script src="./js/index.js"></script>
</body>

</html>