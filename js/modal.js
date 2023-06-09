const modal = document.querySelector('modal');
let content = document.querySelector('modal .content');
let title = document.querySelector('modal .title');
let sentence = document.querySelector('modal .sentence');
let button_grp = document.querySelector('modal .button-group');

const delete_btn = document.querySelectorAll('table img.delete');

if(delete_btn){
  delete_btn.forEach(btn => {
    btn.addEventListener('click', () => {

      modal.className = "";
  
      content.className = "content error";

      title.innerText = "Delete File";

      sentence.innerHTML = '<p class="sentence">Are you sure you want to delete this file? <br> This action cannot be undone</p>';

      let td = btn.parentNode;
      let view = td.firstElementChild;
      let href = view.getAttribute('href');
      let lastIndex = href.lastIndexOf('/');
      let fileName = href.substring(lastIndex + 1);
      let link = document.createElement('a');
      if(pathLevels === 2){
        link.href = "../php/deleteFile.php?file=" + encodeURIComponent(fileName);
      }else if(pathLevels === 3){
        link.href = "../../php/deleteFile.php?file=" + encodeURIComponent(fileName);
      }
      
      link.textContent = 'Delete';
      
      let close_button = document.createElement('button');
      close_button.textContent = 'Cancel';
      close_button.className = 'close';
    
      button_grp.innerHTML = '';
      button_grp.appendChild(link);
      button_grp.appendChild(close_button);

      close_button.addEventListener('click', () => {
          modal.className = "hidden";
      });
    });
  });
};

const info_btn = document.querySelector('table img.info');

if(info_btn){
  info_btn.addEventListener('click', () => {

      modal.className = "";
  
      content.className = "content";

      title.innerText = "On the Place of Business";

      sentence.innerHTML = '<p class="sentence">- Building/Occupancy Certificate<br>      - Lease of Contract <br>      - Notice of Award/Award Sheet<br>      - Homeowners/Neighborhood Certification of No Objection  </p>';
      
      let close_button = document.createElement('button');
      close_button.textContent = 'Close';
      close_button.className = 'close';
    
      button_grp.innerHTML = '';
      button_grp.appendChild(close_button);

      close_button.addEventListener('click', () => {
          modal.className = "hidden";
      });
    });
};

const delete_user = document.querySelectorAll('div.action.delete,a.action.delete');
const admin_page = document.querySelector('#page');

if(delete_user){
  delete_user.forEach(btn => {
    btn.addEventListener('click', () => {

      modal.className = "";
  
      content.className = "content error";

      let link = document.createElement('a');

      if(admin_page.innerText === "User Management"){
        let row = btn.parentNode.parentNode;
        let idTD = row.firstElementChild;
        let id = idTD.innerText;
        title.innerText = "Delete User";

        sentence.innerHTML = '<p class="sentence">Are you sure you want to delete this user? <br> This action cannot be undone</p>';

        link.href = "../../php/userDelete.php?user=" + encodeURIComponent(id);

      }else if(admin_page.innerText === "Business Profile"){
        let user_id = document.querySelector('#user_id');
        id = user_id.innerText;

        title.innerText = "Delete Profile";

        sentence.innerHTML = '<p class="sentence">Are you sure you want to delete this Profile? <br> This action cannot be undone</p>';

        link.href = "../../php/userDelete.php?profile=" + encodeURIComponent(id);

      }else if(admin_page.innerText === "Uploaded Documents"){
        let user_id = document.querySelector('#user_id');
        id = user_id.innerText;

        title.innerText = "Delete All Documents";

        sentence.innerHTML = '<p class="sentence">Are you sure you want to delete all documents? <br> This action cannot be undone</p>';

        link.href = "../../php/userDelete.php?documents=" + encodeURIComponent(id);
      }else if(admin_page.innerText === "Admin Management"){
        let row = btn.parentNode.parentNode;
        let idTD = row.firstElementChild;
        let id = idTD.innerText;

        title.innerText = "Delete Admin";

        sentence.innerHTML = '<p class="sentence">Are you sure you want to delete this account? <br> This action cannot be undone</p>';

        link.href = "../../php/userDelete.php?admin=" + encodeURIComponent(id);

      }else if(admin_page.innerText === "Issuing Business Permit"){
        let permitID = document.querySelector('#permitID');
        let id = permitID.innerText;

        title.innerText = "Delete Permit";

        sentence.innerHTML = '<p class="sentence">Are you sure you want to delete this permit? <br> This action cannot be undone</p>';

        link.href = "../../php/userDelete.php?permit=" + encodeURIComponent(id);

      }

      link.textContent = 'Delete';
      
      let close_button = document.createElement('button');
      close_button.textContent = 'Cancel';
      close_button.className = 'close';
    
      button_grp.innerHTML = '';
      button_grp.appendChild(link);
      button_grp.appendChild(close_button);

      close_button.addEventListener('click', () => {
          modal.className = "hidden";
      });
    });
  });
};

const file_status_img = document.querySelectorAll('table td .status img');
const denied_messages = document.querySelectorAll('table td .message');

if(file_status_img){

  const messages = Array.from(denied_messages, div => div.textContent);

  file_status_img.forEach((file, index) => {

    denied_messages[index].textContent = "";

    file.addEventListener('click', () => {

      modal.className = "";

      if(file.alt == "Approved"){
        content.className = "content success";
        sentence.innerHTML = '<p class="sentence">The document has been approved</p>';
        title.innerText = "Approved";
      }else if(file.alt == "Denied"){
        content.className = "content error";
        title.innerText = "Denied";

        sentence.innerHTML = '<p class="sentence">The document has been denied <br>' + messages[index] +'</p>';
        
      }else if(file.alt == "Pending"){
        content.className = "content warning";
        title.innerText = "Pending";
        sentence.innerHTML = '<p class="sentence">The document is being reviewed</p>';
      }else{
        content.className = "content gray";
        title.innerText = "Uploaded";
        sentence.innerHTML = '<p class="sentence">The document has been uploaded</p>';
      }

      let close_button = document.createElement('button');
      close_button.textContent = 'Close';
      close_button.className = 'close';
    
      button_grp.innerHTML = '';
      button_grp.appendChild(close_button);

      close_button.addEventListener('click', () => {
          modal.className = "hidden";
      });
    });
  });
};

let modal_close_btn = document.querySelector('modal .button-group .close');

if(modal_close_btn){
  modal_close_btn.addEventListener('click', () => {
    modal.className = "hidden";
  });
};