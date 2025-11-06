document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('download-button');
    const menu = document.getElementById('download-menu');
    const pdf = document.getElementById('download-pdf');
    const excel = document.getElementById('download-excel');

    if (!btn || !menu) return;

    btn.addEventListener('click', function (e) {
        e.preventDefault();
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', function (e) {
        if (!menu.contains(e.target) && e.target !== btn) {
            menu.style.display = 'none';
        }
    });

    pdf.addEventListener('click', function () {
        open('/descargar/pdf/')
    });
    
    excel.addEventListener('click', function () {
        open('/descargar/excel/')
    });
});