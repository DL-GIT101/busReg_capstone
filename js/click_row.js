const rows = document.querySelectorAll(".user_info");

rows.forEach( row => {
    if(row.cells[1].innerText === "Created"){
        row.addEventListener('click', () => {
            let id = row.cells[0].innerText;
            
            let url = "msme_profile.php?id=" + encodeURIComponent(id);
    
            window.location.href = url;
        })
    }else {
        row.classList.remove('user_info');
    }
});

