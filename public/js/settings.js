// Cropper.js and profile image logic for settings page
// Usage Link - https://github.com/fengyuanchen/cropperjs
let cropper;
const triggerBtn = document.getElementById('triggerProfileImage');
const fileInput = document.getElementById('profile_image_input');
const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
const cropperImage = document.getElementById('cropperImage');
const uploadBtn = document.getElementById('uploadCroppedImage');
const profileImgPreview = document.getElementById('profileImgPreview');
const croppedImageDataInput = document.getElementById('cropped_image_data');
let croppedDataUrl = null;

if (triggerBtn && fileInput && cropperModal && cropperImage && uploadBtn && profileImgPreview && croppedImageDataInput) {
    triggerBtn.addEventListener('click', function() {
        fileInput.value = '';
        fileInput.click();
    });
    fileInput.addEventListener('change', function() {
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropperModal.show();
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    });
    document.getElementById('cropperModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(cropperImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            movable: true,
            zoomable: true,
            rotatable: false,
            scalable: false
        });
    });
    document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        cropperImage.src = '';
    });
    uploadBtn.addEventListener('click', function() {
        if (cropper) {
            cropper.getCroppedCanvas({width: 300, height: 300}).toBlob(function(blob) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    croppedDataUrl = reader.result;
                    profileImgPreview.src = croppedDataUrl;
                    croppedImageDataInput.value = croppedDataUrl;
                    cropperModal.hide();
                };
                reader.readAsDataURL(blob);
            }, 'image/png');
        }
    });
} 