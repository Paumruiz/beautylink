import { NgFor } from '@angular/common';
import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
} from '@angular/forms';
import { CenterBarComponent } from '../center-bar/center-bar.component';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';
import { HeaderComponent } from '../header/header.component';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatInputModule } from '@angular/material/input';
import { MatNativeDateModule } from '@angular/material/core';
import { MatDatetimepickerModule } from '@mat-datetimepicker/core';
import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';
import { MatNativeDatetimeModule } from '@mat-datetimepicker/core';

@Component({
  selector: 'app-insertar-cita-centro',
  standalone: true,
  imports: [
    FormsModule,
    ReactiveFormsModule,
    NgFor,
    CenterBarComponent,
    HeaderComponent,
    MatDatepickerModule,
    MatInputModule,
    MatNativeDateModule,
    MatNativeDatetimeModule,
    MatDatetimepickerModule,
    NgxMaterialTimepickerModule,
  ],
  templateUrl: './insertar-cita-centro.component.html',
  styleUrl: './insertar-cita-centro.component.css',
})
export class InsertarCitaCentroComponent {
  citaForm: FormGroup;
  servicios: any[] = [];
  empleados: any[] = [];
  clientes: any[] = [];
  nombreCentro: string | null = '';

  private registerUrl = 'http://localhost:8000/citas';

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) {
    this.citaForm = this.fb.group({
      servicio: '',
      cliente: '',
      empleado: '',
      centro: localStorage.getItem('idCentro'),
      fecha: '',
    });

    this.cargarClientes();
    this.cargarServicios();
    this.cargarEmpleados();
    this.nombreCentro = localStorage.getItem('nombre_centro');
  }

  idCentro = localStorage.getItem('idCentro');
  private empleadosUrl = `http://localhost:8000/empleados/${this.idCentro}`;
  private serviciosUrl = 'http://localhost:8000/servicios'; // Asegúrate de tener este endpoint en tu backend
  private clientesUrl = `http://localhost:8000/clientes/${this.idCentro}`;

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

  cargarClientes(): void {
    this.http.get<any[]>(this.clientesUrl).subscribe({
      next: (clientes) => (this.clientes = clientes),
      error: (error) => console.error('Error al cargar empleados', error),
    });
  }

  register(user: any): Observable<any> {
    const headers = new HttpHeaders().set('Content-Type', 'application/json');
    return this.http.post(this.registerUrl, JSON.stringify(user), { headers });
  }

  onSubmit() {
    if (this.citaForm.valid) {
      let fechaControl = this.citaForm.get('fecha');
      if (fechaControl) {
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

        // Replace the 'fecha' field in the form with the UTC date
        this.citaForm.patchValue({ fecha: fechaHoraUTC });

        this.register(this.citaForm.value).subscribe({
          next: (response) => {
            console.log('Cita insertada', response);
            this.router.navigate(['/centros-citas']);
          },
          error: (error) => console.error('Error al insertar cita', error),
        });
        console.log(this.citaForm.value);
      }
    }
  }
}
