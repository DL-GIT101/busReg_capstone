const permit_status = document.querySelector('#permit-status');
if(permit_status){
    switch (permit_status.innerText) {
        case "Approved":
            permit_status.style.backgroundColor = "rgb(25,135,84)";
            permit_status.style.color = "rgb(245, 245, 247)";
            break;
        case "None":
            permit_status.style.backgroundColor = "rgb(66, 66, 69)";
            permit_status.style.color = "rgb(245, 245, 247)";
            break;
    }
};