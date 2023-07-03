let fileStatusMap = {};

if(pathLevels === 2){

  fileStatusMap = {
    Uploaded: '../img/uploaded.svg',
    Approved: '../img/approved.svg',
    Denied: '../img/denied.svg',
    Pending: '../img/pending.svg'

  };
}else if(pathLevels === 3){
    fileStatusMap = {
    Uploaded: '../../img/uploaded.svg',
    Approved: '../../img/approved.svg',
    Denied: '../../img/denied.svg',
    Pending: '../../img/pending.svg'
    
  };
}
  
const fileStatusElements = document.querySelectorAll('table td .status');
  
  if (fileStatusElements) {
    fileStatusElements.forEach(file => {
      const status = file.innerText;
      if (fileStatusMap.hasOwnProperty(status)) {
        file.innerText = '';
        const img = document.createElement('img');
        img.src = fileStatusMap[status];
        img.alt = status;
        file.appendChild(img);
      }
    });
  }

const add_user = document.querySelector('table#users td .data');

if(add_user){
  add_user.addEventListener('click', () =>{
    window.location.href = "addUser.php";
});
};

const user_owner = document.querySelectorAll('table#users td:nth-child(2) div.data');
if(user_owner){
  user_owner.forEach(status => {
      if(status.innerText === "Created"){
        status.className = "data green";
      }else{
        status.className = "data gray";
      }
      status.addEventListener('click', () => {
          let id = status.parentNode.previousElementSibling.textContent;
          window.location.href = "profiles.php?id=" + encodeURIComponent(id);
      });
  });
};

const user_business = document.querySelectorAll('table#users td:nth-child(3) div.data');
if(user_business){
  user_business.forEach(status => {
    if(status.innerText === "Created"){
      status.className = "data green";
    }else{
      status.className = "data gray";
    }
    status.addEventListener('click', () => {
      let id = status.parentNode.parentNode.firstElementChild.textContent;
      window.location.href = "documents.php?id=" + encodeURIComponent(id);
    });
  });
};

const user_documents = document.querySelectorAll('table#users td:nth-child(4) div.data');
if(user_documents){
  user_documents.forEach(status => {
    if(status.innerText === "Complete"){
      status.className = "data green";
    }else if(status.innerText === "Incomplete"){
      status.className = "data orange";
    }else{
      status.className = "data gray";
    }
  });
};

const user_permit = document.querySelectorAll('table#users td:nth-child(5) div.data');
if(user_permit){
  user_permit.forEach(status => {
      if(status.innerText === "Issued"){
        status.className = "data green";
      }else{
        status.className = "data gray";
      }
      status.addEventListener('click', () => {
        let id = status.parentNode.parentNode.firstElementChild.textContent;
        window.location.href = "../permit/msme.php?id=" + encodeURIComponent(id);
    });
  });
};

const msme_documents = document.querySelectorAll('table#msme td:nth-child(4) div.data');
if(msme_documents){
  msme_documents.forEach(status => {
    status.style.pointerEvents = 'none';
    let documentCount = status.innerText;
    let count = documentCount.split('/')[0];
    if(count == 12){
      status.className = "data green";
    }else if(count >= 1){
      status.className = "data orange";
    }else{
      status.className = "data gray";
    }
  });
};

const msme_permit = document.querySelectorAll('table#msme td:nth-child(5) div.data');
if(msme_permit){
  msme_permit.forEach(status => {
    status.style.pointerEvents = 'none';
    if(status.innerText === "Approved"){
      status.className = "data green";
    }else{
      status.className = "data gray";
    }
  });
};

const review_btn = document.querySelectorAll('table#msme td:nth-child(6)');
if(review_btn){
  review_btn.forEach(btn => {
    btn.addEventListener('click', () => {
      let id = btn.parentNode.firstElementChild.textContent;
      window.location.href = "review.php?id=" + encodeURIComponent(id);
    });
  });
};

const add_Admin = document.querySelector('table#admin td .data#addUser');

if(add_Admin){
  add_Admin.addEventListener('click', () =>{
    window.location.href = "add_admin.php";
});
};

const adminProfile = document.querySelectorAll('table#admin td:nth-child(2) div.data');
if(adminProfile){
  adminProfile.forEach(status => {
      if(status.innerText === "Created"){
        status.className = "data green";
      }else{
        status.className = "data gray";
      }
  });
};

const adminRole = document.querySelectorAll('table#admin td:nth-child(3) div.data');
if(adminRole){
  adminRole.forEach(status => {
      if(status.innerText === "Superadmin"){
        status.className = "data orange";
      }else if(status.innerText === "None"){
        status.className = "data gray";
      }
  });
};

const edit_adminAction = document.querySelectorAll('table#admin td.actions div.edit');

if(edit_adminAction){
  edit_adminAction.forEach(status => {
      status.addEventListener('click', () => {
          let id = status.parentNode.parentNode.firstElementChild.textContent;
          window.location.href = "edit_profile.php?id=" + encodeURIComponent(id);
      });
  });
};