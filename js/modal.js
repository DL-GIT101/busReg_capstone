const modals = document.querySelectorAll('modal');
const close_btn = document.querySelectorAll('modal .close');

if(close_btn){
  close_btn.forEach(btn => {
      btn.addEventListener('click', () => {
          modals.forEach(modal => {
            modal.style.display = "none";
          });
      });
  });
};

const delete_modal = document.querySelector('modal.delete');
const delete_link = document.querySelector('modal.delete a');
const delete_btn = document.querySelectorAll('table img.delete');

if(delete_modal && delete_btn){
  delete_btn.forEach(btn => {
    btn.addEventListener('click', () => {
      delete_modal.style.display = "flex";
      let td = btn.parentNode;
      let view = td.firstElementChild;
      let href = view.getAttribute('href');
      let lastIndex = href.lastIndexOf('/');
      let fileName = href.substring(lastIndex + 1);

      delete_link.href = "../php/deleteFile.php?file=" + encodeURIComponent(fileName);
    });
  });
};

const info_modal = document.querySelector('modal.info');
const info_btn = document.querySelector('table img.info');

if(info_modal && info_btn){
  info_btn.addEventListener('click', () => {
    info_modal.style.display = "flex";
  });
};