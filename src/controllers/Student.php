<?php

namespace controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Student
{
    public function index(Application $app, Request $request)
    {
        $students = $this->getStudents($app);

        return $app['twig']->render('students/index.twig', [
            'title' => 'Students',
            'data' => $students
        ]);
    }

    public function form(Application $app, Request $request, $id = null)
    {
        $now = new \DateTime('now');
        $id = (is_null($id)) ? $request->get("id") : $id;
        $title = ($id ? 'Update' : 'Create') . ' student';

        if ( ! is_null($id)) {
            $object = Tools::findOneBy($app, "\Student", ["id" => $id]);
        } else {
            $object = new \models\Student;
            $object->setViewStatus(5);
            $object->setCreatedAt($now);
        }

        $form = $app['form.factory']->createBuilder(\forms\types\Student::class, $object)->getForm();
        $form->handleRequest($request);

        if("POST" == $request->getMethod()) {
        }

        $view = array(
            "id" => $id,
            "title" => $title,
            'form' => $form->createView()
        );

        return $app['twig']->render("students/form.twig", $view);
    }

    function getStudents(Application $app)
    {
        $sql = "SELECT first_name, last_name, id FROM student WHERE view_status = :status ORDER BY first_name ASC";
        $stmt = $app['db']->prepare($sql);
        $stmt->bindValue("status", 5);
        $stmt->execute();
        $results = $stmt->fetchAll();

        return $results;
    }
}
