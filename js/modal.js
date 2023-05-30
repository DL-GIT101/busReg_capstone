const modal = document.querySelector('modal');
const close_btn = document.querySelector('modal > .content > .close');

close_btn.addEventListener('click', () => {
  modal.style.display = "none";
});