const fileStatusMap = {
    Uploaded: '../img/uploaded.svg',
    Approved: '../img/approved.svg',
    Denied: '../img/denied.svg',
    Pending: '../img/pending.svg'
  };
  
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

add_user.addEventListener('click', () =>{
        window.location.href = "add_user.php";
});

const profile = document.querySelectorAll('table td:nth-child(2) div.data');
  profile.forEach(status => {
      if(status.innerText === "Created"){
        status.className = "data green";
      }else{
        status.className = "data gray";
      }
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
  });

  const permit = document.querySelectorAll('table td:nth-child(4) div.data');
  permit.forEach(status => {
      if(status.innerText === "Approved"){
        status.className = "data green";
      }else{
        status.className = "data gray";
      }
  });