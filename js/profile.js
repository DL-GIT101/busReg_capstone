const permit = document.getElementById("permit_status");

switch (permit.innerText) {
    case "Approved":
        permit.classList.add('approved');
        break;
    case "Pending":
        permit.classList.add('pending');
        break;
    case "Denied":
        permit.classList.add('denied');
        break;  
    default:
        permit.classList.add('none');
}