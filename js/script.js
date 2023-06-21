const navLogo = document.querySelector('nav .logo');

const filePath = window.location.pathname;
const directoryPath = filePath.substring(0, filePath.lastIndexOf('/'));
const pathLevels = directoryPath.split('/').length - 1;

navLogo.addEventListener('click', () => {
    
    if(pathLevels === 1){
        window.location.href = 'index.php';
    }else if(pathLevels === 2){
        window.location.href = '../index.php';
    }else if(pathLevels === 3){
        window.location.href = '../../index.php';
    }
});

const adminLogo = document.querySelector('nav#subnav .logo');
adminLogo.addEventListener('click', () => {

    if(pathLevels === 2){
        window.location.href = 'dashboard.php';
    }else if(pathLevels === 3){
        window.location.href = '../dashboard.php';
    }
});

const navIcon = document.querySelector('nav #toggle');
const navButtonGrp = document.querySelector('nav .button-group');
const navButtonGrpStyle = window.getComputedStyle(navButtonGrp);

if(navIcon){
    navIcon.addEventListener('click', () => {
        if(navButtonGrpStyle.display === "none"){
            navButtonGrp.style.display = "block";
            setTimeout(() => {
                navButtonGrp.style.maxHeight = '100px';
            }, 10);
        }else{
            navButtonGrp.style.maxHeight = '';
            setTimeout(() => {
                navButtonGrp.style.display = "";
            },400);
        }
    });
};