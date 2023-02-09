<?php

namespace tests;
use CSY\DatabaseTable;
use CSY\MyPDO;
use CSY2038\Controllers\AdminController;
use ManageTests\ManageTest;
use PHPUnit\Framework\TestCase;

/**
 * @covers CSY2038\Controllers\AdminController
 */
class AdminControllerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * */
    public function testAddjob()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $_SESSION['userDetails'] = [
            'usertype' => 'CLIENT',
            'id' => 1
        ];
        $data = ['submit' => true,
                'title' => 'First level tech support',
                'description' => 'To work alongside the IT field',
                'salary' =>  '£15,000 - £18,000',
                'location' => 'Milton Keynes',
                'categoryId' =>  1,
                'closingDate' => '2023-04-09',
        ];

        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], $data);
        $adminPage = $adminController->addjob();


        $this->assertEquals($adminPage['title'], 'addjob');
        $this->assertArrayHasKey("categories", $adminPage['variables']);

        $jobs = $this->databaseJobs->findAll();
var_dump($jobs);
        $this->assertNotEmpty($jobs);
        $this->assertEquals(count($jobs), 1);
        $this->assertEquals($jobs[0]['title'], $data['title']);

    }
    /**
     * @runInSeparateProcess
     * */
    public function testEditjob()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $id = $this->manageTest->addJob();

        $data = ['submit' => true,
            'id' => $id,
            'title' => 'Warehouse Op',
            'description' => 'N/A',
            'salary' =>  'N/A',
            'location' => 'N/A',
            'categoryId' =>  1,
            'closingDate' => '2023-05-09',
        ];

        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, ['id' => $id], $data);
        $adminPage = $adminController->editjob();

        $this->assertArrayHasKey("job", $adminPage['variables']);
        $this->assertArrayHasKey("categories", $adminPage['variables']);

        $job = $this->databaseJobs->find("id", $id)[0];
        $this->assertEquals($job['title'], $data['title']);

    }
    /**
     * @runInSeparateProcess
     * */
    public function testDeletejob()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $this->manageTest->addJob();

        $jobs = $this->databaseJobs->findAll();

        $this->assertEquals($jobs[0]['archive'], NULL);
        $this->assertEquals(count($jobs), 1);
        $job = $jobs[0];

        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], ['id' => $job['id']]);
        $adminController->deletejob();

        $jobs = $this->databaseJobs->findAll();
        $this->assertEquals($jobs[0]['archive'], 1);
    }
    /**
     * @runInSeparateProcess
     * */
    public function testRepostjob()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $this->manageTest->addJob();

        $jobs = $this->databaseJobs->findAll();

        $this->assertEquals($jobs[0]['archive'], NULL);
        $this->assertEquals(count($jobs), 1);
        $job = $jobs[0];

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], ['id' => $job['id']]);
        $adminController->deletejob();

        $jobs = $this->databaseJobs->findAll();
        $this->assertEquals($jobs[0]['archive'], 1);

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], ['id' => $job['id']]);
        $adminController->repostjob();

        $jobs = $this->databaseJobs->findAll();
        $this->assertEquals($jobs[0]['archive'], NULL);
    }
    /**
     * @runInSeparateProcess
     * */
    public function testAddEditCategory()
    {

        $this->manageTest->cleanser();
        $this->manageTest->access();

        //Test add category
        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [],
            ['submit' => true, 'name' => 'Development']);
        $adminPage = $adminController->addEditCategory();


        $this->assertEquals($adminPage['title'], 'editcategories');
        $this->assertArrayHasKey("currentCategory", $adminPage['variables']);

        $categories = $this->databaseCategories->findAll();
        $this->assertNotEmpty($categories);
        $this->assertEquals(count($categories), 1);
        $this->assertEquals($categories[0]['name'], 'Development');

        //Test update category
        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [],
            ['submit' => true, 'id' => $categories[0]['id'], 'name' => 'Engineering']);
        $adminPage = $adminController->addEditCategory();

        $categories = $this->databaseCategories->findAll();
        $this->assertEquals(count($categories), 1);
        $this->assertEquals($categories[0]['name'], 'Engineering');

    }

    public function testDisplayCat(){
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $id = $this->manageTest->addCat(["name" => "Engineering"]);
        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, ['id' => $id], []);
        $adminPage = $adminController->addEditCategory();

        $this->assertEquals($adminPage['title'], 'editcategories');
        $this->assertArrayHasKey("currentCategory", $adminPage['variables']);
        $this->assertEquals($adminPage['variables']['currentCategory']['name'], 'Engineering');
    }

    /**
     * @runInSeparateProcess
     * */
    public function testDeletecategory()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $this->manageTest->addCat();

        $categories = $this->databaseCategories->findAll();
        $this->assertNotEmpty($categories);
        $this->assertEquals(count($categories), 1);
        $category = $categories[0];

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], ['id' => $category['id']]);
        $adminController->deletecategory();

        $categories = $this->databaseCategories->findAll();
        $this->assertEmpty($categories);
    }

    public function testJobs()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $this->manageTest->addJob();
        $this->manageTest->addCat();
        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], []);
        $adminPage = $adminController->jobs();

        $this->assertEquals("admin/jobs.html.php", $adminPage['template']);
        $this->assertEquals("jobs", $adminPage['title']);

        $this->assertNotNull($adminPage['variables']['jobs']);
        $this->assertNotNull($adminPage['variables']['categories']);

        $jobs = $adminPage['variables']['jobs'];
        $categories = $adminPage['variables']['categories'];

        $this->assertGreaterThan(0, count($jobs));
        $this->assertGreaterThan(0, count($categories));

        $job = $jobs[0];
