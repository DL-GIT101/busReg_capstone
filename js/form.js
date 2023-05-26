const error_msg = document.querySelectorAll('form .error_msg');
const inputs = document.querySelectorAll('form input[type=email],form input[type=password], form input[type=text], form select,form input[type=file], #map');


for (let i = 0; i < error_msg.length; i++) {
  if(error_msg[i].textContent.trim().length > 0){
    inputs[i].classList.add('error_input');
  }
}

for (let i = 0; i < inputs.length; i++) {
  inputs[i].addEventListener('input', () => {
    inputs[i].classList.remove('error_input');
    error_msg[i].classList.add('hidden');
  });  
}

const alerts = document.querySelectorAll('form .alert');
alerts.forEach(alert => {
    if(alert.textContent.trim().length > 0){
      alert.style.display = "block";
    }
});