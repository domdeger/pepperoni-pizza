<?php
class AuthenticationController {

    public  function Login() {
        if(!isset($_POST['username']) || isset($_POST['password'])) {
            die('Set required parameters.');
        }

        $name = $_POST['username'];
        $pw = $_POST['password'];

        if($name == $pw) {
            $user = new stdClass();
            $user->name = $name;

            $_SESSION['User'] = $user;

            return http_redirect("/shoutbox/view");
        }
    }

    public  function Register() {
        include_once "../data/IUserAdapter.php";

        if(!isset($_POST['username']) || isset($_POST['password'])) {
            return http_redirect("/Authentication/Register");
        }



    return http_response_code(200);

    }
}
?>