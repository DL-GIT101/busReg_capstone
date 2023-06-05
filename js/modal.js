const modal = document.querySelector('modal');
const close_btn = document.querySelector('modal > .content > .close');

if(close_btn){
  close_btn.addEventListener('click', () => {
    modal.style.display = "none";
  });
};