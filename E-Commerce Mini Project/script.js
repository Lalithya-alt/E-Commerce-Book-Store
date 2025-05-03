


/* Login Validation */

let lname = document.getElementById('lname');
let lpsw = document.getElementById('lpsw');
let lerrorMsg = document.querySelectorAll('.l');
let patternName = /^[a-zA-Z0-9]+$/;

function submitLogin() {
    if (lname.value == '' || lpsw.value == '') {
        lerrorMsg.forEach(function (e) {
            e.textContent = 'Fill in all fields';
            e.classList.remove('not-visible');
        })
        return false;
    } else {
        if (patternName.test(lname.value)) {
            if (lname.value == 'admin' && lpsw.value == 'admin') {
                window.location.href = 'dashboard.html';
                return false;
            } else {
                lerrorMsg[1].textContent = 'Account doesn\'t exist';
                lerrorMsg[1].classList.remove('not-visible');
                return false;
            }
        } else {
            lerrorMsg[0].textContent = 'Invalid characters';
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
/* Sign-In Validation */

let sname = document.getElementById('sname');
let spsw = document.getElementById('spsw');
let srpsw = document.getElementById('srpsw');
let email = document.getElementById('email');
let serrorMsg = document.querySelectorAll('.s');

function submitSign() {
    if (sname.value == '' || spsw.value == '' || email.value == '' || srpsw.value == '') {
        serrorMsg.forEach(function (e) {
            e.textContent = 'Fill in all fields';
            e.classList.remove('not-visible');
        })
        return false;
    } else {
        if (patternName.test(sname.value)) {
                if (spsw.value === srpsw.value) {
                    window.location.href = 'log-in.html';
                    return false;
                } else {
                    serrorMsg[3].textContent = 'passwords do not match';
                    serrorMsg[3].classList.remove('not-visible');
                    return false;
                }
        } else {
            serrorMsg[0].textContent = 'Invalid characters';
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

/*Billing address Validation*/
function formValidateBill(){
    
}