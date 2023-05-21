const notif_modal = document.getElementById('notif_modal');
const close_btn = document.getElementById('modal_close_btn');
const file_link = document.getElementById('file_link');

close_btn.onclick = () => notif_modal.style.display = "none";

const delete_file_btns = document.querySelectorAll('.delete_file');
delete_file_btns.forEach(btn => {
    btn.addEventListener('click', () => {
        notif_modal.style.display = "block";
        file_link.href = "delete_file.php?file=" + encodeURIComponent(btn.value);
      
    });console.log(btn);
  });