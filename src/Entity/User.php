<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Merci d'indiquer un nom !")
     * @Assert\Length(min=2, max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $lastname;

    /**
     * @Assert\NotBlank(message="Merci d'indiquer un prénom !")
     * @Assert\Length(min=2, max=100)
     * @ORM\Column(type="string", length=100)
     */
    private $firstname;

    /**
     * @Assert\NotBlank(message="Merci d'indiquer un surnom !")
     * @Assert\Length(min=2, max=100)
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAdmin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, inversedBy="users")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="organizer")
     */
    private $eventsOrganizer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureFileName;

    

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->eventsOrganizer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
//        $roles = $this->roles;
//        // guarantee every user at least has ROLE_USER
//        $roles[] = 'ROLE_USER';
//
//        return array_unique($roles);

        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        $this->events->removeElement($event);

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventsOrganizer(): Collection
    {
        return $this->eventsOrganizer;
    }

    public function addEventsOrganizer(Event $eventsOrganizer): self
    {
        if (!$this->eventsOrganizer->contains($eventsOrganizer)) {
            $this->eventsOrganizer[] = $eventsOrganizer;
            $eventsOrganizer->setOrganizer($this);
        }

        return $this;
    }

    public function removeEventsOrganizer(Event $eventsOrganizer): self
    {
        if ($this->eventsOrganizer->removeElement($eventsOrganizer)) {
            // set the owning side to null (unless already changed)
            if ($eventsOrganizer->getOrganizer() === $this) {
                $eventsOrganizer->setOrganizer(null);
            }
        }

        return $this;
    }

//
//    /**
//     * @ORM\Column(type="datetime", nullable=true)
//     * @var \DateTime
//     */
//    private $passwordRequestedAt;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(type="string", length=255, nullable=true)
//     */
//    private $token;
//
//    /*
//     * Get passwordRequestedAt
//     */
//    public function getPasswordRequestedAt()
//    {
//        return $this->passwordRequestedAt;
//    }
//
//    /*
//     * Set passwordRequestedAt
//     */
//    public function setPasswordRequestedAt($passwordRequestedAt)
//    {
//        $this->passwordRequestedAt = $passwordRequestedAt;
//        return $this;
//    }
//
//    /*
//     * Get token
//     */
//    public function getToken()
//    {
//        return $this->token;
//    }
//
//    /*
//     * Set token
//     */
//    public function setToken($token)
//    {
//        $this->token = $token;
//        return $this;
//    }

public function getPictureFileName(): ?string
{
    return $this->pictureFileName;
}

public function setPictureFileName(?string $pictureFileName): self
{
    $this->pictureFileName = $pictureFileName;

    return $this;
}

}
