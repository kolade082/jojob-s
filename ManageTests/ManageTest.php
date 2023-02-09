<?php

namespace ManageTests;

use CSY\DatabaseTable;
use CSY\MyPDO;
use PHPUnit\Framework\TestCase;

class ManageTest
{
    private $pdo;

    public function __construct()
    {
        $myDb = new MyPDO();
        $this->pdo = $myDb->db('testJob');
    }

    public function cleanser(): void
    {
        $tableNames = ['job', 'category', 'admin', 'applicants', 'contact'];

        foreach ($tableNames as $tableName) {
            $table = new DatabaseTable($this->pdo, $tableName, 'id');
            $table->customFind("TRUNCATE TABLE $tableName", []);
        }
    }

    public function access($data = []){
        $user = [
            'id' => 1,
            'fullname' => 'name',
            'username' => 'username',
            'password' => 'letmein',
            'usertype' => 'ADMIN'
        ];

        $_SESSION['loggedin'] = 1;
        $_SESSION['userDetails'] = $user;
    }
    public function addJob($data = [])
    {
        $databaseJobs = new DatabaseTable($this->pdo, 'job', 'id');
        $criteria = [
            'title' => $data['title'] ?? 'First level tech support',
            'description' => $data['description'] ?? 'To work alongside the IT field',
            'salary' => $data['salary'] ?? '£15,000 - £18,000',
            'location' => $data['location'] ?? 'Milton Keynes',
            'categoryId' => $data['categoryId'] ?? 1,
            'closingDate' => $data['closingDate'] ?? '2023-04-09',
        ];

        $databaseJobs->insert($criteria);
        return $this->pdo->lastInsertId();
    }

    public function addCat($data = []): string
    {
        $databaseCats = new DatabaseTable($this->pdo, 'category', 'id');
        $criteria = [
            'name' => $data['name'] ?? 'IT',
        ];

        $databaseCats->insert($criteria);
        return $this->pdo->lastInsertId();
    }

    public function applicants($data = [])
    {
        $databaseApp = new DatabaseTable($this->pdo, 'applicants', 'id');
        $criteria = [
            'name' => $data['name'] ?? 'Kolade Dara',
            'email' => $data['email'] ?? 'koladedara@gmail.com',
            'details' => $data['details'] ?? 'N/A',
            'jobId' => $data['jobId'] ?? 1,
            'cv' => $data['cv'] ?? '',
        ];

        $databaseApp->insert($criteria);
    }
    public function addEnquiry($data = []){
        $databaseContact = new DatabaseTable($this->pdo, 'contact', 'id');
        $values = [
                'name' => $data['name'] ?? 'Darkay',
                'telephone' => $data['telephone'] ?? '07834567',
                'email' => $data['email'] ?? 'kay@gmail.com',
                'enquiry' => $data['enquiry'] ?? 'Hello, I have a question',
                'adminId' => $data['adminId'] ?? NULL
            ];
        $databaseContact->insert($values);
        return $this->pdo->lastInsertId();
    }
public function addUser($data = []){
    $databaseContact = new DatabaseTable($this->pdo, 'admin', 'id');
    $values = [
        'fullname' => $data['fullname'] ?? 'Dara Kolade',
        'username' => $data['username'] ?? 'Darkay',
        'password' => $data['password'] ?? 'letmein',
        'usertype' => $data['usertype'] ?? 'ADMIN'
    ];
    $databaseContact->insert($values);
    return $this->pdo->lastInsertId();
}

}