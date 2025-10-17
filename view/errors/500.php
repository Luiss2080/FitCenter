<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 500 - Error interno del servidor</title>
    <link rel="icon" type="image/png" href="../../public/img/LogoFitCenter.png">
    <link href="../../public/css/app.css" rel="stylesheet">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
        .error-description {
            font-size: 1rem;
            margin-bottom: 3rem;
            opacity: 0.8;
        }
        .back-button {
            background: white;
            color: #ff6b6b;
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: transform 0.3s ease;
        }
        .back-button:hover {
            transform: translateY(-2px);
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message">Error interno del servidor</div>
        <div class="error-description">
            Ha ocurrido un error inesperado. Nuestro equipo técnico ha sido notificado.
        </div>
        <a href="<?php echo BASE_URL; ?>" class="back-button">
            ← Volver al inicio
        </a>
    </div>
</body>
</html>