const rows = document.querySelectorAll(".user_info");
const action_del_btn = document.getElementById('action_dlt');
const page_admin = document.getElementById('page');

const del_modal_user = document.getElementById('user_del');
const user_link = document.getElementById('user_link');

if(rows){
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
            del_modal_user.style.display = "block";
                let id = row.cells[0].innerText;
                user_link.href = "php/msme_delete.php?id_user=" + encodeURIComponent(id);
            });
    });
};

if(action_del_btn){
    const user_id = document.getElementById('user_id');
    
    action_del_btn.addEventListener('click', () => {
        let id = user_id.innerText;
        del_modal_user.style.display = "block";
        if(page_admin.innerText == "Profile"){
            user_link.href = "php/msme_delete.php?profile=" + encodeURIComponent(id);
        }else if(page_admin.innerText == "Documents"){
            user_link.href = "php/msme_delete.php?documents=" + encodeURIComponent(id);
        }
        
    });
};

const btn_user_del = document.querySelector('#user_del > .content  button');
if(btn_user_del){
    btn_user_del.addEventListener('click', () => {
        del_modal_user.style.display = "none"
    });
};


