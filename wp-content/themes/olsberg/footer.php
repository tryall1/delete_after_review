    <script>
        function toggleMenu() {
            const body = document.body;
            const menu = document.querySelector('.menu');
            
            if (menu.classList.contains('open')) {
                menu.classList.remove('open');
                body.classList.remove('menu-open');
            } else {
                menu.classList.add('open');
                body.classList.add('menu-open');
            }
        }

        function toggleSubmenu(element, event) {
            event.preventDefault();
            
            const menuItem = element.parentNode;
            const submenu = menuItem.querySelector('.submenu');
            const chevron = element.querySelector('.chevron');
            
            // Закрыть все другие подменю
            const allItems = document.querySelectorAll('.menu-item');
            allItems.forEach(item => {
                if (item !== menuItem) {
                    const link = item.querySelector('.menu-link');
                    const otherSubmenu = item.querySelector('.submenu');
                    const otherChevron = item.querySelector('.chevron');
                    
                    link.classList.remove('active');
                    if (otherSubmenu) otherSubmenu.classList.remove('open');
                    if (otherChevron) otherChevron.classList.remove('rotated');
                }
            });
            
            // Переключить текущее подменю
            if (submenu && submenu.classList.contains('open')) {
                element.classList.remove('active');
                submenu.classList.remove('open');
                chevron.classList.remove('rotated');
            } else {
                element.classList.add('active');
                if (submenu) submenu.classList.add('open');
                chevron.classList.add('rotated');
            }
        }
    </script>
    
    <?php wp_footer(); ?>
</body>
</html>
