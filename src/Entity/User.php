<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

// и ты мне еще про магию говорил. а как работает вот это ты не узнавал?
// магия еще пожесче. да еще и писать код в пхпдоках неудобно, пхпшторм не подсвечивает ошибки
// и не форматирует нормально
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    //тут лишний пробел. прийми один стиль и пиши в нем


    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    // пароль кроме как захешированный никаким не может быть. бесполезный коммент, удаляем
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Company", mappedBy="owner", cascade={"persist", "remove"})
     */
    private $companyOwned;


    /**
     * @ORM\Column(type="string", length=150)
     */
    private $userType;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Person", mappedBy="user", cascade={"persist", "remove"})
     */
    private $person;

// а как айди юзера может быть нуллабл?
    public function getId(): ?int
    {
        return $this->id;
    }
// гктткры и сеттеры. раздувают модель нереально. +1 повод не любить доктрину
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {// по моему тут не нужно приводить тип, тайпхинт на возвращаемое значение сделает это за тебя
        // ну есть не писать declare strict types. можешь уточнить этот момент
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {// а если я несколько раз позову этот метод? оно ж мне несколько раз напишет в массив ролей вот эту роль
        // методы гет не должны что то менять, для этого есть сет
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCompanyOwned(): ?Company
    {
        return $this->companyOwned;
    }

    public function setCompanyOwned(?Company $companyOwned): self
    {
        $this->companyOwned = $companyOwned;

        // set (or unset) the owning side of the relation if necessary
        $newOwner = null === $companyOwned ? null : $this;
        if ($companyOwned->getOwner() !== $newOwner) {
            $companyOwned->setOwner($newOwner);
        }

        return $this;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): self
    {

        $this->userType = $userType;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

}
