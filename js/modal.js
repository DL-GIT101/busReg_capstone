const modal = document.querySelector('modal');
const close_btn = document.querySelector('modal > .content > button');

close_btn.addEventListener('click', () => {
  modal.style.display = "none";
});

const notif_modal = document.getElementById('notif_modal');
const file_link = document.getElementById('file_link');
const delete_file_btns = document.querySelectorAll('.delete_file');
delete_file_btns.forEach(btn => {
    btn.addEventListener('click', () => {
        notif_modal.style.display = "block";
        file_link.href = "delete_file.php?file=" + encodeURIComponent(btn.value);
    });
  });