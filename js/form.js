const errors = document.querySelectorAll('form >.error');
const inputs = document.querySelectorAll('form > input');

let i = 0;
errors.forEach(error => {
  if(error.textContent.trim().length > 0){
    inputs[i].style.boxShadow = '0 0 3px red'
    inputs[i].style.border = '1px solid red'
    inputs[i].style.marginBottom = '0px'
  }
    i+=1;
});

const alerts = document.querySelectorAll('form > .alert');
alerts.forEach(alert => {
    if(alert.textContent.trim().length > 0){
      alert.style.display = "block";
    }
});