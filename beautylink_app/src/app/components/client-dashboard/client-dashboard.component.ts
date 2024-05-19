import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';
import { Citas } from '../../models/citas.interface';
import { ClientDashboardService } from '../../services/client-dashboard.service';
import { SidebarHeaderComponent } from '../sidebar-header/sidebar-header.component';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { MatDialog } from '@angular/material/dialog';
import { ActualizarCitaComponent } from '../actualizar-cita/actualizar-cita.component';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
} from '@angular/forms';
import { Router, RouterLink } from '@angular/router';

@Component({
  selector: 'app-client-dashboard',
  standalone: true,
  imports: [
    CommonModule,
    SidebarHeaderComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
  ],
  templateUrl: './client-dashboard.component.html',
  styleUrl: './client-dashboard.component.css',
})
export class ClientDashboardComponent {
  citas: Citas[] = [];
  editMode: boolean = false;
  citaForm: FormGroup;
  citaToEdit: any;

  constructor(
    private clientDashboardService: ClientDashboardService,
    private http: HttpClient,
    private dialog: MatDialog,
    private fb: FormBuilder
  ) {
    this.citaForm = this.fb.group({
      servicio: '',
      cliente: localStorage.getItem('idCliente'),
      empleado: '',
      centro: localStorage.getItem('idCentro'),
      fecha: '',
    });
  }

  ngOnInit(): void {
    this.loadCitas();
    this.initForm();
  }

  loadCitas(): void {
    if (this.citas) {
      const idCliente = localStorage.getItem('idCliente');
      if (idCliente !== null) {
        this.clientDashboardService.getCitasById(idCliente).subscribe(
          (data) => {
            this.citas = data;
          },
          (error) => {
            console.error('Error al obtener las citas', error);
          }
        );
      }
    }
  }

  initForm(): void {
    this.citaForm = this.fb.group({
      servicio: '',
      empleado: '',
      fecha: '',
    });
  }

  editarCita(cita: any): void {
    this.editMode = true;
    this.citaToEdit = cita;
    this.citaForm.patchValue({
      servicio: cita.servicio,
      empleado: cita.empleado,
      fecha: cita.fecha,
    });
  }

  actualizarCita(): void {
    if (this.citaForm.valid) {
      const citaData = this.citaForm.value;
      this.http
        .patch(`http://localhost:8000/citas/${this.citaToEdit.id}`, {
          cita: citaData,
        })
        .subscribe({
          next: (response) => {
            console.log('Cita actualizada correctamente', response);
            this.loadCitas(); // Actualiza la lista de citas después de la actualización
            this.cancelEditMode();
          },
          error: (error) => console.error('Error al actualizar la cita', error),
        });
    }
  }

  cancelEditMode(): void {
    this.editMode = false;
    this.citaToEdit = null;
    this.citaForm.reset();
  }

  eliminarCita(id: number) {
    const url = `http://localhost:8000/citas/${id}`; // Asegúrate de que la URL coincida con tu API
    if (confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
      // Opcional: Diálogo de confirmación
      this.http.delete(url).subscribe({
        next: (response) => {
          console.log('Cita eliminada correctamente', response);
          this.loadCitas(); // Actualiza la lista de citas después de la eliminación
        },
        error: (error) => console.error('Error al eliminar la cita', error),
      });
    }
  }
}
