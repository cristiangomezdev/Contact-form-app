const form = document.getElementById('Form-data');
const form_name = document.getElementById('form-name');
const form_email = document.getElementById('form-email');
const form_phone = document.getElementById('form-phone')
const form_textarea = document.getElementById('form-message');
const form_not = document.getElementById('sent-notification');
const form_lang = document.getElementsByClassName('translateDiv')
const logo = document.getElementById('form-error');
let arrayVal = [{
    'name': false,
    'email': false,
    'phone': false,
    'textarea': false
}];

function btnLoading() {
    let btn = document.querySelector("#formSubmit");
    btn.style.color = 'transparent';
    btn.classList.add("nopointer");
    btn.classList.toggle("button--loading");
}

function validateInputs() {
    validateInputsIcons()
}


form.addEventListener('change', e => {
    e.preventDefault();
    validateInputsIcons();
});

//Remueve los espacios en blanco de los inputs y para luego validar cada campo.
function validateInputsIcons() {

    const message = [
        [{
            true: 'Ingrese un nombre o empresa',
            false: 'enter name or enterprise'
        }],
        [{
            true: 'ingrese una direccion de correo valida',
            false: 'enter a valid email address'
        }],
        [{
            true: 'ingrese un numero',
            false: 'enter a phone number'
        }],
        [{
            true: 'Ingrese su consulta',
            false: 'enter your question'
        }]
    ]
    const name = form_name.value.trim();
    const email = form_email.value.trim();
    const phone = form_phone.value.trim();
    const textarea = form_textarea.value.trim();
    const tongue = lang();

    if (name === '') {
        setErrorFor(form_name, message[0][0][tongue]);
    } else {
        arrayVal['name'] = true;
        setSuccessFor(form_name);

    }
    if (email === '' || !isEmail(email)) {
        setErrorFor(form_email, message[1][0][tongue]);
    } else {
        arrayVal['email'] = true;
        setSuccessFor(form_email);
    }

    if (phone === '' || isNaN(phone)) {
        setErrorFor(form_phone, message[2][0][tongue]);
    } else {
        arrayVal['phone'] = true;
        setSuccessFor(form_phone);
    }
    if (textarea === '') {
        setErrorFor(form_textarea, message[3][0][tongue]);
    } else {
        arrayVal['textarea'] = true;
        setSuccessFor(form_textarea);
    }



    if (arrayVal['name'] && arrayVal['email'] && arrayVal['phone'] && arrayVal['textarea']) {
        return true;
    } else {
        return false;
    }
}


//En caso que el campo no cumpla con las caracteristicas, se le asigna icono rojo, con su despectivo mensaje.
function setErrorFor(input, message) {

    let form_control = input.parentElement;
    let form_icon = form_control.querySelector('i');
    let form_span = form_control.querySelector('span');
    form_icon.className = "fas fa-exclamation-circle form-check tooltip"
    form_span.style.visibility = 'visible';
    form_icon.style.color = '#ce5a5a';
    form_span.innerText = message;


}
//En caso que el campo cumpla con las caracteristicas, se le asigna icono verde, con su despectivo mensaje.
function setSuccessFor(input) {

    let form_control = input.parentElement;
    form_control.querySelector('span').style.visibility = 'hidden';
    form_control.querySelector('i').style.color = '#399b39';
    form_control.querySelector('i').className = "fas form-check fa-check-circle tooltip"
}

function isEmail(email) {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email);
}

// retorna si esta navegando en ingles
function lang() {
    if (document.querySelector('.translateDiv').id === 'en2') {
        return false
    }
    return true
}

function btnSuccess() {
    let btn = document.querySelector("#formSubmit");
    btn.innerText = '';
    btn.style.color = 'white';
    btn.classList.remove("button--loading");
    let span = document.createElement('i');
    span.classList.add("fas");
    span.classList.add("fa-check");
    btn.classList.add("green");
    btn.appendChild(span);
}
//this functions is off, is just for debugging validation errors in the front
//esta funcion es para validar errores del backend en el front
function errorAppend(errorResponse) {
    Object.keys(errorResponse)
        .forEach(function eachKey(key) {
            //alert(key); // alerts key 

            //console.log(errorResponse[key])
            //console.log(document.getElementById("form-"+key));
            /*
            let divMessage = document.createElement("div");
            divMessage.setAttribute('id','form-errors');
            for (let i = 0; i < errorResponse[key].length; i++) {
              let spanMessage = document.createElement("span");

              spanMessage.innerHTML = errorResponse[key][i];
              divMessage.append(spanMessage);
              document.getElementById("form-"+key).parentNode.append(divMessage);
            }*/
            btnSuccess();

            //alert(errorResponse[key]); // alerts value
        });

}

function errorDelete() {
    if (document.getElementById('form-errors')) {
        document.getElementById('form-errors').remove();
    }
}

function deleteAfter() {
    document.getElementById("form-name").value = '';
    document.getElementById("form-phone").value = '';
    document.getElementById("form-email").value = '';
    document.getElementById("form-message").value = '';
}

function submitFormAjax() {
    if (validateInputsIcons()) {
        //boton send
        document.getElementById('google-response-token').value = "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI";
        btnLoading();
        document.querySelectorAll("#form-errors").forEach(e => e.remove());
        var myForm = document.getElementById("Form-data");
        let formData = new FormData(myForm);
        formData.set("name", 'mariano');
        var xmlhttp;
        //in case of using web browsers from IE 6 to 9
        //en caso que se utilize en navegadores IE de 7 a 9
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
        }

        // Instantiating the request object
        xmlhttp.open("POST", "backend.php", true);
        // Defining event listener for readystatechange event
        errorDelete();

        xmlhttp.onreadystatechange = function() {
            // if (this.readyState !== "complete"){
            //    document.getElementById("response_message").innerHTML = "Loading";
            // }
            if (this.readyState === 4 && this.status === 200) {

                //this line is for testing validations with the front 
                //estas lineas son para testear validaciones desde el frontend
                document.getElementById("sent-notification").style.textAlign = "center";
                document.getElementById("sent-notification").innerHTML = this.responseText;
                //const errorResponse= JSON.parse(this.response);
                //errorAppend(errorResponse);
                //const response_messag=this.response;.reset();
                btnSuccess();
                deleteAfter();
                //const errorResponse= JSON.parse(this.response);

            }
        }

        // Retrieving the form data


        formData.append("submit", true);
        //console.log(formData.name);

        // Sending the request to the server
        xmlhttp.send(formData);
    }
}
/*
      function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
          grecaptcha.execute('reCAPTCHA_site_key', {action: 'submit'}).then(function(token) {
              // Add your logic to submit to your backend server here.
          });
        });
      }
      */