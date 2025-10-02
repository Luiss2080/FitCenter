    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?php echo date('Y'); ?> CareCenter. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-right">
                    <p>Versión <?php echo APP_VERSION; ?> | 
                       <a href="/contacto">Contacto</a> | 
                       <a href="/ayuda">Ayuda</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="<?php echo ASSETS_URL; ?>/js/app.js"></script>
    
    <?php if (isset($jsAdicional) && is_array($jsAdicional)): ?>
        <?php foreach ($jsAdicional as $js): ?>
            <script src="<?php echo ASSETS_URL; ?>/js/<?php echo $js; ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Scripts inline si existen -->
    <?php if (isset($scriptInline)): ?>
        <script>
            <?php echo $scriptInline; ?>
        </script>
    <?php endif; ?>
    
    <!-- Google Maps API si está configurado -->
    <?php if (defined('GOOGLE_MAPS_API_KEY') && !empty(GOOGLE_MAPS_API_KEY)): ?>
        <script async defer 
                src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&libraries=places,geometry">
        </script>
    <?php endif; ?>
    
</body>
</html>