//        var_dump($job);
        $this->assertArrayHasKey('id', $job);

        $category = $categories[0];
        $this->assertArrayHasKey('id', $category);
        $this->assertArrayHasKey('name', $category);
    }
    public function testJobsWithCatFilter()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $catId = $this->manageTest->addCat();
        $jobId = $this->manageTest->addJob(['categoryId' => $catId]);

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact,
            ["category_name" => $catId], []);
        $admin = $adminController->jobs();

        $jobs = $admin['variables']['jobs'];
        $this->assertNotNull($jobs);
        $this->assertCount(1, $jobs);
        $this->assertEquals($jobId, $jobs[0]['id']);
    }

    public function testJobsWithCategoryAndClientFilter()
    {
        $this->manageTest->cleanser();

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], []);
        $expectedResult = [
            'template' => 'admin/jobs.html.php',
            'title' => 'jobs',
            'variables' => [
                'jobs' => [],
                'categories' => [],
            ],
        ];

        $_SESSION['userDetails'] = [
            'usertype' => 'CLIENT',
            'id' => 2,
        ];

        $result = $adminController->jobs();
        $this->assertEquals($expectedResult, $result);
    }

    public function testJobsWithCategoryWhereClientFilter(){
        $this->manageTest->cleanser();

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, ['category_name'=>1], []);
        $expectedResult = [
            'template' => 'admin/jobs.html.php',
            'title' => 'jobs',
            'variables' => [
                'jobs' => [],
                'categories' => [],
            ],
        ];

        $_SESSION['userDetails'] = [
            'usertype' => 'CLIENT',
            'id' => 2,
        ];

        $result = $adminController->jobs();
        $this->assertEquals($expectedResult, $result);

    }
    public function testApplicants()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $id = $this->manageTest->addJob();

        $this->manageTest->applicants(['jobId' => $id]);
        $this->manageTest->applicants(['jobId' => $id]);

        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, ["id" => $id], []);
        $adminPage = $adminController->applicants();

        $this->assertNotEmpty($adminPage['variables']);

        $job = $this->databaseJobs->find("id", $id)[0];
        $this->assertNotNull($job);
        $this->assertEquals($adminPage['variables']['job'], $job);


        $applicants = $this->databaseApplicants->find("jobId", $id);
        $this->assertEquals($adminPage['variables']['applicants'], $applicants);
        $this->assertEquals(2, count($adminPage['variables']['applicants']));
    }
    public function testEnquiry()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        for($i = 1; $i < 5; $i++){
            $this->manageTest->addEnquiry();
        }

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], []);
        $adminPage = $adminController->enquiry();

        $this->assertEquals(4, count($adminPage['variables']['contacts']));
    }
    public function testUpdateEnquiry()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $id = $this->manageTest->addEnquiry();

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], ['id'=>$id]);
        $adminController->updateEnquiry();

        $contacts = $this->databaseContact->findAll();
        $updatedContact = $this->databaseContact->find('id', $id);

        $this->assertNotNull($updatedContact[0]['adminId']);
        $this->assertEquals(count($contacts), 1);
    }


    public function testUsers()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        for($i = 1; $i < 5; $i++){
            $this->manageTest->addUser();
        }


        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], []);
        $adminPage = $adminController->users();
        $users = $this->databaseAdmin->findAll();
        $this->assertEquals(4, count($users));
        $this->assertArrayHasKey('template', $adminPage);
    }

    /**
     * @runInSeparateProcess
     * */
    public function testDeleteuser()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $this->manageTest->addUser();

        $users = $this->databaseAdmin->findAll();
        $this->assertNotEmpty($users);
        $this->assertEquals(count($users), 1);
        $user = $users[0];

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], ['id' => $user['id']]);
        $adminController->deleteuser();

        $users = $this->databaseAdmin->findAll();
        $this->assertEmpty($users);
    }
    /**
     * @runInSeparateProcess
     * */
    public function testRegister()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();
        $data = ['submit' => true,
            'fullname' => 'Dara Kolade',
            'username' => 'Darkay',
            'password' => 'password123',
            'usertype' => 'ADMIN'
        ];
        $this->manageTest->access($data);

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], $data);
        $adminController->register();

        $admin = $this->databaseAdmin->find('username', 'Darkay');

        $this->assertNotNull($admin);
        $this->assertCount(1, $admin);
        $this->assertEquals($admin[0]['fullname'], 'Dara Kolade');
    }

    public function testCategories()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $this->manageTest->addCat();

        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], []);
        $adminPage = $adminController->categories();

        $categories = $adminPage['variables']['categories'];
        $this->assertCount(1, $categories);
        $this->assertEquals($categories[0]['name'], "IT");
    }

    /**
     * @runInSeparateProcess
     * */
    public function testLogin()
    {
        $this->manageTest->cleanser();
        $registerData = [
            'submit' => true,
            'fullname' => 'Dara Kolade',
            'username' => 'Darkay',
            'password' => 'password123',
            'usertype' => 'ADMIN'
        ];
        $this->manageTest->access($registerData);

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], $registerData);
        $adminController->register();

        $loginData = [
            'submit' => true,
            'username' => 'Darkay',
            'password' => 'password123'
        ];
        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], $loginData);
        $loginResult = $adminController->login();

        $this->assertEquals($loginResult['template'], 'admin/login.html.php');
        $this->assertEquals($loginResult['title'], 'login');
        $this->assertArrayHasKey('errors', $loginResult['variables']);
        $this->assertArrayHasKey('message', $loginResult['variables']);
        $this->assertTrue($_SESSION['loggedin'] > 0);
        $this->assertArrayHasKey('id', $_SESSION['userDetails']);
    }
    /**
     * @runInSeparateProcess
     * */

    public function testLoginInvalidUsername()
    {
        $this->manageTest->cleanser();
        $registerData = [
            'submit' => true,
            'fullname' => 'Dara Kolade',
            'username' => 'Darkay',
            'password' => 'LETMEIN',
            'usertype' => 'ADMIN'
        ];
        $this->manageTest->access($registerData);

        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], $registerData);
        $adminController->register();

        $loginData = [
            'submit' => true,
            'username' => 'Invalid',
            'password' => 'LETMEIN'
        ];
        $adminController = new AdminController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], $loginData);
        $loginResult = $adminController->login();

        $this->assertEquals($loginResult['template'], 'admin/login.html.php');
        $this->assertArrayHasKey('message', $loginResult['variables']);
        $this->assertEquals($loginResult['variables']['message'], 'Invalid Cred');
        $this->assertArrayNotHasKey('id', $_SESSION);
    }

    /**
     * @runInSeparateProcess
     * */
    public function testLogout()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], []);
        $adminController->logout();

        $this->assertArrayNotHasKey('loggedin', $_SESSION);
    }

    public function testIndex()
    {
        $this->manageTest->cleanser();
        $this->manageTest->access();

        $adminController = new AdminController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], []);
        $adminPage = $adminController->index();

        $this->assertEquals($adminPage['template'], 'admin/index.html.php');
        $this->assertEquals($adminPage['title'], 'Admin');
        $this->assertEquals($adminPage['variables'], []);
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
