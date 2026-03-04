document.addEventListener("DOMContentLoaded", function () {
    // Sidebar toggle functionality
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.createElement('div');
    
    // Create and append overlay
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
    
    // Toggle sidebar
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        });
    }
    
    // Close sidebar when clicking on overlay
    overlay.addEventListener('click', function () {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    });
    
    // Improve submenu interaction
    const submenus = document.querySelectorAll('.sidebar .nav-item > a[data-bs-toggle="collapse"]');
    submenus.forEach(submenu => {
        submenu.addEventListener('click', function() {
            const icon = this.querySelector('.fa-chevron-down');
            if(icon) {
                // simple quick rotate if collapsed or not
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                icon.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
                icon.style.transition = 'transform 0.3s ease';
            }
        });
        
        // Setup initial state
        const icon = submenu.querySelector('.fa-chevron-down');
        if(icon && submenu.getAttribute('aria-expanded') === 'true') {
            icon.style.transform = 'rotate(180deg)';
        }
    });

    // Copy to clipboard for shortcodes (table tags)
    const copyTags = document.querySelectorAll('table code');
    copyTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const textToCopy = this.textContent;
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show tooltip or visual feedback
                const originalText = this.textContent;
                this.textContent = "Copiado!";
                this.classList.add('bg-success', 'text-white');
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('bg-success', 'text-white');
                }, 1500);
            }).catch(err => {
                console.error('Failed to copy info: ', err);
            });
        });
    });
});
