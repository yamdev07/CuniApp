@props([
    'type' => 'alert', // alert, confirm, verification
    'title' => '',
    'message' => '',
    'email' => '',
    'show' => false
])

<!-- Modal Overlay -->
<div class="modal-overlay {{ $show ? 'active' : '' }}" id="modalOverlay" style="{{ $show ? '' : 'display: none;' }}">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">
                <i class="bi bi-info-circle" style="color: var(--primary);"></i>
                <span>{{ $title }}</span>
            </h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="modal-body" id="modalBody">
            <!-- Dynamic content will be injected here -->
            <p id="modalMessage" style="color: var(--gray-700);">{{ $message }}</p>
        </div>
        
        <div class="modal-footer" id="modalFooter">
            <button class="modal-btn secondary" onclick="closeModal()">Annuler</button>
            <button class="modal-btn primary" id="modalConfirmBtn">Confirmer</button>
        </div>
    </div>
</div>

<!-- Verification Modal (Special) -->
<div class="modal-overlay" id="verificationModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="bi bi-shield-check" style="color: var(--primary);"></i>
                Vérification Email
            </h3>
            <button class="modal-close" onclick="closeVerificationModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="modal-body">
            <p style="text-align: center; color: var(--gray-600); margin-bottom: 16px;">
                Un code de vérification a été envoyé à<br>
                <strong id="verificationEmail" style="color: var(--primary);"></strong>
            </p>
            <form id="verificationForm" method="POST" action="{{ route('verification.verify') }}">
                @csrf
                <input type="hidden" name="email" id="verificationEmailInput">
                <div class="verification-code-inputs">
                    <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="0">
                    <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="1">
                    <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="2">
                    <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="3">
                    <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="4">
                    <input type="text" class="verification-code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" data-index="5">
                </div>
                <input type="hidden" name="code" id="verificationCodeInput">
                <button type="submit" class="btn-submit" style="width: 100%;">
                    <span>Vérifier</span>
                    <i class="bi bi-check-circle"></i>
                </button>
            </form>
            <div class="verification-info">
                <p>Vous n'avez pas reçu le code ? <a href="#" id="resendCode" onclick="resendVerificationCode(event)">Renvoyer</a></p>
            </div>
            <div class="resend-timer disabled" id="resendTimer">
                Renvoyer dans <span id="timerCount">60</span>s
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background: var(--surface);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    max-width: 450px;
    width: 90%;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s ease;
    overflow: hidden;
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--gray-800);
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    width: 32px;
    height: 32px;
    border: none;
    background: var(--gray-100);
    border-radius: var(--radius);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    color: var(--gray-600);
}

.modal-close:hover {
    background: var(--gray-200);
    color: var(--gray-800);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--gray-200);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.modal-btn {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    border-radius: var(--radius);
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-btn.primary {
    background: var(--primary);
    color: var(--white);
}

.modal-btn.primary:hover {
    background: var(--primary-dark);
}

.modal-btn.secondary {
    background: var(--white);
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
}

.modal-btn.secondary:hover {
    background: var(--gray-50);
}

/* Verification Code Inputs */
.verification-code-inputs {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin: 24px 0;
}

.verification-code-input {
    width: 50px;
    height: 60px;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    border: 2px solid var(--gray-300);
    border-radius: var(--radius-md);
    transition: all 0.2s ease;
}

.verification-code-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--primary-subtle);
}

.verification-code-input.filled {
    border-color: var(--primary);
    background: var(--primary-subtle);
}

.verification-info {
    text-align: center;
    color: var(--gray-600);
    font-size: 13px;
    margin-top: 16px;
}

.verification-info a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

.verification-info a:hover {
    text-decoration: underline;
}

.resend-timer {
    text-align: center;
    color: var(--gray-500);
    font-size: 12px;
    margin-top: 12px;
}

.resend-timer.disabled {
    color: var(--gray-400);
}

