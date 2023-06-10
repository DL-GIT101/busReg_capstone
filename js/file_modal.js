const del_modal = document.getElementById('file_del');
const file_link = document.getElementById('file_link');
const del_btn = document.querySelectorAll('td > .delete');

del_btn.forEach(btn => {
  btn.addEventListener('click', () => {
      del_modal.style.display = "block";

      if(pathLevels-1 === 1){
        file_link.href = "php/delete_file.php?file=" + encodeURIComponent(btn.value);
      }else if(pathLevels-1 === 2){
        file_link.href = "../php/delete_file.php?file=" + encodeURIComponent(btn.value);
      }
  
    });
  });

const btn_file_del = document.querySelector('#file_del > .content  button');

if(btn_file_del){
  btn_file_del.addEventListener('click', () => {
    del_modal.style.display = "none"
  });
};
const info_img = document.getElementById('info');
const info_modal = document.getElementById('info_modal'); 
if(info_modal){
  info_img.addEventListener('click', () => {
      info_modal.style.display = "block";
  });
};

const info_del = document.querySelector('#info_modal > .content  button');
if(info_del){
  info_del.addEventListener('click', () => {
      info_modal.style.display = "none"
  });
};

const approve = document.getElementById('approve_modal');
const approve_btn = document.getElementById('approve_btn');
const approve_cancel = document.querySelector('#approve_modal > .content  button');

approve_btn.addEventListener('click', () => {
    approve.className = "";
});

approve_cancel.addEventListener('click', () => {
    approve.className = "hidden";
});