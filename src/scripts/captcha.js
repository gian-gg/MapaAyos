function generateCaptcha(length = 5) {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let captcha = '';
    for (let i = 0; i < length; i++) {
        captcha += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return captcha;
}

function renderCaptcha(captcha) {
    const preview = document.querySelector('.captcha-form .preview');
    preview.innerHTML = '';
    preview.style.position = 'relative';  // for absolute positioning of noise

    const noiseCount = 50; 
    for (let i = 0; i < noiseCount; i++) {
        const dot = document.createElement('span');
        dot.style.position = 'absolute';
        dot.style.width = '3px';
        dot.style.height = '3px';
        dot.style.borderRadius = '50%';
        dot.style.backgroundColor = `hsl(${Math.random() * 360}, 50%, 50%)`;
        dot.style.top = `${Math.random() * 40}px`; 
        dot.style.left = `${Math.random() * (preview.clientWidth || 200)}px`; 
        dot.style.opacity = '0.3';
        preview.appendChild(dot);
    }

 
    for (let char of captcha) {
        const span = document.createElement('span');
        span.textContent = char;

        // messy styles
        span.style.position = 'relative';
        span.style.zIndex = '1';
        span.style.transform = `
            rotate(${Math.floor(Math.random() * 60 - 30)}deg)
            translate(${Math.floor(Math.random() * 5 - 2)}px, ${Math.floor(Math.random() * 5 - 2)}px)
        `;
        span.style.fontSize = `${Math.floor(Math.random() * 8 + 18)}px`;
        span.style.margin = `${Math.floor(Math.random() * 6)}px ${Math.floor(Math.random() * 6)}px`;
        span.style.fontWeight = ['normal', 'bold', 'bolder', 'lighter'][Math.floor(Math.random() * 4)];
        span.style.fontStyle = Math.random() > 0.5 ? 'italic' : 'normal';
        span.style.letterSpacing = `${Math.floor(Math.random() * 4 - 2)}px`;
        span.style.color = `hsl(${Math.random() * 360}, ${50 + Math.random() * 50}%, ${30 + Math.random() * 30}%)`;
        span.style.display = 'inline-block';

        preview.appendChild(span);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signup-form');
    const signUpBtn = document.getElementById('signUp-btn');
    const captchaModal = document.querySelector('.captcha-modal');
    const captchaInput = document.getElementById('captchaInput');
    const captchaSubmitBtn = document.getElementById('captcha-submit-btn');
    const captchaError = document.querySelector('.captcha-error');
    const captchaVerified = document.getElementById('captcha-verified');

    let currentCaptcha = generateCaptcha();
    renderCaptcha(currentCaptcha);

    // Refresh only
    captchaSubmitBtn.addEventListener('click', () => {
        if (captchaInput.value.trim() === '') {
            // Refresh the CAPTCHA
            currentCaptcha = generateCaptcha();
            renderCaptcha(currentCaptcha);
            captchaInput.value = '';
            captchaError.style.display = 'none';
        } else {
            validateCaptcha();
        }
    });

    // Validate on Enter key
    captchaInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            validateCaptcha();
        }
    });

    // Handle Sign Up click or Submit
    form.addEventListener('submit', function (e) {
        if (captchaVerified.value !== 'true') {
            e.preventDefault();

            // Basic field check (form is mostly filled)
            const requiredFields = ['firstName', 'lastName', 'email', 'password', 'confirmPassword'];
            for (let id of requiredFields) {
                if (!document.getElementById(id).value.trim()) return;
            }

            captchaModal.style.display = 'flex';
        }
    });

    function validateCaptcha() {
        if (captchaInput.value.trim().toUpperCase() === currentCaptcha) {
            captchaVerified.value = 'true';
            captchaModal.style.display = 'none';
            captchaError.style.display = 'none';
            form.submit(); // re-trigger actual form submit
        } else {
            captchaError.style.display = 'block';
            captchaInput.value = '';
            currentCaptcha = generateCaptcha();
            renderCaptcha(currentCaptcha);
        }
    }
});
