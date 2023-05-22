const notif_modal = document.getElementById('notif_modal');
const file_link = document.getElementById('file_link');

const modal = document.querySelectorAll('.modal');
const close_btn = document.querySelectorAll('.modal_close_btn, .modal a');
close_btn.forEach((btn, index) => {
    btn.addEventListener('click', () => {
        modal[index].style.display = "none";      
    });console.log(btn);console.log(modal[index]);
  });

const delete_file_btns = document.querySelectorAll('.delete_file');
delete_file_btns.forEach(btn => {
    btn.addEventListener('click', () => {
        notif_modal.style.display = "block";
        file_link.href = "delete_file.php?file=" + encodeURIComponent(btn.value);
    });
  });