.btn-submit {
    width: 100%;
    padding: 14px 24px;
    font-size: 14px;
    font-weight: 600;
    color: var(--white);
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
}
</style>

<script>
// Global Modal Functions
let modalCallback = null;
let resendTimerInterval;

function showModal(type, title, message, callback = null) {
    const overlay = document.getElementById('modalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalFooter = document.getElementById('modalFooter');
    const confirmBtn = document.getElementById('modalConfirmBtn');
    
    modalCallback = callback;
    
    // Set content
    modalTitle.querySelector('span').textContent = title;
    modalMessage.textContent = message;
    
    // Show/hide buttons based on type
    if (type === 'alert') {
        modalFooter.innerHTML = '<button class="modal-btn primary" onclick="closeModal()">OK</button>';
    } else if (type === 'confirm') {
        modalFooter.innerHTML = `
            <button class="modal-btn secondary" onclick="closeModal()">Annuler</button>
            <button class="modal-btn primary" onclick="confirmAction()">Confirmer</button>
        `;
    }
    
    overlay.classList.add('active');
    overlay.style.display = 'flex';
}

function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    overlay.classList.remove('active');
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 300);
    modalCallback = null;
}

function confirmAction() {
    if (modalCallback) {
        modalCallback();
    }
    closeModal();
}

// Verification Modal Functions
function openVerificationModal(email) {
    document.getElementById('verificationEmail').textContent = email;
    document.getElementById('verificationEmailInput').value = email;
    document.getElementById('verificationModal').classList.add('active');
    
    setTimeout(() => {
        document.querySelector('.verification-code-input').focus();
    }, 300);
    
    startResendTimer();
}

function closeVerificationModal() {
    document.getElementById('verificationModal').classList.remove('active');
}

// Verification Code Inputs
document.querySelectorAll('.verification-code-input').forEach((input, index, inputs) => {
    input.addEventListener('input', function() {
        if (this.value.length === 1) {
            this.classList.add('filled');
            if (index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        }
        updateVerificationCode();
    });
    
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '' && index > 0) {
            inputs[index - 1].focus();
            inputs[index - 1].value = '';
            inputs[index - 1].classList.remove('filled');
            updateVerificationCode();
        }
    });
});

function updateVerificationCode() {
    let code = '';
    document.querySelectorAll('.verification-code-input').forEach(input => {
        code += input.value;
    });
    document.getElementById('verificationCodeInput').value = code;
}

function startResendTimer() {
    const timerElement = document.getElementById('resendTimer');
    const timerCount = document.getElementById('timerCount');
    const resendLink = document.getElementById('resendCode');
    let seconds = 60;
    
    timerElement.classList.remove('disabled');
    resendLink.style.pointerEvents = 'none';
    resendLink.style.opacity = '0.5';
    
    clearInterval(resendTimerInterval);
    resendTimerInterval = setInterval(() => {
        seconds--;
        timerCount.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(resendTimerInterval);
            timerElement.classList.add('disabled');
            resendLink.style.pointerEvents = 'auto';
            resendLink.style.opacity = '1';
        }
    }, 1000);
}

function resendVerificationCode(e) {
    e.preventDefault();
    const email = document.getElementById('verificationEmailInput').value;
    
    fetch('{{ route("verification.resend") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showModal('alert', 'Succès', 'Un nouveau code a été envoyé à votre adresse email.');
            startResendTimer();
        } else {
            showModal('alert', 'Erreur', data.message || 'Une erreur est survenue.');
        }
    })
    .catch(error => {
        showModal('alert', 'Erreur', 'Une erreur est survenue. Veuillez réessayer.');
    });
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeVerificationModal();
    }
});

// Make functions globally available
window.showModal = showModal;
window.closeModal = closeModal;
window.openVerificationModal = openVerificationModal;
window.closeVerificationModal = closeVerificationModal;
window.resendVerificationCode = resendVerificationCode;
</script>