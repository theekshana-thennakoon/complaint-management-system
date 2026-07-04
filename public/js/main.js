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

    // Dynamic row addition for complaints
    const addRowBtn = document.getElementById('addRowBtn');
    if (addRowBtn) {
        addRowBtn.addEventListener('click', function() {
            const tbody = document.getElementById('detailsTbody');
            const rowCount = tbody.querySelectorAll('tr').length;
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="row-number">${rowCount + 1}</td>
                <td><input type="text" name="detail_letter_no[]" class="form-control"></td>
                <td><input type="text" name="detail_name[]" class="form-control"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                </td>
            `;
            tbody.appendChild(tr);
            updateRowNumbers();
        });
    }

    // Dynamic row removal and updating numbers
    const detailsTable = document.getElementById('detailsTable');
    if (detailsTable) {
        detailsTable.addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
                const tr = e.target.closest('tr');
                tr.remove();
                updateRowNumbers();
            }
        });
    }

    function updateRowNumbers() {
        const tbody = document.getElementById('detailsTbody');
        if (tbody) {
            const rows = tbody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                const numCell = row.querySelector('.row-number') || row.firstElementChild;
                if(numCell) {
                    numCell.textContent = index + 1;
                    numCell.classList.add('row-number');
                }
            });
        }
    }
});
