document.addEventListener('DOMContentLoaded', event => {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#menu-toggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // Auto close flash messages after 5 seconds
    const flashMsg = document.getElementById('msg-flash');
    if(flashMsg){
        setTimeout(() => {
            flashMsg.style.display = 'none';
        }, 5000);
    }

    // Security constraints (Disable Right Click, DevTools, View Source)
    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Disable common developer keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Prevent F12
        if (e.key === 'F12' || e.keyCode === 123) {
            e.preventDefault();
        }
        
        // Prevent Ctrl+Shift+I / Cmd+Option+I (Inspect)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'I' || e.key === 'i' || e.keyCode === 73)) {
            e.preventDefault();
        }
        
        // Prevent Ctrl+Shift+C / Cmd+Option+C (Inspect Element)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'C' || e.key === 'c' || e.keyCode === 67)) {
            e.preventDefault();
        }

        // Prevent Ctrl+Shift+J / Cmd+Option+J (Console)
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && (e.key === 'J' || e.key === 'j' || e.keyCode === 74)) {
            e.preventDefault();
        }

        // Prevent Ctrl+U / Cmd+U (View Source)
        if ((e.ctrlKey || e.metaKey) && (e.key === 'U' || e.key === 'u' || e.keyCode === 85)) {
            e.preventDefault();
        }
    });
});
