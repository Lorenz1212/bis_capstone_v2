

<?php

class User{
    public function checkLogin($email, $password){
        
    }
}
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();

    $validatedUser = $user->checkLogin($email, $password);

    if($validatedUser){

        $form = new Connection("localhost", "root", "", "system");

        $form->login($email, $password);
    }


}
?>