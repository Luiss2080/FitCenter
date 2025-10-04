<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - CareCenter</title>
    <link rel="stylesheet" href="../../public/css/app.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }
        .email-preview {
            background: #f8f9fa;
            border: 2px dashed #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .btn-verification {
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn-verification:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin: 30px 0;
            gap: 20px;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }
        .step.completed { background: #28a745; }
        .step.current { background: #ffc107; color: #000; }
        .step.pending { background: #6c757d; }
    </style>
</head>
<body class="auth-body">
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h1 style="color: #28a745; margin-bottom: 10px;">¡Registro Exitoso!</h1>
        <p style="color: #666; font-size: 18px;">Tu cuenta ha sido creada correctamente</p>

        <!-- Indicador de pasos -->
        <div class="step-indicator">
            <div class="step completed" title="Registro completado">1</div>
            <div style="width: 50px; height: 2px; background: #28a745; margin-top: 19px;"></div>
            <div class="step current" title="Verificar email">2</div>
            <div style="width: 50px; height: 2px; background: #6c757d; margin-top: 19px;"></div>
            <div class="step pending" title="Acceso al sistema">3</div>
        </div>

        <h3 style="color: #333;">📧 Verificación de Email Requerida</h3>
        <p>Para poder acceder al sistema, necesitas verificar tu dirección de email.</p>

        <!-- Preview del email enviado -->
        <div class="email-preview">
            <h4 style="color: #28a745; margin: 0 0 15px 0;">📬 Email Enviado (Simulado)</h4>
            <div style="background: white; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745;">
                <strong>Para:</strong> <span id="userEmail"><?php echo $_GET['email'] ?? 'tu@email.com'; ?></span><br>
                <strong>Asunto:</strong> Verifica tu cuenta en CareCenter<br>
                <strong>Contenido:</strong>
                <div style="margin-top: 10px; font-style: italic; color: #555;">
                    "Hola <span id="userName"><?php echo $_GET['nombre'] ?? 'Usuario'; ?></span>,<br><br>
                    Para verificar tu cuenta en CareCenter, haz clic en el enlace de abajo.<br>
                    Este enlace expira en 24 horas."
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div style="margin: 30px 0;">
            <?php if (isset($_GET['token'])): ?>
                <a href="verificar_email.php?token=<?php echo htmlspecialchars($_GET['token']); ?>" class="btn-verification">
                    ✅ Verificar Email Ahora
                </a>
            <?php endif; ?>
            
            <a href="reenviar_verificacion.php" class="btn btn-secondary" style="margin: 10px;">
                📧 Reenviar Email
            </a>
        </div>

        <!-- Información adicional -->
        <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h4 style="color: #0066cc; margin: 0 0 10px 0;">💡 ¿Cómo funciona?</h4>
            <ol style="text-align: left; color: #555;">
                <li>Hemos simulado el envío del email (modo desarrollo)</li>
                <li>En producción, recibirías un email real</li>
                <li>Haz clic en "Verificar Email Ahora" para simular la verificación</li>
                <li>Una vez verificado, podrás iniciar sesión</li>
            </ol>
        </div>

        <!-- Enlaces de navegación -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
            <a href="login.php" class="btn btn-primary">🔑 Ir al Login</a>
            <a href="../../index.php" class="btn btn-secondary">🏠 Página Principal</a>
        </div>

        <!-- Log viewer para desarrollo -->
        <div style="margin-top: 20px;">
            <details>
                <summary style="cursor: pointer; color: #666;">🔧 Ver Log de Email (Desarrollo)</summary>
                <div style="background: #f8f9fa; padding: 15px; margin-top: 10px; border-radius: 5px; text-align: left;">
                    <small>
                        <?php
                        $logFile = '../../logs/emails.log';
                        if (file_exists($logFile)) {
                            $logs = file_get_contents($logFile);
                            $emails = explode("📧 EMAIL ENVIADO", $logs);
                            $ultimoEmail = end($emails);
                            echo "<pre style='font-size: 12px; max-height: 200px; overflow-y: auto;'>" . htmlspecialchars($ultimoEmail) . "</pre>";
                        } else {
                            echo "No hay logs disponibles";
                        }
                        ?>
                    </small>
                </div>
            </details>
        </div>
    </div>

    <script>
        // Auto-refresh del log cada 5 segundos
        setInterval(function() {
            // Solo en modo desarrollo
            console.log('Sistema de verificación activo');
        }, 5000);
    </script>
</body>
</html>