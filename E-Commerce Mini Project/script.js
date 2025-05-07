/* Login Validation */

// Select form elements
let lname = document.getElementById('lname'); // Email field
let lpsw = document.getElementById('lpsw'); // Password field
let lerrorMsg = document.querySelectorAll('.l'); // Error messages

// Email pattern (basic email format validation)
let patternEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

function submitLogin() {
    // Check if fields are empty
    if (lname.value == '' || lpsw.value == '') {
        lerrorMsg.forEach(function (e) {
            e.textContent = 'Fill in all fields';
            e.classList.remove('not-visible');
        });
        return false;
    } else {
        // Validate email format
        if (patternEmail.test(lname.value)) {
            // Simulate a successful login check with static values
            return true;
            
        } else {
            // Invalid email format
            lerrorMsg[0].textContent = 'Invalid email format';
            lerrorMsg[0].classList.remove('not-visible');
            return false;
        }
    }
}


function errorResetLogin() {
    lerrorMsg.forEach(function (e) {
        if (!e.classList.contains('not-visible')) {
            e.classList.add('not-visible');
        }
    })
}

/* Sign-Up Validation */

// Select form elements
let sname = document.getElementById('sname');
let spsw = document.getElementById('spsw');
let srpsw = document.getElementById('srpsw');
let email = document.getElementById('email');
let serrorMsg = document.querySelectorAll('.error-msg');

// Regular expression for validating name (letters and spaces only)
let patternName = /^[a-zA-Z\s]+$/;

// Regular expression for validating email
patternEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

function submitSign() {
    // Check if any field is empty
    if (sname.value == '' || spsw.value == '' || email.value == '' || srpsw.value == '') {
        serrorMsg.forEach(function (e) {
            e.textContent = 'Fill in all fields';
            e.classList.remove('not-visible');
        });
        return false; // Prevent form submission
    } else {
        // Validate name (only letters and spaces)
        if (patternName.test(sname.value)) {
            // Validate email format
            if (patternEmail.test(email.value)) {
                // Check if passwords match
                if (spsw.value === srpsw.value) {
                    // Allow form submission
                    return true;
                } else {
                    // Display password mismatch error
                    serrorMsg[3].textContent = 'Passwords do not match';
                    serrorMsg[3].classList.remove('not-visible');
                    return false;
                }
            } else {
                // Display invalid email format error
                serrorMsg[1].textContent = 'Invalid email format';
                serrorMsg[1].classList.remove('not-visible');
                return false;
            }
        } else {
            // Display invalid name error
            serrorMsg[0].textContent = 'Invalid name format';
            serrorMsg[0].classList.remove('not-visible');
            return false;
        }
    }
}

function errorResetSign() {
    serrorMsg.forEach(function (e) {
        if (!e.classList.contains('not-visible')) {
            e.classList.add('not-visible');
        }
    })
}

//feedback form validation
function FeedbackFormValidate() {
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const comment = document.getElementById("comment").value.trim();
    const errorEl = document.getElementById("error");

    // Reset previous error
    errorEl.textContent = "";
    errorEl.style.color = "white";

    // Check if any field is empty
    if (!name || !email || !comment) {
        errorEl.textContent = "All fields are required.";
        return false; // Prevent submission
    }

    // Email format validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        errorEl.textContent = "Enter a valid email address.";
        return false;
    }

    return true; // Allow submission
}

function resetError() {
    const errorEl = document.getElementById("error");
    errorEl.textContent = "";
}


