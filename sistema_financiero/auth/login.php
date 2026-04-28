<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
        }

        .card {
            border-radius: 15px;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center">

<div class="card shadow p-4" style="width: 350px;">

    <h3 class="text-center mb-3">🔐 Iniciar sesión</h3>

    <form action="validar.php" method="POST">

        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="email" class="form-control" placeholder="ejemplo@mail.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="********" required>
        </div>

        <div class="d-grid">
            <button class="btn btn-dark">Ingresar</button>
        </div>

    </form>

</div>

</body>
</html>