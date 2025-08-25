window.addEventListener('DOMContentLoaded', function() 
{
	const oldpassword = document.getElementById('password');
    const password = document.getElementById('newpassword');
	const confirmPassword = document.getElementById('renewpassword');
	const registerBtn = document.querySelector('button[type="submit"][id="changePasswordBtn"]');

	let errorMsg = document.getElementById('passwordError');
	if (!errorMsg) {
		errorMsg = document.createElement('p');
		errorMsg.id = 'passwordError';
		errorMsg.style.color = 'red';
		errorMsg.style.display = 'none';
		registerBtn.parentNode.insertBefore(errorMsg, registerBtn.nextSibling);
	}

	function validatePasswords() {
		if (password.value && confirmPassword.value && password.value === confirmPassword.value) {
			registerBtn.disabled = false;
			errorMsg.style.display = 'none';
		}
        else if (!oldpassword.value && password.value === confirmPassword.value)
        {
            registerBtn.disabled = true;
        }
		else {
			registerBtn.disabled = true;
			if (password.value && confirmPassword.value) {
				errorMsg.textContent = 'Las nuevas contraseñas no coinciden.';
				errorMsg.style.display = 'block';
			}
			else {
				errorMsg.style.display = 'none';
			}
		}
	}

	registerBtn.disabled = true;
	password.addEventListener('input', validatePasswords);
	confirmPassword.addEventListener('input', validatePasswords);
});