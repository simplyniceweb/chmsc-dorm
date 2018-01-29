<?php

namespace models;

/**
 * @Entity @Table(name="student")
 **/
class Student
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    public $id;
    
    /** @Column(type="string") **/
    public $student_id;
    
    /** @Column(type="string") **/
    public $first_name;
    
    /** @Column(type="string") **/
    public $middle_name;
    
    /** @Column(type="string") **/
    public $last_name;
    
    /** @Column(type="string") **/
    public $phone;
    
    /** @Column(type="string") **/
    public $email;
    
    /** @Column(type="text") **/
    public $address;
    
    /** @Column(type="integer", nullable=true) **/
    protected $view_status;
    
    /** @Column(type="datetime", nullable=true) **/
    protected $created_at;

    /** @Column(type="datetime", nullable=true) **/
    protected $modified_at;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set studentId.
     *
     * @param string $studentId
     *
     * @return Student
     */
    public function setStudentId($studentId)
    {
        $this->student_id = $studentId;

        return $this;
    }

    /**
     * Get studentId.
     *
     * @return string
     */
    public function getStudentId()
    {
        return $this->student_id;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return Student
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set middleName.
     *
     * @param string $middleName
     *
     * @return Student
     */
    public function setMiddleName($middleName)
    {
        $this->middle_name = $middleName;

        return $this;
    }

    /**
     * Get middleName.
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Student
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return Student
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set viewStatus.
     *
     * @param int|null $viewStatus
     *
     * @return Student
     */
    public function setViewStatus($viewStatus = null)
    {
        $this->view_status = $viewStatus;

        return $this;
    }

    /**
     * Get viewStatus.
     *
     * @return int|null
     */
    public function getViewStatus()
    {
        return $this->view_status;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return Student
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set modifiedAt.
     *
     * @param \DateTime|null $modifiedAt
     *
     * @return Student
     */
    public function setModifiedAt($modifiedAt = null)
    {
        $this->modified_at = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt.
     *
     * @return \DateTime|null
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }
}
