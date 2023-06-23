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

const add_user = document.querySelector('table td .data');

if(add_user){
  add_user.addEventListener('click', () =>{
    window.location.href = "addUser.php";
});
}


const profile = document.querySelectorAll('table td:nth-child(2) div.data');
  profile.forEach(status => {
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

  const documents = document.querySelectorAll('table td:nth-child(3) div.data');
  documents.forEach(status => {
    if(status.innerText === "Complete"){
      status.className = "data green";
    }else if(status.innerText === "Incomplete"){
      status.className = "data orange";
    }else{
      status.className = "data gray";
    }
    status.addEventListener('click', () => {
      let id = status.parentNode.parentNode.firstElementChild.textContent;
      window.location.href = "documents.php?id=" + encodeURIComponent(id);
  });
  });

  const permit = document.querySelectorAll('table td:nth-child(4) div.data');
  permit.forEach(status => {
      if(status.innerText === "Approved"){
        status.className = "data green";
      }else{
        status.className = "data gray";
      }
      status.addEventListener('click', () => {
        let id = status.parentNode.parentNode.firstElementChild.textContent;
        window.location.href = "../permit/msme.php?id=" + encodeURIComponent(id);
    });
  });