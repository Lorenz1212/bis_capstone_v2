<?php
class Connection{
    private $host;
    private $name;
    private $password;
    private $database;

    public function __construct($host, $name, $password, $database)
    {
        $this->host = $host;
        $this->name = $name;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect(){
        $conn = mysqli_connect()
    }

    public function login($email, $password){
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_execute($stmt);   
        $res = mysqli_stmt_get_result($stmt);

        if($res > 0){
            while($row = mysqli_fetch_assoc($res)){
                if($password === $row['password']){
                    header("Location: dashboard");
                }
            }
        }
    }
}