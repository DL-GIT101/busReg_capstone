const rows = document.querySelectorAll(".user_info");

const del_modal = document.getElementById('user_del');
const user_link = document.getElementById('user_link');

rows.forEach( row => {
    //Profile
    row.cells[1].addEventListener('click', () => {
            let id = row.cells[0].innerText;
            let url = "msme_profile.php?id=" + encodeURIComponent(id);
            window.location.href = url;
        });
    //Documents
    row.cells[2].addEventListener('click', () => {
            let id = row.cells[0].innerText;
            let url = "msme_documents.php?id=" + encodeURIComponent(id);
            window.location.href = url;
        });
    //Delete msme
    row.cells[4].addEventListener('click', () => {
            del_modal.style.display = "block";
            let id = row.cells[0].innerText;
            user_link.href = "php/msme_delete.php?id_user=" + encodeURIComponent(id);
        });
});

const btn_user_del = document.querySelector('#user_del > .content  button');
btn_user_del.addEventListener('click', () => {
  del_modal.style.display = "none"
});

