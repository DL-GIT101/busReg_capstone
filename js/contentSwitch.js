const one = document.getElementById('content_1');
const two = document.getElementById('content_2');

const edit = document.getElementById('content_1_edit');
const documents = document.getElementById('content_1_document');

const upload = document.getElementById('content_2_upload');
const profile = document.getElementById('content_2_profile');

documents.addEventListener('click', () => {
            two.className = "";
            one.className = "hidden";
            edit.className = "hidden";
            documents.className = "back hidden";
            upload.className = "";
            profile.className = "back";
            
});

profile.addEventListener('click', () => {
            one.className = "";
            two.className = "hidden";
            upload.className = "hidden";
            profile.className = "back hidden";
            edit.className = "";
            documents.className = "back";
});