import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { HeaderComponent } from '../header/header.component';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
} from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Citas } from '../../models/citas.interface';
import { HttpClient } from '@angular/common/http';
import { CentrosCitasService } from '../../services/centros-citas.service';
import { CenterBarComponent } from '../center-bar/center-bar.component';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatInputModule } from '@angular/material/input';
import { MatNativeDateModule } from '@angular/material/core';
import { MatDatetimepickerModule } from '@mat-datetimepicker/core';
import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';
import { MatNativeDatetimeModule } from '@mat-datetimepicker/core';

@Component({
  selector: 'app-centros-citas',
  standalone: true,
  imports: [
    CommonModule,
    HeaderComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
    CenterBarComponent,
    MatDatepickerModule,
    MatInputModule,
    MatNativeDateModule,
    MatNativeDatetimeModule,
    MatDatetimepickerModule,
    NgxMaterialTimepickerModule,
  ],
  templateUrl: './centros-citas.component.html',
  styleUrl: './centros-citas.component.css',
})
export class CentrosCitasComponent {
  citas: Citas[] = [];
  editMode: boolean = false;
  citaForm: FormGroup;
  citaToEdit: any;
  nombreCliente: string | null = '';
  apellidosCliente: string | null = '';
  nombreCentro: string | null = '';
  servicios: any[] = [];
  empleados: any[] = [];

  constructor(
    private centrosCitasService: CentrosCitasService,
    private http: HttpClient,
    private fb: FormBuilder
  ) {
    this.citaForm = this.fb.group({
      servicio: '',
      cliente: '',
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
    this.nombreCentro = localStorage.getItem('nombre_centro');
  }

  loadCitas(): void {
    if (this.citas) {
      const idCentro = localStorage.getItem('idCentro');
      if (idCentro !== null) {
        this.centrosCitasService.getCitasById(idCentro).subscribe(
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
        const citaId = this.citaToEdit.id;

        this.http
          .patch(`http://localhost:8000/citas/${citaId}`, {
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
