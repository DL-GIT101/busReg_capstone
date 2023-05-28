const nav_logo = document.getElementById('nav_logo');
const page = document.querySelector('title');

nav_logo.style.cursor = 'pointer';
nav_logo.addEventListener('click', () => {
    
    if(page.innerText === "Login"){
        window.location.href = 'index.php';
    }else{
        window.location.href = '../index.php';
    }
});