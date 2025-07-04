function validateForm() {
    const phone = document.querySelector('[name="contact_no"]').value.trim();
    const firstName = document.querySelector('[name="first_name"]').value.trim();
    const lastName = document.querySelector('[name="last_name"]').value.trim();

    const phoneRegex = /^0\d{9}$/;
    const nameRegex = /^[A-Za-z]{2,50}$/;

    if (!phoneRegex.test(phone)) {
        alert("Contact number must be 10 digits, start with 0, and contain only numbers.");
        return false;
    }

    if (!nameRegex.test(firstName)) {
        alert("First name must be 2-50 letters only. No numbers or symbols.");
        return false;
    }

    if (!nameRegex.test(lastName)) {
        alert("Last name must be 2-50 letters only. No numbers or symbols.");
        return false;
    }

    return true;
}
