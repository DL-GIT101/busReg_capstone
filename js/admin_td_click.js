const rows = document.querySelectorAll(".user_info");
rows.forEach( row => {
    row.cells[1].addEventListener('click', () => {
            let id = row.cells[0].innerText;
            let url = "msme_profile.php?id=" + encodeURIComponent(id);
            window.location.href = url;
        });
    row.cells[2].addEventListener('click', () => {
            let id = row.cells[0].innerText;
            let url = "msme_documents.php?id=" + encodeURIComponent(id);
            window.location.href = url;
        });
});

