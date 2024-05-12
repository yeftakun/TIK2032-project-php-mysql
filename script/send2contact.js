function toggleForm(formId) {
    const emailForm = document.getElementById('emailForm');
    const whatsappForm = document.getElementById('whatsappForm');
    const emailTab = document.getElementById('emailTab');
    const whatsappTab = document.getElementById('whatsappTab');

    if (formId === 'emailForm') {
        emailForm.style.display = 'flex';
        whatsappForm.style.display = 'none';
        emailTab.classList.add('active');
        whatsappTab.classList.remove('active');
    } else if (formId === 'whatsappForm') {
        emailForm.style.display = 'none';
        whatsappForm.style.display = 'flex';
        emailTab.classList.remove('active');
        whatsappTab.classList.add('active');
    }
}

function sendWhatsApp() {
    const name = document.getElementById('whatsappName').value;
    const email = document.getElementById('whatsappEmail').value;
    const phone = document.getElementById('whatsappPhone').value;
    const message = document.getElementById('whatsappMessage').value;
    const encodedMessage = encodeURIComponent(`Nama: ${name}\nEmail: ${email}\nNo.Telp:${phone}\n${message}`);

    const whatsappUrl = `https://wa.me/6285775471308/?text=${encodedMessage}`;
    window.open(whatsappUrl, '_blank');
}

function validateEmail() {
    var email = document.getElementById("emailAddress").value;
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Invalid email format. Please enter a valid email address.");
        return false;
    }
    return true;
}