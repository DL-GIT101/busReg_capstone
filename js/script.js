const navLogo = document.querySelector('nav .logo');

const filePath = window.location.pathname;
const directoryPath = filePath.substring(0, filePath.lastIndexOf('/'));
const pathLevels = directoryPath.split('/').length - 1;

navLogo.style.cursor = 'pointer';
navLogo.addEventListener('click', () => {
    
    if(pathLevels === 1){
        window.location.href = 'index.php';
    }else if(pathLevels === 2){
        window.location.href = '../index.php';
    }else if(pathLevels === 3){
        window.location.href = '../../index.php';
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
                navButtonGrp.style.maxHeight = '60px';
            }, 10);
        }else{
            navButtonGrp.style.maxHeight = '';
            setTimeout(() => {
                navButtonGrp.style.display = "";
            },400);
        }
    });
};