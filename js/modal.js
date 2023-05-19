const notif_modal = document.getElementById('notif_modal');
const close_btn = document.getElementById('modal_close_btn');

close_btn.onclick = () => notif_modal.style.display = "none";

const delete_file_btns = document.querySelectorAll('.delete_file');
delete_file_btns.forEach(btn => {
    btn.addEventListener('click', () => {
        notif_modal.style.display = "block";
      
    });console.log(btn);
  });
