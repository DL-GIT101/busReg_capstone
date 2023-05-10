const errors = document.querySelectorAll('form .error');
const inputs = document.querySelectorAll('form input');
const select = document.querySelectorAll('form select');

let i = 0;
errors.forEach(error => {
  if(error.textContent.trim().length > 0){
    inputs[i].style.boxShadow = '0 0 3px red'
    inputs[i].style.border = '1px solid red'
    inputs[i].style.marginBottom = '0px'
  }
    i+=1;
});

let j = 0;
errors.forEach(error => {
  if(error.textContent.trim().length > 0){
    select[j].style.boxShadow = '0 0 3px red'
    select[j].style.border = '1px solid red'
    select[j].style.marginBottom = '0px'
  }
  j+=1;
});

const alerts = document.querySelectorAll('form .alert');
alerts.forEach(alert => {
    if(alert.textContent.trim().length > 0){
      alert.style.display = "block";
    }
});