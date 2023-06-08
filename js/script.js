const nav_logo = document.getElementById('nav_logo');

const filePath = window.location.pathname;
const directoryPath = filePath.substring(0, filePath.lastIndexOf('/'));
const pathLevels = directoryPath.split('/').length - 1;

nav_logo.style.cursor = 'pointer';
nav_logo.addEventListener('click', () => {
    
    if(pathLevels === 1){
        window.location.href = 'index.php';
    }else if(pathLevels === 2){
        window.location.href = '../index.php';
    }else if(pathLevels === 3){
        window.location.href = '../../index.php';
    }
});