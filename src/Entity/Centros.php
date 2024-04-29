<?php

namespace App\Entity;

use App\Repository\CentrosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: CentrosRepository::class)]
#[UniqueEntity(fields: ['email_centro'], message: 'There is already an account with this email_centro')]
class Centros implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_centro")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre_centro = null;

    #[ORM\Column(length: 150, unique: true)]
    private ?string $email_centro = null;

    #[ORM\Column(length: 200)]
    private ?string $direccion_centro = null;

    #[ORM\Column(length: 20)]
    private ?string $telefono_centro = null;

    #[ORM\Column(length: 100)]
    private ?string $password_centro = null;

    #[ORM\OneToMany(mappedBy: 'id_centro', targetEntity: Clientes::class)]
    private Collection $clientes_centro;

    #[ORM\OneToMany(mappedBy: 'id_centro', targetEntity: Empleados::class)]
    private Collection $empleados_centro;

    #[ORM\OneToMany(mappedBy: 'id_centro', targetEntity: Citas::class)]
    private Collection $centro_cita;

    #[ORM\Column]
    private array $roles = [];


    public function __construct()
    {
        $this->clientes_centro = new ArrayCollection();
        $this->empleados_centro = new ArrayCollection();
        $this->centro_cita = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreCentro(): ?string
    {
        return $this->nombre_centro;
    }

    public function setNombreCentro(string $nombre_centro): static
    {
        $this->nombre_centro = $nombre_centro;

        return $this;
    }

    public function getEmailCentro(): ?string
    {
        return $this->email_centro;
    }

    public function setEmailCentro(string $email_centro): static
    {
        $this->email_centro = $email_centro;

        return $this;
    }

    public function getDireccionCentro(): ?string
    {
        return $this->direccion_centro;
    }

    public function setDireccionCentro(string $direccion_centro): static
    {
        $this->direccion_centro = $direccion_centro;

        return $this;
    }

    public function getTelefonoCentro(): ?string
    {
        return $this->telefono_centro;
    }

    public function setTelefonoCentro(string $telefono_centro): static
    {
        $this->telefono_centro = $telefono_centro;

        return $this;
    }


    /**
     * @return Collection<int, Clientes>
     */
    public function getClientesCentro(): Collection
    {
        return $this->clientes_centro;
    }

    public function addClientesCentro(Clientes $clientesCentro): static
    {
        if (!$this->clientes_centro->contains($clientesCentro)) {
            $this->clientes_centro->add($clientesCentro);
            $clientesCentro->setIdCentroCliente($this);
        }

        return $this;
    }

    public function removeClientesCentro(Clientes $clientesCentro): static
    {
        if ($this->clientes_centro->removeElement($clientesCentro)) {
            // set the owning side to null (unless already changed)
            if ($clientesCentro->getIdCentroCliente() === $this) {
                $clientesCentro->setIdCentroCliente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Empleados>
     */
    public function getEmpleadosCentro(): Collection
    {
        return $this->empleados_centro;
    }

    public function addEmpleadosCentro(Empleados $empleadosCentro): static
    {
        if (!$this->empleados_centro->contains($empleadosCentro)) {
            $this->empleados_centro->add($empleadosCentro);
            $empleadosCentro->setEmpleadosCentro($this);
        }

        return $this;
    }

    public function removeEmpleadosCentro(Empleados $empleadosCentro): static
    {
        if ($this->empleados_centro->removeElement($empleadosCentro)) {
            // set the owning side to null (unless already changed)
            if ($empleadosCentro->getEmpleadosCentro() === $this) {
                $empleadosCentro->setEmpleadosCentro(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Citas>
     */
    public function getCentroCita(): Collection
    {
        return $this->centro_cita;
    }

    public function addCentroCitum(Citas $centroCitum): static
    {
        if (!$this->centro_cita->contains($centroCitum)) {
            $this->centro_cita->add($centroCitum);
            $centroCitum->setCentroCita($this);
        }

        return $this;
    }

    public function removeCentroCitum(Citas $centroCitum): static
    {
        if ($this->centro_cita->removeElement($centroCitum)) {
            // set the owning side to null (unless already changed)
            if ($centroCitum->getCentroCita() === $this) {
                $centroCitum->setCentroCita(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email_centro;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_CENTRO';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password_centro;
    }

    public function setPassword(string $password_centro): static
    {
        $this->password_centro = $password_centro;

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
