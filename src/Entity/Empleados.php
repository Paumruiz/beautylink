<?php

namespace App\Entity;

use App\Repository\EmpleadosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpleadosRepository::class)]
class Empleados
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_empleado')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre_empleado = null;

    #[ORM\Column(length: 200)]
    private ?string $apellidos_empleado = null;

    #[ORM\Column(length: 50)]
    private ?string $rol_empleado = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $horario_inicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $horario_fin = null;

    #[ORM\ManyToOne(targetEntity: Centros::class, inversedBy: 'empleados_centro')]
    #[ORM\JoinColumn(name: "id_centro", referencedColumnName: "id_centro", nullable: false)]
    private ?Centros $id_centro = null;

    #[ORM\OneToMany(mappedBy: 'id_empleado', targetEntity: Citas::class)]
    private Collection $empleado_cita;

    public function __construct()
    {
        $this->empleado_cita = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreEmpleado(): ?string
    {
        return $this->nombre_empleado;
    }

    public function setNombreEmpleado(string $nombre_empleado): static
    {
        $this->nombre_empleado = $nombre_empleado;

        return $this;
    }

    public function getApellidosEmpleado(): ?string
    {
        return $this->apellidos_empleado;
    }

    public function setApellidosEmpleado(string $apellidos_empleado): static
    {
        $this->apellidos_empleado = $apellidos_empleado;

        return $this;
    }

    public function getRolEmpleado(): ?string
    {
        return $this->rol_empleado;
    }

    public function setRolEmpleado(string $rol_empleado): static
    {
        $this->rol_empleado = $rol_empleado;

        return $this;
    }

    public function getHorarioInicio(): ?\DateTimeInterface
    {
        return $this->horario_inicio;
    }

    public function setHorarioInicio(\DateTimeInterface $horario_inicio): static
    {
        $this->horario_inicio = $horario_inicio;

        return $this;
    }

    public function getHorarioFin(): ?\DateTimeInterface
    {
        return $this->horario_fin;
    }

    public function setHorarioFin(\DateTimeInterface $horario_fin): static
    {
        $this->horario_fin = $horario_fin;

        return $this;
    }

    public function getEmpleadosCentro(): ?Centros
    {
        return $this->id_centro;
    }

    public function setEmpleadosCentro(?Centros $id_centro): static
    {
        $this->id_centro = $id_centro;

        return $this;
    }

    /**
     * @return Collection<int, Citas>
     */
    public function getEmpleadoCita(): Collection
    {
        return $this->empleado_cita;
    }

    public function addEmpleadoCitum(Citas $empleadoCitum): static
    {
        if (!$this->empleado_cita->contains($empleadoCitum)) {
            $this->empleado_cita->add($empleadoCitum);
            $empleadoCitum->setEmpleadoCita($this);
        }

        return $this;
    }

    public function removeEmpleadoCitum(Citas $empleadoCitum): static
    {
        if ($this->empleado_cita->removeElement($empleadoCitum)) {
            // set the owning side to null (unless already changed)
            if ($empleadoCitum->getEmpleadoCita() === $this) {
                $empleadoCitum->setEmpleadoCita(null);
            }
        }

        return $this;
    }
}
