import { CommonModule, NgFor } from '@angular/common';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
} from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-actualizar-cita',
  standalone: true,
  imports: [FormsModule, CommonModule, ReactiveFormsModule, NgFor, RouterLink],
  templateUrl: './actualizar-cita.component.html',
  styleUrl: './actualizar-cita.component.css',
})
export class ActualizarCitaComponent {
  citaForm: FormGroup;
  servicios: any[] = [];
  empleados: any[] = [];
  idCita: number = 0;

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
  }

  idCentro = localStorage.getItem('idCentro');
  private empleadosUrl = `http://localhost:8000/empleados/${this.idCentro}`;
  private serviciosUrl = 'http://localhost:8000/servicios'; // Asegúrate de tener este endpoint en tu backend

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

  actualizarCita(id: number, citaData: any): Observable<any> {
    const url = `http://localhost:8000/citas/${id}`;
    return this.http.patch(url, { cita: citaData });
  }

  onSubmit() {
    if (this.citaForm.valid) {
      this.actualizarCita(this.idCita, this.citaForm.value).subscribe({
        next: (response) =>
          console.log('Cita actualizada correctamente', response),
        error: (error) => console.error('Error al actualizar la cita', error),
      });
    }
  }
}
