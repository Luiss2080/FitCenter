<?php
session_start();

// Destruir toda la información de la sesión
session_unset();
session_destroy();

// Redirigir al login con mensaje de confirmación
header('Location: login.php?logout=success');
exit;
?>