document.addEventListener("DOMContentLoaded", function(event) {
    setTimeout(function(){
	options.hideInfoMessage('info');
    }, options.timeout_val);
});

const options = {
    timeout_val: 5000,
    
    hideInfoMessage: function(classType){
	let infoElements = document.getElementsByClassName(classType);

	for (const [key, value] of Object.entries(infoElements)) {
	    value.style.display = "none";
	}
    },

    checkFormPasswords: function(){
	let password1 = document.getElementById('password1');
	let password2 = document.getElementById('password2');

	if(password1.value != password2.value){
	    alert('Podane hasła nie są takie same!');
	    return false;
	}

	return true;
    }
}


