const imageFile = document.getElementById('post_imageFile');
const img = new Image();
img.style.width = '150px';
img.style.marginTop = '1rem';
imageFile.parentNode.append(img);
imageFile.parentNode.style.height = 'auto';

imageFile.addEventListener('change', event => {

    const preview = document.getElementById('post-image-preview');
    if (preview) {
        preview.remove();
    }

    const file = event.currentTarget.files[0];
    const label = event.currentTarget.parentNode.querySelector('[for="post_imageFile"]');
    label.textContent = file.name;

    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.addEventListener('load', () => {
        img.src = reader.result;
    });
});