<?php

use CSY\DatabaseTable;
use CSY\MyPDO;
use CSY2038\Controllers\PageController;
use ManageTests\ManageTest;
use PHPUnit\Framework\TestCase;

/**
 * @covers CSY2038\Controllers\PageController
 */
class PageControllerTest extends TestCase
{

    private $pdo;
    private $manageTest;
    private $databaseContact;
    private $databaseAdmin;
    private $databaseApplicants;
    private $databaseJobs;
    private $databaseCategories;

    public function testHome()
    {
        $this->manageTest->cleanser();

        $data = [
            'location' => 'Northampton',
        ];

        $this->manageTest->addJob($data);
        $this->manageTest->addJob();
        $pageController = new PageController($this->databaseJobs,
            $this->databaseCategories, $this->databaseApplicants,
            $this->databaseAdmin, $this->databaseContact, [], []);
        $page = $pageController->home();

        $this->assertTrue(is_array($page));
        $this->assertEquals($page['template'], 'index.html.php');
        $this->assertEquals($page['title'], 'Home');
        $this->assertArrayHasKey('jobs', $page['variables']);
        $this->assertTrue(2 == count($page['variables']['jobs']));
        $this->assertTrue(2 == count($page['variables']['locations']));
        $this->assertArrayHasKey('locations', $page['variables']);
    }

    public function testFilter()
    {

        $this->manageTest->cleanser();
        $data = [
            'location' => 'Northampton',
        ];

        $this->manageTest->addJob($data);
        for($i = 1; $i < 5; $i++){
            $this->manageTest->addJob();
        }

        $pageController1 = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], []);
        $page = $pageController1->home();

        $this->assertTrue(is_array($page));
        $this->assertEquals($page['template'], 'index.html.php');
        $this->assertEquals($page['title'], 'Home');
        $this->assertArrayHasKey('jobs', $page['variables']);
        $this->assertTrue(5 == count($page['variables']['jobs']));
        $this->assertTrue(2 == count($page['variables']['locations']));

        $pageController = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, ['job_location' => $data['location']], []);
        $page = $pageController->home();

        $this->assertTrue(is_array($page));
        $this->assertEquals($page['template'], 'index.html.php');
        $this->assertEquals($page['title'], 'Home');
        $this->assertArrayHasKey('jobs', $page['variables']);
        $this->assertTrue(1 == count($page['variables']['jobs']));
        $this->assertTrue(2 == count($page['variables']['locations']));
    }

    public function testAbout()
    {
        $this->manageTest->cleanser();

        $pageController1 = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], []);
        $page = $pageController1->about();

        $this->assertArrayHasKey('template', $page);
        $this->assertEquals('about.html.php', $page['template']);
        $this->assertArrayHasKey('title', $page);
        $this->assertEquals('About', $page['title']);
        $this->assertArrayHasKey('variables', $page);
        $this->assertEmpty($page['variables']);
    }

    public function testFaqs()
    {
        $this->manageTest->cleanser();

        $pageController1 = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], []);
        $page = $pageController1->faqs();

        $this->assertArrayHasKey('template', $page);
        $this->assertEquals('faqs.html.php', $page['template']);
        $this->assertArrayHasKey('title', $page);
        $this->assertEquals('FAQs', $page['title']);
        $this->assertArrayHasKey('variables', $page);
        $this->assertEmpty($page['variables']);
    }

    public function testApply()
    {
        $this->manageTest->cleanser();
        $_FILES = [
            'cv' => [
                'name' => 'test.pdf',
                'type' => 'application/pdf',
                'tmp_name' => '/path/to/test.pdf',
                'error' => 0,
                'size' => 12345
            ]
        ];

        $post = ['submit' => true,
            'name' => 'Kolade Dara',
            'email' => 'koladedara@gmail.com',
            'details' => 'N/A',
            'jobId' =>  '1'
        ];


        $pageController = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], $post);
        $page = $pageController->apply();

        $this->assertTrue(is_array($page));
        $this->assertEquals($page['template'], "complete.html.php");

        $this->assertFileExists('cvs/test.pdf');


    }
    public function testJobApply(){
        $this->manageTest->cleanser();


        $id = $this->manageTest->addJob();

        $pageController1 = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, ['id'=>$id], []);
        $page = $pageController1->apply();

        $this->assertTrue(is_array($page));
        $this->assertEquals($page['template'], "apply.html.php");
    }

    public function testJob()
    {
        $this->manageTest->cleanser();

        $data1 = [
            'name' => 'IT',
        ];
        $id = $this->manageTest->addCat($data1);

        $data = [
            'location' => 'Northampton',
            'categoryId' => $id
        ];

        $this->manageTest->addJob($data);

        $pageController1 = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, ['categoryId' => $id], []);
        $page = $pageController1->job();

        $this->assertTrue(is_array($page));
        $this->assertEquals($page['template'], 'job.html.php');
        $this->assertEquals($page['title'], 'Job');
        $this->assertArrayHasKey('jobs', $page['variables']);
        $this->assertArrayHasKey('catName', $page['variables']);
        $this->assertTrue($data1['name'] == $page['variables']['catName']);
        $this->assertTrue(1 == count($page['variables']['jobs']));
    }

    public function testContact()
    {
        $this->manageTest->cleanser();

        $post = ['submit' => true,
            'name' => 'Kolade Dara',
            'telephone' => '1234567890',
            'email' => 'koladedara@job.com',
            'enquiry' => 'This is a test enquiry'];

        $pageController = new PageController($this->databaseJobs, $this->databaseCategories,
            $this->databaseApplicants, $this->databaseAdmin, $this->databaseContact, [], $post);
        $page = $pageController->contact();

        $this->assertTrue(is_array($page));
        $this->assertEquals($page['variables']['me'], "Complaint Received");
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
