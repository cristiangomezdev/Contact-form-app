# php_contact
php poo contact form with front end

This is an implementation of a contact form, everything is from scratch using the next technologies:
(Front end)
-HTML
-CSS
-Javascript
-Ajax

(Backend)
-PHP poo
-reCaptcha v3
-Mysql

the front validates every input, and creates a formData object that is sent via ajax.
the backend contains a class named 'validation' and uses reCaptcha v3 in case of bots.

when the propertyes are settle, the message will be sent to the database 
and the ajax response will provide the status to the front end.

////////////////////////////////////////////////////////////////////////////////////////

Se implementa un formulario de contacto desde 0
usando las siguientes tecnologias:
(Front end)
-HTML
-CSS
-Javascript
-Ajax

(Backend)
-PHP poo
-reCaptcha v3
-Mysql

El front end se encarga de validar cada input para luego instanciar un objecto formData que es enviado via ajax.
el back end contiene una clase llamada validation y usa reCaptcha v3 para evitar bots.

cuando las propiedades estan instanciadas el mensaje se envia a la base de datos
y se le pasa una respuesta del estado al front end mediante ajax.

