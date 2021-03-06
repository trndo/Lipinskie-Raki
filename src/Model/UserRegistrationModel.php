<?php


namespace App\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;


class UserRegistrationModel
{
    /**
     * @Assert\NotBlank(message="Пожалуйста, введите email!")
     * @Assert\Email()
     * @UniqueUser()
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Введите пароль")
     * @Assert\Length(min=5, minMessage="Этот пароль короткий")
     */
    private $password;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $surname;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $phone;

    /**
     * @var string|null
     */
    private $friendUniqueId;

    /**
     * @return string|null
     */
    public function getFriendUniqueId(): ?string
    {
        return $this->friendUniqueId;
    }

    /**
     * @param string|null $friendUniqueId
     * @return UserRegistrationModel
     */
    public function setFriendUniqueId(?string $friendUniqueId): UserRegistrationModel
    {
        $this->friendUniqueId = $friendUniqueId;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname($surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }


}