<?php

namespace CSY2038\Controllers;

use CSY\DatabaseTable;
use CSY\MyPDO;

class PageController
{

    private $pdo;

    private $dbJobs;
    private $dbCat;
    private $dbApp;
    private $dbContact;

    public function __construct(DatabaseTable $dbJobs, DatabaseTable $dbCat,
                                DatabaseTable $dbApp, DatabaseTable $dbAdmin,
                                DatabaseTable $dbContact, array $get, array $post)
    {
        $myDb = new MyPDO();

        $this->pdo = $myDb->db();
        $this->dbJobs = $dbJobs;
        $this->dbCat = $dbCat;
        $this->dbApp = $dbApp;
        $this->dbContact = $dbContact;
        $this->get = $get;
        $this->post = $post;
    }

    public function home()
    {
        $values = [];
        $statement = 'SELECT j.*, c.id AS catID FROM job
        j LEFT JOIN category c ON c.id = j.categoryId';

        if (isset($this->get
                ['job_location']) && $this->get
            ['job_location'] != "All") {
            $statement .= ' WHERE j.location =:location AND j.archive IS NULL  ORDER BY j.closingDate ASC LIMIT 10';
            $values = [
                'location' => $this->get
                ['job_location']
            ];
        } else {
            $statement .= ' WHERE j.archive IS NULL ORDER BY j.closingDate ASC LIMIT 10';
        }
        $jobs = $this->dbJobs->customFind($statement, $values);
        $criteria = 'SELECT DISTINCT location FROM job';
        $locations = $this->dbJobs->customFind($criteria, []);
        return ['template' => 'index.html.php', 'title' => 'Home', 'variables' =>
            ["jobs" => $jobs, "locations" => $locations]];

    }

    public function job()
    {

        $jobs = $this->dbJobs->find("categoryId", $this->get
        ['categoryId']);
        $categories = $this->dbCat->find("id", $this->get
        ['categoryId'])[0];
        return [
            'template' => 'job.html.php',
            'title' => 'Job',
            'variables' =>
                ["jobs" => $jobs,
                    "catName" => $categories["name"]
                ]
        ];
    }

    public function about()
    {
        return ['template' => 'about.html.php', 'title' => 'About', 'variables' => []];
    }

    public function contact()
    {
        $me ='';
        if (isset($this->post['submit'])) {
            $contact = [
                'name' => $this->post['name'],
                'telephone' => $this->post['telephone'],
                'email' => $this->post['email'],
                'enquiry' => $this->post['enquiry']
            ];

            $this->dbContact->insert($contact);

            $me = 'Complaint Received';
        }
        return ['template' => 'contact.html.php', 'title' => 'Contact', 'variables' => ['me' => $me]];
    }

    public function faqs()
    {
        return ['template' => 'faqs.html.php', 'title' => 'FAQs', 'variables' => []];
    }

    public function apply()
    {
        if (isset($this->post['submit'])) {
            $fileName = '';
            if (isset($_FILES['cv'])) {
                if ($_FILES['cv']['error'] == 0) {

                    $parts = explode('.', $_FILES['cv']['name']);

                    $extension = end($parts);

                    $fileName = uniqid() . '.' . $extension;

                    move_uploaded_file($_FILES['cv']['tmp_name'], 'cvs/' . $fileName);
                }
            }
            $applicants = [
                'name' => $this->post['name'],
                'email' => $this->post['email'],
                'details' => $this->post['details'],
                'jobId' => $this->post['jobId'],
                'cv' => $fileName
            ];
            $applicants = $this->dbApp->insert($applicants);
            return ['template' => 'complete.html.php', 'title' => 'Apply', 'variables' => []];
        } else {
            $job = $this->dbJobs->find("id", $this->get['id'])[0];
            return ['template' => 'apply.html.php', 'title' => 'Apply', 'variables' => ["job" => $job]];
        }

    }


}
