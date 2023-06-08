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

const select_review  = document.querySelectorAll('.select_review');
const denied_message  = document.querySelectorAll('.denied_message');

if(select_review && denied_message){

  select_review.forEach((review, index )=> {
      if(review.value == "Uploaded"){
        review.classList.add('uploaded');
      }else if(review.value == "Pending"){
        review.classList.add('pending');
      }else if(review.value == "Denied"){
        review.classList.add('denied');   
      }else if(review.value == "Approved"){
        review.classList.add('approved');
      }

      review.addEventListener('change', () => {
        let selected = review.options[review.selectedIndex];
        review.className = "select_review";

        denied = denied_message[index];
        denied.className = "denied_message hidden";

          if(selected.innerText == "Uploaded"){
            review.classList.add('uploaded');
          }else if(selected.innerText == "Pending"){
            review.classList.add('pending');      
          }else if(selected.innerText == "Denied"){
            review.classList.add('denied');
            denied.classList.remove('hidden');
          }else if(selected.innerText == "Approved"){
            review.classList.add('approved');   
          }
      });
  });
};