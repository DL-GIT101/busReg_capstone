const rows = document.querySelectorAll(".user_info");

rows.forEach( row => {
    row.cells[0].addEventListener('click', () => {
            let id = row.cells[0].innerText;
            
            let url = "msme_profile.php?id=" + encodeURIComponent(id);
    
            window.location.href = url;
        })
});

