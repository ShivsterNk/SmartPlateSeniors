// ══════════════════════════════════════════
//  SmartPlate — dashboard.js
// ══════════════════════════════════════════

document.addEventListener('DOMContentLoaded', () => {

    const sidebar         = document.getElementById('sidebar');
    const mainContent     = document.getElementById('mainContent');
    const navLogo         = document.getElementById('navLogo');
    const navProfileBtn   = document.getElementById('navProfileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    const overlay         = document.getElementById('overlay');
    const nutritionBody   = document.getElementById('nutritionBody');
    const expandBtn       = document.getElementById('expandBtn');

    let sidebarOpen = true;
    let profileOpen = false;

    // Sidebar toggle — SmartPlate logo click
    navLogo.addEventListener('click', (e) => {
        e.preventDefault();
        sidebarOpen = !sidebarOpen;
        sidebar.classList.toggle('hidden', !sidebarOpen);
        mainContent.classList.toggle('full-width', !sidebarOpen);
        closeProfile();
    });

    // Profile dropdown toggle
    navProfileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        profileOpen = !profileOpen;
        profileDropdown.classList.toggle('open', profileOpen);
        overlay.classList.toggle('active', profileOpen);
    });

    // Close profile on overlay click
    overlay.addEventListener('click', closeProfile);

    // Close profile on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeProfile();
    });

    function closeProfile() {
        profileOpen = false;
        profileDropdown.classList.remove('open');
        overlay.classList.remove('active');
    }

    // Nutrition collapsible
    document.getElementById('nutritionExpand').addEventListener('click', () => {
        nutritionBody.classList.toggle('collapsed');
        expandBtn.classList.toggle('rotated');
    });

    // Sidebar active state
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function (e) {
            document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Nav link active state
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', function (e) {
            document.querySelectorAll('.nav-links a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

});