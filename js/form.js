const error_msg = document.querySelectorAll('form .error-msg');
const inputs = document.querySelectorAll('form input[type=email],form input[type=password], form input[type=text], form select,form input[type=file], #map');

if(error_msg && inputs){

  error_msg.forEach((msg, i) => {
    
    if (msg.textContent.trim().length > 0) {
      inputs[i].classList.add('error-input');
    }
  
    inputs[i].addEventListener('input', () => {
      inputs[i].classList.remove('error-input');
      error_msg[i].classList.add('hidden');
    });
  });

  // Form Error Alert
  const error_alert = document.querySelector('form .error-alert');
  if(error_alert){
    if(error_alert.textContent.trim().length > 0){
      error_alert.classList.remove('hidden');
    };
  };
};
  
const review_selects = document.querySelectorAll('form table select.review');
const denied_message = document.querySelectorAll('form table select.denied-message');
const denied_error = document.querySelectorAll('form table div.denied-error');

if(review_selects && denied_message){
  review_selects.forEach((select, index) => {

    let denied_select = denied_message[index];
    
    if(select.value == "Uploaded"){
      select.classList.add('uploaded');
    }else if(select.value == "Pending"){
      select.classList.add('pending');
    }else if(select.value == "Denied"){
      select.classList.add('denied');
      denied_select.style.display = "block";
    }else if(select.value == "Approved"){
      select.classList.add('approved');
    }

      

      select.addEventListener('change', () => {

        let selectedOption = select.options[select.selectedIndex];
        denied_select.removeAttribute('style');
        select.className = "review";

        denied_error[index].textContent = "";
        select.classList.remove('error_input');

        if(selectedOption.value == "Uploaded"){
          select.classList.add('uploaded');
        }else if(selectedOption.value == "Pending"){
          select.classList.add('pending');
        }else if(selectedOption.value == "Denied"){
          select.classList.add('denied');
          denied_select.style.display = "block";
        }else if(selectedOption.value == "Approved"){
          select.classList.add('approved');
        }
      });
  });

  denied_error.forEach((message, index) => {
    if (message.textContent.trim().length > 0) {
      review_selects[index].classList.add('error_input');
    }
  });
};
