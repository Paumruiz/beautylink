import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { NgFor } from '@angular/common';
import { Observable } from 'rxjs';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
import { ClientBarComponent } from '../client-bar/client-bar.component';
import { SidebarHeaderComponent } from '../sidebar-header/sidebar-header.component';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatInputModule } from '@angular/material/input';
import { MatNativeDateModule } from '@angular/material/core';
import { MatDatetimepickerModule } from '@mat-datetimepicker/core';
import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';
import { MatNativeDatetimeModule } from '@mat-datetimepicker/core';

@Component({
  selector: 'app-reservar-cita',
  standalone: true,
  imports: [
    FormsModule,
    ReactiveFormsModule,
    NgFor,
    ClientBarComponent,
    SidebarHeaderComponent,
    MatDatepickerModule,
    MatInputModule,
    MatNativeDateModule,
    MatNativeDatetimeModule,
    MatDatetimepickerModule,
    NgxMaterialTimepickerModule,
  ],
  templateUrl: './reservar-cita.component.html',
  styleUrl: './reservar-cita.component.css',
})
export class ReservarCitaComponent {
  citaForm: FormGroup;
  servicios: any[] = [];
  empleados: any[] = [];
  nombreCliente: string | null = '';
  apellidosCliente: string | null = '';

  private registerUrl = 'http://localhost:8000/citas';

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router
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
    this.nombreCliente = localStorage.getItem('nombre_cliente');
    this.apellidosCliente = localStorage.getItem('apellidos_cliente');
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

        this.citaForm.patchValue({ fecha: fechaHoraUTC });

        this.register(this.citaForm.value).subscribe({
          next: (response) => {
            console.log('Cita insertada', response);
            this.router.navigate(['/client-dashboard']);
          },
          error: (error) => console.error('Error al insertar cita', error),
        });
        console.log(this.citaForm.value);
      }
    }
  }
}
