// Cropper.js and profile image logic for settings page
// Usage Link - https://github.com/fengyuanchen/cropperjs
document.addEventListener('DOMContentLoaded', function() {
    let cropper = null;
    const triggerBtn = document.getElementById('triggerProfileImage');
    const fileInput = document.getElementById('profile_image_input');
    const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
    const cropperImage = document.getElementById('cropperImage');
    const uploadButton = document.getElementById('uploadCroppedImage');
    const profilePreview = document.getElementById('profileImgPreview');
    const accountForm = document.getElementById('accountSettingsForm');
    const croppedDataInput = document.getElementById('cropped_image_data');

    // Trigger file input when change button is clicked
    triggerBtn.addEventListener('click', () => fileInput.click());

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                return;
            }

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Please select an image smaller than 2MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result;
                cropperModal.show();
                
                // Destroy existing cropper if it exists
                if (cropper) {
                    cropper.destroy();
                }
                
                // Initialize cropper with a 1:1 aspect ratio
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle cropped image upload
    uploadButton.addEventListener('click', function() {
        if (!cropper) return;

        // Get cropped canvas with optimized dimensions
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        // Convert to PNG with compression
        const compressedDataUrl = canvas.toDataURL('image/png', 0.9);
        
        // Set the cropped image data in the hidden input
        croppedDataInput.value = compressedDataUrl;
        
        // Update preview
        profilePreview.src = compressedDataUrl;
        
        // Submit the form
        accountForm.submit();
    });

    // Clean up when modal is hidden
    document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        fileInput.value = '';
    });
}); 