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