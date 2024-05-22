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
import { ClientBarComponent } from '../client-bar/client-bar.component';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatInputModule } from '@angular/material/input';
import { MatNativeDateModule } from '@angular/material/core';
import { MatDatetimepickerModule } from '@mat-datetimepicker/core';
import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';
import { MatNativeDatetimeModule } from '@mat-datetimepicker/core';

@Component({
  selector: 'app-client-dashboard',
  standalone: true,
  imports: [
    CommonModule,
    SidebarHeaderComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
    ClientBarComponent,
    MatDatepickerModule,
    MatInputModule,
    MatNativeDateModule,
    MatNativeDatetimeModule,
    MatDatetimepickerModule,
    NgxMaterialTimepickerModule,
  ],
  templateUrl: './client-dashboard.component.html',
  styleUrl: './client-dashboard.component.css',
})
export class ClientDashboardComponent {
  citas: Citas[] = [];
  editMode: boolean = false;
  citaForm: FormGroup;
  citaToEdit: any;
  nombreCliente: string | null = '';
  apellidosCliente: string | null = '';
  servicios: any[] = [];
  empleados: any[] = [];

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

    this.cargarServicios();
    this.cargarEmpleados();
  }

  idCentro = localStorage.getItem('idCentro');
  private empleadosUrl = `http://localhost:8000/empleados/${this.idCentro}`;
  private serviciosUrl = 'http://localhost:8000/servicios';

  cargarServicios(): void {
    this.http.get<any[]>(this.serviciosUrl).subscribe({
      next: (servicios) => (this.servicios = servicios),
      error: (error) => console.error('Error al cargar servicios', error),
    });
  }

  cargarEmpleados(): void {
    this.http.get<any[]>(this.empleadosUrl).subscribe({
      next: (empleados) => (this.empleados = empleados),
      error: (error) => console.error('Error al cargar empleados', error),
    });
  }

  ngOnInit(): void {
    this.loadCitas();
    this.initForm();
    this.nombreCliente = localStorage.getItem('nombre_cliente');
    this.apellidosCliente = localStorage.getItem('apellidos_cliente');
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
      let fechaControl = this.citaForm.get('fecha');
      let empleadoControl = this.citaForm.get('empleado');
      let servicioControl = this.citaForm.get('servicio');

      if (fechaControl && empleadoControl && servicioControl) {
        let fechaHora: Date = fechaControl.value;
        let fechaHoraUTC = new Date(
          Date.UTC(
            fechaHora.getFullYear(),
            fechaHora.getMonth(),
            fechaHora.getDate(),
            fechaHora.getHours(),
            fechaHora.getMinutes()
          )
        );

        this.citaForm.patchValue({ fecha: fechaHoraUTC });

        this.citaForm.patchValue({ empleado: empleadoControl.value.id });
        this.citaForm.patchValue({ servicio: servicioControl.value.id });

        const citaData = this.citaForm.value;
        this.http
          .patch(`http://localhost:8000/citas/${this.citaToEdit.id}`, {
            cita: citaData,
          })
          .subscribe({
            next: (response) => {
              console.log('Cita actualizada correctamente', response);
              this.loadCitas();
              this.cancelEditMode();
            },
            error: (error) =>
              console.error('Error al actualizar la cita', error),
          });
      }
    }
  }

  cancelEditMode(): void {
    this.editMode = false;
    this.citaToEdit = null;
    this.citaForm.reset();
  }

  eliminarCita(id: number) {
    const url = `http://localhost:8000/citas/${id}`;
    if (confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
      this.http.delete(url).subscribe({
        next: (response) => {
          console.log('Cita eliminada correctamente', response);
          this.loadCitas();
        },
        error: (error) => console.error('Error al eliminar la cita', error),
      });
    }
  }
}
