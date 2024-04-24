<?php

namespace App\Entity;

use App\Repository\ServiciosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiciosRepository::class)]
class Servicios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_servicio')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre_servicio = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $descripcion_servicio = null;

    #[ORM\Column]
    private ?int $duracion_servicio = null;

    #[ORM\Column]
    private ?float $precio_servicio = null;

    #[ORM\ManyToOne(targetEntity: Centros::class, inversedBy: 'servicios_centro')]
    #[ORM\JoinColumn(name: "id_centro", referencedColumnName: "id_centro", nullable: false)]
    private ?Centros $id_centro = null;

    #[ORM\OneToMany(mappedBy: 'id_servicio', targetEntity: Citas::class)]
    private Collection $servicio_cita;

    public function __construct()
    {
        $this->servicio_cita = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreServicio(): ?string
    {
        return $this->nombre_servicio;
    }

    public function setNombreServicio(string $nombre_servicio): static
    {
        $this->nombre_servicio = $nombre_servicio;

        return $this;
    }

    public function getDescripcionServicio(): ?string
    {
        return $this->descripcion_servicio;
    }

    public function setDescripcionServicio(?string $descripcion_servicio): static
    {
        $this->descripcion_servicio = $descripcion_servicio;

        return $this;
    }

    public function getDuracionServicio(): ?int
    {
        return $this->duracion_servicio;
    }

    public function setDuracionServicio(int $duracion_servicio): static
    {
        $this->duracion_servicio = $duracion_servicio;

        return $this;
    }

    public function getPrecioServicio(): ?float
    {
        return $this->precio_servicio;
    }

    public function setPrecioServicio(float $precio_servicio): static
    {
        $this->precio_servicio = $precio_servicio;

        return $this;
    }

    public function getServiciosCentro(): ?Centros
    {
        return $this->id_centro;
    }

    public function setServiciosCentro(?Centros $id_centro): static
    {
        $this->id_centro = $id_centro;

        return $this;
    }

    /**
     * @return Collection<int, Citas>
     */
    public function getServicioCita(): Collection
    {
        return $this->servicio_cita;
    }

    public function addServicioCitum(Citas $servicioCitum): static
    {
        if (!$this->servicio_cita->contains($servicioCitum)) {
            $this->servicio_cita->add($servicioCitum);
            $servicioCitum->setServicioCita($this);
        }

        return $this;
    }

    public function removeServicioCitum(Citas $servicioCitum): static
    {
        if ($this->servicio_cita->removeElement($servicioCitum)) {
            // set the owning side to null (unless already changed)
            if ($servicioCitum->getServicioCita() === $this) {
                $servicioCitum->setServicioCita(null);
            }
        }

        return $this;
    }
}
