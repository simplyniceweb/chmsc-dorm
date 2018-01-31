<?php

namespace controllers;

use controllers\Tools;
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
        $error = 0;
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
            if ($form->isValid()) {
                $data = $form->getData();

                // unique student id
                $studentId = $data->getStudentId();
                $uniqueStudentId = $this->getUniqueStudentId($app, $studentId);
                if (count($uniqueStudentId) > 0) {
                    $error++;
                    $app['session']->getFlashBag()->add('errors', 'Student ID should be unique.');
                }

                // if no error proceed
                if (!$error) {
                    $data->setViewStatus(5);
                    $data->setCreatedAt($now);
                    $data->setModifiedAt($now);

                    $app['orm.em']->persist($data);
                    $app['orm.em']->flush();

                    $app['session']->getFlashBag()->add('success', $title . ' successfully.');
                    $url = $app['url_generator']->generate('students.update', ['id' => $data->getId()]);
                    return $app->redirect($url);
                }
            }
        }

        $view = array(
            "id" => $id,
            "title" => $title,
            'form' => $form->createView()
        );

        return $app['twig']->render("students/form.twig", $view);
    }

    public function delete(Application $app, Request $request, $id = null)
    {
        $title = "Student";
        $object = Tools::findOneBy($app, "\Student", ["id" => $id]);
        if (!is_object($object)) {
            $app['session']->getFlashBag()->add('errors', "$title with $id does not exist.");
            $url = $app['url_generator']->generate('students');
        } else {
            $object->setViewStatus(1);
            $object->setModifiedAt(new \DateTime('now'));
            $app['orm.em']->persist($object);
            $app['orm.em']->flush();

            $app['session']->getFlashBag()->add('success', $title . ' deleted successfully.');
            $url = $app['url_generator']->generate('students');
        }

        return $app->redirect($url);
    }

    function getUniqueStudentId(Application $app, $studentId)
    {
        $sql = "SELECT id 
                FROM student 
                WHERE student_id = :student_id 
                AND view_status = :status";

        $stmt = $app['db']->prepare($sql);
        $stmt->bindValue("status", 5);
        $stmt->bindValue("student_id", $studentId);
        $stmt->execute();
        $results = $stmt->fetchAll();

        return $results;
    }

    function getStudents(Application $app)
    {
        $sql = "SELECT  id, student_id, first_name, last_name FROM student WHERE view_status = :status ORDER BY first_name ASC";
        $stmt = $app['db']->prepare($sql);
        $stmt->bindValue("status", 5);
        $stmt->execute();
        $results = $stmt->fetchAll();

        return $results;
    }
}
