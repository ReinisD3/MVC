<?php

namespace app\Repositories;

use App\Repositories\UsersRepositoryInterface;
use App\Models\Collections\UsersCollection;
use App\Models\User;
use PDO;

class MysqlUsersRepository implements UsersRepositoryInterface
{
    private array $config;
    private PDO $pdo;

    public function __construct()
    {
        $this->config = json_decode(file_get_contents('config.json'), true);
        try {
            $this->pdo = new PDO($this->config['connection'] . ';dbname=' . $this->config['name'],
                $this->config['username'],
                $this->config['password']);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getRecords(): UsersCollection
    {
        $statement = $this->pdo->prepare("select * from users");

        $statement->execute();

        $users = $statement->fetchALl(PDO::FETCH_CLASS);
        $usersCollection = new UsersCollection();
        foreach ($users as $user) {
            $usersCollection->add(new User(
                $user->name,
                $user->email,
                $user->password,
                $user->id
            ));
        }
        return $usersCollection;
    }
    public function validateLogin(string $email, string $password):?User
    {

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $userFound = $statement->fetchAll(PDO::FETCH_CLASS);
        if(empty($userFound)) return null;
        if(!password_verify($password, $userFound[0]->password)) return null;
        return new User(
            $userFound[0]->name,
            $userFound[0]->email,
            $userFound[0]->password,
            $userFound[0]->id
        );
    }
    public function add(User $newUser):void
    {
        $sql = "INSERT INTO users (name,email,password) VALUES ('{$newUser->name()}', '{$newUser->email()}','{$newUser->password()}')";
        $this->pdo->exec($sql);

    }

}