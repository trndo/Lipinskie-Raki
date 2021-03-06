<?php


namespace App\Model;


use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserModel
{
    /**
     * @Assert\NotBlank(message="*Поле не должно быть пустым")
     * @var string|null
     */
    private $name;

    /**
     * @Assert\NotBlank(message="*Поле не должно быть пустым")
     * @var string|null
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="*Поле не должно быть пустым")
     * @var string|null
     * @UniqueUser()
     */
    private $email;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $coordinates;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return UserModel
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return UserModel
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserModel
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }


    /**
     * @param string|null $address
     * @return UserModel
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }


    /**
     * @param string|null $coordinates
     * @return UserModel
     */
    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }


}