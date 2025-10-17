<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email de Recuperación Enviado - FitCenter</title>
    <link rel="icon" type="image/png" href="../../public/img/LogoFitCenter.png">
    <link rel="stylesheet" href="../../public/css/app.css">
    <style>
        .recovery-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .mail-icon {
            font-size: 4rem;
            color: #007bff;
            margin-bottom: 20px;
        }
        .email-preview {
            background: #f8f9fa;
            border: 2px dashed #007bff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .countdown {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .btn-reset {
            background: #dc3545;
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
        .btn-reset:hover {
            background: #c82333;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="auth-body">
    <div class="recovery-container">
        <div class="mail-icon">📧</div>
        <h1 style="color: #007bff; margin-bottom: 10px;">Email de Recuperación Enviado</h1>
        <p style="color: #666; font-size: 18px;">Revisa tu bandeja de entrada</p>

        <!-- Información del email -->
        <div style="background: #e7f3ff; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <p><strong>📬 Para:</strong> <?php echo htmlspecialchars($_GET['email'] ?? 'tu@email.com'); ?></p>
            <p><strong>⏰ Enviado:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
            <p><strong>⌛ Expira:</strong> <?php echo date('d/m/Y H:i:s', strtotime('+1 hour')); ?> (1 hora)</p>
        </div>

        <!-- Preview del email enviado -->
        <div class="email-preview">
            <h4 style="color: #007bff; margin: 0 0 15px 0;">📬 Email Enviado (Simulado)</h4>
            <div style="background: white; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff;">
                <strong>Asunto:</strong> Recuperar contraseña - CareCenter<br>
                <strong>Contenido:</strong>
                <div style="margin-top: 10px; font-style: italic; color: #555;">
                    "Hola <?php echo htmlspecialchars($_GET['nombre'] ?? 'Usuario'); ?>,<br><br>
                    Para restablecer tu contraseña, haz clic en el enlace de abajo.<br>
                    Este enlace expira en 1 hora por seguridad."
                </div>
            </div>
        </div>

        <!-- Countdown timer -->
        <div class="countdown">
            <h4 style="color: #856404; margin: 0 0 10px 0;">⏰ Tiempo restante: <span id="countdown">60:00</span></h4>
            <small>El enlace expirará en 1 hora por motivos de seguridad</small>
        </div>

        <!-- Botón de acción directo -->
        <?php if (isset($_GET['token'])): ?>
            <div style="margin: 30px 0;">
                <a href="reset_password.php?token=<?php echo htmlspecialchars($_GET['token']); ?>" class="btn-reset">
                    🔐 Cambiar Contraseña Ahora
                </a>
            </div>
        <?php endif; ?>

        <!-- Información de ayuda -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h4 style="color: #333; margin: 0 0 10px 0;">💡 ¿Cómo funciona?</h4>
            <ol style="text-align: left; color: #555;">
                <li>Hemos simulado el envío del email (modo desarrollo)</li>
                <li>En producción, recibirías un email real en tu bandeja</li>
                <li>Haz clic en "Cambiar Contraseña Ahora" para continuar</li>
                <li>El enlace expira en 1 hora por seguridad</li>
            </ol>
        </div>

        <!-- Botones alternativos -->
        <div style="margin: 30px 0;">
            <a href="recover_password_new.php" class="btn btn-secondary">
                🔄 Enviar Otro Email
            </a>
            <a href="login.php" class="btn btn-primary">
                🔑 Volver al Login
            </a>
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
        // Countdown timer
        let timeLeft = 60 * 60; // 60 minutos en segundos
        
        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('countdown').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                document.getElementById('countdown').textContent = 'EXPIRADO';
                document.getElementById('countdown').style.color = '#dc3545';
            } else {
                timeLeft--;
            }
        }
        
        // Actualizar cada segundo
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Llamar inmediatamente
    </script>
</body>
</html>