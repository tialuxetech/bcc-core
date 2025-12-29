(function () {
    function toggleACFFields() {
        const verificationStep = document.querySelector('.fls_signup_verification');
        const acfFieldsWrapper = document.querySelector('.acf-user-register-fields');

        if (!acfFieldsWrapper) return;

        if (verificationStep) {
            // Hide ACF fields during verification
            acfFieldsWrapper.style.display = 'none';
        } else {
            // Show ACF fields during initial registration
            acfFieldsWrapper.style.display = '';
        }
    }

    // Initial run
    document.addEventListener('DOMContentLoaded', toggleACFFields);

    // Observe DOM changes (FluentAuth is JS-driven)
    const observer = new MutationObserver(toggleACFFields);
    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });
})();
