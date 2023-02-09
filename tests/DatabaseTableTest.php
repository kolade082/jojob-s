<?php
namespace tests;

use CSY;
use CSY\DatabaseTable;
use CSY\MyPDO;
use ManageTests\ManageTest;
use PHPUnit\Framework\TestCase;

class DatabaseTableTest extends TestCase
{
    public function testDelete()
    {
        $this->manageTest->cleanser();

        $id = $this->manageTest->addCat();
        $databaseCat = new DatabaseTable($this->pdo, 'category', 'id');

        $function = $databaseCat->find("id", $id)[0];
        $this->assertEquals($function["id"], $id);

        $databaseCat->delete("id", $id);

        $function2 = $databaseCat->find("id", $id);
        $this->assertEmpty($function2);

    }

    public function testFindAll()
    {
        $this->manageTest->cleanser();

        $this->manageTest->addJob();
        $this->manageTest->addJob();
        $this->manageTest->addJob();

        $databaseJobs = new DatabaseTable($this->pdo, 'job', 'id');
        $function = $databaseJobs->findAll();

        $this->assertNotEmpty($function);
        $this->assertTrue(3 == count($function));
    }

    public function testFind()
    {
        $this->manageTest->cleanser();

        $data = [
            'title' => 'Warehouse Op',
        ];
        $id = $this->manageTest->addJob($data);

        $databaseJobs = new DatabaseTable($this->pdo, 'job', 'id');
        $function = $databaseJobs->find("id", $id)[0];

        $this->assertEquals($function["id"], $id);
        $this->assertEquals($function["title"], $data['title']);
    }

    public function testInsert()
    {
        $this->manageTest->cleanser();

        $this->manageTest->addCat();
        $this->manageTest->addCat();
        $this->manageTest->addCat();
        $this->manageTest->addCat();

        $databaseCat = new DatabaseTable($this->pdo, 'category', 'id');
        $function = $databaseCat->findAll()[0];

        $this->assertTrue(4 == count($function));
    }

    public function testUpdate()
    {
        $this->manageTest->cleanser();

        $data = [
            'name' => 'Warehouse'
        ];
        $id = $this->manageTest->addCat($data);

        $databaseCat = new DatabaseTable($this->pdo, 'category', 'id');
        $function = $databaseCat->find("id", $id)[0];

        $this->assertTrue(isset($function["id"]));
        $this->assertEquals($function["id"], $id);
        $data2 = [
            'id' => $id,
            'name' => 'Teaching'
        ];
        $databaseCat->update($data2);
        $function2 = $databaseCat->find("id", $id)[0];

        $this->assertNotEquals($function["name"], $function2["name"]);
        $this->assertEquals($function2["name"], $data2["name"]);
    }


    protected function setUp()
    {
        $myDb = new MyPDO();

        $this->pdo = $myDb->db('testJob');
        $this->databaseJobs = new DatabaseTable($this->pdo, 'job', 'id');
        $this->databaseCategories = new DatabaseTable($this->pdo, 'category', 'id');
        $this->databaseApplicants = new DatabaseTable($this->pdo, 'applicants', 'id');
        $this->databaseAdmin = new DatabaseTable($this->pdo, 'admin', 'id');
        $this->databaseContact = new DatabaseTable($this->pdo, 'contact', 'id');

        $this->manageTest = new ManageTest();
    }
}
