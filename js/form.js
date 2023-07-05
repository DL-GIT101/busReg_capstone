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
  
const file_Status = document.querySelectorAll('form table select.fileStatus');
const review = document.querySelectorAll('form table select.review');
const review_error = document.querySelectorAll('form table div.error-msg');

if(file_Status && review){
  file_Status.forEach((status, i) => {
        if(status.value !== "Denied"){
            review[i].style.display = "none";
        };

        status.addEventListener('change', () => {
          let selectedOption = status.options[status.selectedIndex];
          if(selectedOption.value !== "Denied"){
            review[i].style.display = "none";
          }else if(selectedOption.value == "Denied"){
            review[i].style.display = "block";
          };
        });
    });
 
};
