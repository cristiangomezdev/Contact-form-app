<?php
define("DEV_MODE", true);
//define("DEV_MODE", FALSE);
// BASE DE DATOS
define("DRIVER", "mysql");
define("HOST", "localhost");
define("DSN", "mysql:host=localhost;dbname=maindevs");
define("DBNAME", "maindevs");
define("USER", "root");
define("PASS", "");
define("PORT", "3306");
define('SITE_KEY', '');
define('SECRET_KEY', ''); 
// EMAIL ACCOUNT
define("MAILER", "infomaindevs@gmail.com");
// CLASSES
class Validation
{
    private $name;
    private $email;
    private $phone;
    private $message;
    private $error;
    private $nameError;
    private $emailError;
    private $phoneError;
    private $messageError;
    public $errorStatus;

    function __construct()
    {
        $this->name = "";
        $this->email = "";
        $this->phone = "";
        $this->message = "";
        $this->nameError = array(
            "empty" => "",
            "minlength" => "",
            "maxlength" => ""
        );
        $this->emailError = array(
            "empty" => "",
            "minlength" => "",
            "maxlength" => "",
            "valid" => ""
        );
        $this->phoneError = array(
            "empty" => "",
            "valid" => ""
        );
        $this->messageError = array(
            "empty" => "",
            "length" => "",
            "valid" => ""
        );
        $this->errorStatus = 0;
    }
    //FUNCTIONS
    function errorHandler($key, $validation, $description)
    {
        $value = $key . "Error";
        $this->$value[$validation] = $description;
        //$this->error[$key] = $description;
        
    }
    public function errorDisplay($field)
    {
        $value = $field . "Error";
        $i = 0;
        $errors[] = "";
        foreach ($this->$value as $error)
        {
            if (!empty($error))
            {

                $errors[$i] = $error;
                $i = $i + 1;
            }

        }
        return $errors;
    }

    function checkErrors($errors)
    {
        $value = $errors . "Error";
        //var_dump($this->phone);
        foreach ($this->$value as $error)
        {
            if ($error != "")
            {
                return false;
            }
        }
        return true;
    }

    public function sanitizeInput($input)
    {
        $inputCleaned = htmlspecialchars(stripslashes(strtolower(trim(filter_var($input, FILTER_SANITIZE_STRING)))));

        $inputCleaned = ucwords($inputCleaned);

        return $inputCleaned;
    }

    public function sendForm($name, $email, $phone, $message)
    {
        try
        {
            $pdo = new \PDO(DSN, USER, PASS);
            $date = date('y-m-d H:i:s');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "INSERT INTO contact (name,email,phone,message,date) VALUES ('$name','$email','$phone','$message','$date')";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            //SendNotification('3',$_POST['language']);
            $pdo = NULL;
        }
        catch(\Exception $e)
        {
            errorLog($e->getMessage());
            echo "Mensaje erroneo";

        }
    }
    public function minlength($input)
    {
        if (strlen($input) < 2)
        {
            return true;
        }
        return false;
    }
    public function maxlength($input)
    {
        if (strlen($input) > 50)
        {
            return true;
        }
        return false;
    }
    public function reCaptcha($recaptcha)
    {

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response={$recaptcha}"); 
        $response = json_decode($response);

        $response = (array)$response;

        if ($response['success'] && ($response['score'] && $response['score'] > 0.5))
        {
            echo "<div class='alert alert-success'> Validaci&oacute;n correcta :) </div>";
            return true;
        }

        else
        {
            echo "<div class='alert alert-danger'> Validaci&oacute;n incorrecta :( </div>";
            return false;
        }

    }

    //SETTERS AND GETTERS
    public function set_name($name)
    {
        if (empty($name))
        {
            $this->errorHandler("name", "empty", "The name field is empty");
        }
        if ($this->minlength($name))
        {
            $this->errorHandler("name", "minlength", "The name must be at least 2 caracters");
        }
        if ($this->maxlength($name))
        {
            $this->errorHandler("name", "maxlength", "The name must be at least 2 caracters");
        }
        if ($this->checkErrors("name"))
        {
            $this->name = $this->sanitizeInput($name);
            // echo "<br>work<br>";
            
        }
        else
        {
            $this->errorStatus = 1;
            // echo "<br>not work <br>";
            
        }
    }

