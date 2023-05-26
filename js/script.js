const nav_logo = document.getElementById('nav_logo');
nav_logo.style.cursor = 'pointer';
nav_logo.addEventListener('click', () => {
    window.location.href = '../index.php';
});