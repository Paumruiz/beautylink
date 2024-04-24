<?php

namespace App\Entity;

use App\Repository\CitasRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitasRepository::class)]
class Citas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_cita')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_cita = null;

    #[ORM\ManyToOne(targetEntity: Servicios::class, inversedBy: 'servicio_cita')]
    #[ORM\JoinColumn(name: "id_servicio", referencedColumnName: "id_servicio", nullable: false)]
    private ?Servicios $id_servicio = null;

    #[ORM\ManyToOne(targetEntity: Clientes::class, inversedBy: 'cliente_cita')]
    #[ORM\JoinColumn(name: "id_cliente", referencedColumnName: "id_cliente", nullable: false)]
    private ?Clientes $id_cliente = null;

    #[ORM\ManyToOne(targetEntity: Empleados::class, inversedBy: 'empleado_cita')]
    #[ORM\JoinColumn(name: "id_empleado", referencedColumnName: "id_empleado", nullable: false)]
    private ?Empleados $id_empleado = null;

    #[ORM\ManyToOne(targetEntity: Centros::class, inversedBy: 'centro_cita')]
    #[ORM\JoinColumn(name: "id_centro", referencedColumnName: "id_centro", nullable: false)]
    private ?Centros $id_centro = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaCita(): ?\DateTimeInterface
    {
        return $this->fecha_cita;
    }

    public function setFechaCita(\DateTimeInterface $fecha_cita): static
    {
        $this->fecha_cita = $fecha_cita;

        return $this;
    }

    public function getServicioCita(): ?Servicios
    {
        return $this->id_servicio;
    }

    public function setServicioCita(?Servicios $id_servicio): static
    {
        $this->id_servicio = $id_servicio;

        return $this;
    }

    public function getClienteCita(): ?Clientes
    {
        return $this->id_cliente;
    }

    public function setClienteCita(?Clientes $id_cliente): static
    {
        $this->id_cliente = $id_cliente;

        return $this;
    }

    public function getEmpleadoCita(): ?Empleados
    {
        return $this->id_empleado;
    }

    public function setEmpleadoCita(?Empleados $id_empleado): static
    {
        $this->id_empleado = $id_empleado;

        return $this;
    }

    public function getCentroCita(): ?Centros
    {
        return $this->id_centro;
    }

    public function setCentroCita(?Centros $centro_cita): static
    {
        $this->id_centro = $centro_cita;

        return $this;
    }
}