    public function set_email($email)
    {
        if (empty($email))
        {
            $this->errorHandler("email", "empty", "The email field is empty");
        }
        if ($this->minlength($email))
        {
            $this->errorHandler("email", "minlength", "The email must be at least 2 caracters");
        }
        if ($this->maxlength($email))
        {
            $this->errorHandler("name", "minlength", "The name must be at least 2 caracters");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->errorHandler("email", "valid", "The email field is invalid");
        }
        if ($this->checkErrors("email"))
        {
            $this->email = $this->sanitizeInput($email);
            // echo "<br>work<br>";
            
        }
        else
        {
            $this->errorStatus = 1;
            // echo "<br>not work <br>";
            
        }

    }

    public function set_phone($phone)
    {
        if (empty($phone))
        {
            $this->errorHandler("phone", "empty", "The phone field is empty");
        }
        $phone = str_replace([' ', '.', ',', '-', '(', ')'], '', $phone);
        /*
        if(preg_match('/^(?:(?:00)?549?)?0?(?:11|[2368]\d)(?:(?=\d{0,2}15)\d{2})??\d{8}$/D',$phone)){
            $this->errorHandler("phone","valid","The phone number is invalid");
        }
        */
        if ($this->checkErrors("phone"))
        {
            $this->phone = $this->sanitizeInput($phone);
            // echo "<br>work<br>";
            
        }
        else
        {
            $this->errorStatus = 1;
            // echo "<br>not work <br>";
            
        }
    }

    public function set_message($message)
    {
        if (empty($message))
        {
            $this->errorHandler("message", "empty", "The message field is empty");
        }
        if ($this->minlength($message))
        {
            $this->errorHandler("message", "minlength", "The message must be at least 2 caracters");
        }
        if (strlen($message) > 250)
        {
            $this->errorHandler("message", "valid", "The message number is invalid");
        }
        if ($this->checkErrors("message"))
        {
            $this->message = $this->sanitizeInput($message);
            // echo "<br>work<br>";
            
        }
        else
        {
            $this->errorStatus = 1;
            // echo "<br>not work <br>";
            
        }

    }
    public function get_phone()
    {
        return $this->phone;
    }
    public function get_email()
    {
        return $this->email;
    }
    public function get_name()
    {
        return $this->name;
    }
    public function get_message()
    {
        return $this->message;
    }
    public function get_status()
    {
        return $this->errorStatus;
    }
}
function reCaptcha($recaptcha)
{

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe&response={$recaptcha}");
    $response = json_decode($response);

    $response = (array)$response;

    if ($response['success'] /*&& ($response['score'] && $response['score'] > 0.3)*/)
    {
        echo "<div class='alert alert-success'> Validaci&oacute;n correcta :) </div>";
        return true;
    }

    else
    {
        echo "<div class='alert alert-danger'> Validaci&oacute;n incorrecta :( </div>";
        return false;
    }

}
if ((isset($_POST['submit'])) && reCaptcha($_POST['recaptcha']))
{
    $clase = new Validation();
    $clase->set_name($_POST['name']);
    $clase->set_email($_POST['email']);
    $clase->set_phone($_POST['phone']);
    $clase->set_message($_POST['message']);
    if ($clase->get_status() != 1)
    {
        $clase->sendForm($clase->get_name() , $clase->get_email() , $clase->get_phone() , $clase->get_message());
        echo "mensaje enviado";
    }
    else
    {
        $arr = array(
            'name' => $clase->errorDisplay('name') ,
            'email' => $clase->errorDisplay('email') ,
            'phone' => $clase->errorDisplay('phone') ,
            'message' => $clase->errorDisplay('message')
        );
        echo json_encode($arr);
    }

}

