<?php

namespace App\Entity;

use App\Repository\ClientesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ClientesRepository::class)]
class Clientes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_cliente")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre_cliente = null;

    #[ORM\Column(length: 200)]
    private ?string $apellidos_cliente = null;

    #[ORM\Column(length: 150, unique: true)]
    private ?string $email_cliente = null;

    #[ORM\Column(length: 20)]
    private ?string $telefono_cliente = null;

    #[ORM\Column(length: 100)]
    private ?string $password_cliente = null;

    #[ORM\ManyToOne(targetEntity: Centros::class, inversedBy: 'clientes_centro')]
    #[ORM\JoinColumn(name: "id_centro", referencedColumnName: "id_centro", nullable: false)]
    private ?Centros $id_centro = null;

    #[ORM\OneToMany(mappedBy: 'id_cliente', targetEntity: Citas::class)]
    private Collection $cliente_cita;

    #[ORM\Column]
    private array $roles = [];

    public function __construct()
    {
        $this->cliente_cita = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreCliente(): ?string
    {
        return $this->nombre_cliente;
    }

    public function setNombreCliente(string $nombre_cliente): static
    {
        $this->nombre_cliente = $nombre_cliente;

        return $this;
    }

    public function getApellidosCliente(): ?string
    {
        return $this->apellidos_cliente;
    }

    public function setApellidosCliente(string $apellidos_cliente): static
    {
        $this->apellidos_cliente = $apellidos_cliente;

        return $this;
    }

    public function getEmailCliente(): ?string
    {
        return $this->email_cliente;
    }

    public function setEmailCliente(string $email_cliente): static
    {
        $this->email_cliente = $email_cliente;

        return $this;
    }

    public function getTelefonoCliente(): ?string
    {
        return $this->telefono_cliente;
    }

    public function setTelefonoCliente(string $telefono_cliente): static
    {
        $this->telefono_cliente = $telefono_cliente;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password_cliente;
    }

    public function setPassword(string $password_cliente): static
    {
        $this->password_cliente = $password_cliente;

        return $this;
    }

    public function getIdCentroCliente(): ?centros
    {
        return $this->id_centro;
    }

    public function setIdCentroCliente(?centros $id_centro): static
    {
        $this->id_centro = $id_centro;

        return $this;
    }

    /**
     * @return Collection<int, Citas>
     */
    public function getClienteCita(): Collection
    {
        return $this->cliente_cita;
    }

    public function addClienteCitum(Citas $clienteCitum): static
    {
        if (!$this->cliente_cita->contains($clienteCitum)) {
            $this->cliente_cita->add($clienteCitum);
            $clienteCitum->setClienteCita($this);
        }

        return $this;
    }

    public function removeClienteCitum(Citas $clienteCitum): static
    {
        if ($this->cliente_cita->removeElement($clienteCitum)) {
            // set the owning side to null (unless already changed)
            if ($clienteCitum->getClienteCita() === $this) {
                $clienteCitum->setClienteCita(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email_cliente;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_CLIENTE';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
