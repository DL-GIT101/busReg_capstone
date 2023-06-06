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

const error_alert = document.querySelectorAll('form .error_alert');
error_alert.forEach(alert => {
    if(alert.textContent.trim().length > 0){
      alert.classList.remove('hidden');
    }
});

const select_status  = document.getElementById('status_admin');

if(select_status){
  select_status.addEventListener('change', () => {
      let selected = select_status.options[select_status.selectedIndex];
      select_status.className = "";
        if(selected.innerText == "Uploaded"){
          select_status.classList.add('uploaded');
        }else if(selected.innerText == "Pending"){
          select_status.classList.add('pending');
        }else if(selected.innerText == "Denied"){
          select_status.classList.add('denied');
        }else if(selected.innerText == "Approved"){
          select_status.classList.add('approved');
        }
  });

};