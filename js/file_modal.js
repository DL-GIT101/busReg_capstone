const del_modal = document.getElementById('file_del');
const file_link = document.getElementById('file_link');
const del_btn = document.querySelectorAll('td > .delete');
del_btn.forEach(btn => {
  btn.addEventListener('click', () => {
      del_modal.style.display = "block";
      file_link.href = "delete_file.php?file=" + encodeURIComponent(btn.value);
    });
  });

  const btn_file_del = document.querySelector('#file_del > .content  button');
btn_file_del.addEventListener('click', () => {
  del_modal.style.display = "none"
});