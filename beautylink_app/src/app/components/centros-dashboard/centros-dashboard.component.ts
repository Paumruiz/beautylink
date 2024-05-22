import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
} from '@angular/forms';
import { HeaderComponent } from '../header/header.component';
import { RouterLink } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Citas } from '../../models/citas.interface';
import {
  CentrosDashboardService,
  CitasResponse,
} from '../../services/centros-dashboard.service';
import { CenterBarComponent } from '../center-bar/center-bar.component';

@Component({
  selector: 'app-centros-dashboard',
  standalone: true,
  imports: [
    CommonModule,
    HeaderComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
    CenterBarComponent,
  ],
  templateUrl: './centros-dashboard.component.html',
  styleUrl: './centros-dashboard.component.css',
})
export class CentrosDashboardComponent {
  private baseUrl = 'http://localhost:8000';
  citas: Citas[] = [];
  num_citas: number = 0;
  nombreCentro: string | null = '';

  constructor(
    private centrosDashboardService: CentrosDashboardService,
    private http: HttpClient
  ) {
    this.cargarTotalIngresos();
    this.nombreCentro = localStorage.getItem('nombre_centro');
  }

  cargarTotalIngresos() {
    const ingresosData = localStorage.getItem('totalIngresosData');
    if (ingresosData) {
      const ingresosObj = JSON.parse(ingresosData);
      const fechaExpiracion = new Date(ingresosObj.fechaExpiracion);
      if (fechaExpiracion > new Date()) {
        this.totalIngresos = ingresosObj.total;
      } else {
        localStorage.removeItem('totalIngresosData');
      }
    }
  }

  ngOnInit(): void {
    this.loadCitas();
  }

  loadCitas(): void {
    if (this.citas) {
      const idCentro = localStorage.getItem('idCentro');
      if (idCentro !== null) {
        this.centrosDashboardService.getCitasById(idCentro).subscribe(
          (data: CitasResponse) => {
            this.citas = data.citas;
            this.num_citas = data.num_citas;
          },
          (error) => {
            console.error('Error al obtener las citas', error);
          }
        );
      }
    }
  }

  totalIngresos: number = 0;

  eliminarCita(id: number) {
    const cita = this.citas.find((c) => c.id === id);
    if (!cita) {
      console.error('Cita no encontrada');
      return;
    }

    const url = `http://localhost:8000/citas/${id}`;
    if (confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
      this.http.delete(url).subscribe({
        next: (response) => {
          console.log('Cita eliminada correctamente', response);
          this.totalIngresos += cita.precio_servicio;
          const unaSemanaMasTarde = new Date();
          unaSemanaMasTarde.setDate(unaSemanaMasTarde.getDate() + 7);
          const ingresosData = {
            total: this.totalIngresos,
            fechaExpiracion: unaSemanaMasTarde.toISOString(),
          };
          localStorage.setItem(
            'totalIngresosData',
            JSON.stringify(ingresosData)
          );
          this.loadCitas();
        },
        error: (error) => console.error('Error al eliminar la cita', error),
      });
    }
  }
}
