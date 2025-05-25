class ModalSystem {
    constructor() {
        this.setupModals();
        this.polyfillDialog();
        this.setupEditButtons();
    }

    setupModals() {
        // Open modals
        document.querySelectorAll('[data-modal-open]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const modalId = trigger.getAttribute('data-modal-open');
                this.openModal(modalId);
            });
        });

        // Close modals
        document.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
            closeBtn.addEventListener('click', () => {
                const modal = closeBtn.closest('dialog');
                this.closeModal(modal);
            });
        });

        // Close when clicking backdrop
        document.querySelectorAll('dialog').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal);
                }
            });
        });
    }

    setupEditButtons() {
        document.querySelectorAll('[data-modal-edit]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const modalId = btn.getAttribute('data-modal-target') || 'editModal';
                const itemId = btn.getAttribute('data-modal-edit');
                const dataType = btn.getAttribute('data-type') || 'judge';
                
                try {
                    const response = await fetch(`/scoring_system/includes/get_${dataType}.php?id=${itemId}`);
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const itemData = await response.json();
                    if (itemData) {
                        this.openModalWithData(modalId, itemData);
                    }
                } catch (error) {
                    console.error('Error loading data:', error);
                    alert('Failed to load data. Please try again.');
                }
            });
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            if (typeof modal.showModal === 'function') {
                modal.showModal();
            } else {
                modal.style.display = 'block';
                modal.setAttribute('open', '');
            }
            document.body.style.overflow = 'hidden';
        }
    }

    openModalWithData(modalId, data) {
        const modal = document.getElementById(modalId);
        if (modal) {
            // Populate form fields
            for (const [key, value] of Object.entries(data)) {
                const input = modal.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = value;
                    
                    // Handle special cases
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = Boolean(value);
                    } else if (input.tagName === 'SELECT') {
                        input.value = value;
                    }
                }
            }
            
            this.openModal(modalId);
        }
    }

    closeModal(modal) {
        if (modal) {
            // Clear form data when closing
            if (modal.tagName === 'DIALOG') {
                modal.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.type !== 'submit' && input.type !== 'button') {
                        input.value = '';
                        if (input.type === 'checkbox' || input.type === 'radio') {
                            input.checked = false;
                        }
                    }
                });
            }

            if (typeof modal.close === 'function') {
                modal.close();
            } else {
                modal.style.display = 'none';
                modal.removeAttribute('open');
            }
            document.body.style.overflow = '';
        }
    }

    polyfillDialog() {
        if (typeof HTMLDialogElement !== 'function') {
            document.querySelectorAll('dialog').forEach(dialog => {
                if (!dialog.showModal) {
                    dialog.showModal = function() {
                        this.style.display = 'block';
                        this.setAttribute('open', '');
                    };
                    dialog.close = function() {
                        this.style.display = 'none';
                        this.removeAttribute('open');
                    };
                }
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.modalSystem = new ModalSystem(); // Make available globally if needed
});

// Validate email on form submission
async function validateEmailOnSubmit(form) {
    const emailInput = form.querySelector('[name="email"]');
    if (!emailInput || !emailInput.value) return true; // Skip if email is empty
    
    try {
        const response = await fetch('/scoring_system/includes/check_email.php?email=' + encodeURIComponent(emailInput.value));
        const data = await response.json();
        
        if (data.exists) {
            alert(`This email is already registered as a ${data.type}`);
            emailInput.focus();
            return false;
        }
        return true;
    } catch (error) {
        console.error('Validation error:', error);
        return true; // Proceed if validation fails
    }
}

// Modify form submission in modal.js
document.querySelectorAll('dialog form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        if (!await validateEmailOnSubmit(form)) {
            e.preventDefault();
        }
    });
})