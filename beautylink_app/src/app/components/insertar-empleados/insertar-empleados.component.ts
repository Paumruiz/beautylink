import { NgFor } from '@angular/common';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
} from '@angular/forms';
import { Router } from '@angular/router';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-insertar-empleados',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, NgFor],
  templateUrl: './insertar-empleados.component.html',
  styleUrl: './insertar-empleados.component.css',
})
export class InsertarEmpleadosComponent {
  empleadosForm: FormGroup;

  private registerUrl = 'http://localhost:8000/empleados';

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) {
    this.empleadosForm = this.fb.group({
      centroId: localStorage.getItem('idCentro'),
      nombre: '',
      apellidos: '',
      rol: '',
      horario_inicio: '',
      horario_fin: '',
    });
  }

  register(user: any): Observable<any> {
    const headers = new HttpHeaders().set('Content-Type', 'application/json');
    return this.http.post(this.registerUrl, JSON.stringify(user), { headers });
  }

  onSubmit() {
    if (this.empleadosForm.valid) {
      this.register(this.empleadosForm.value).subscribe({
        next: (response) => {
          console.log('Empleado insertado', response);
          this.router.navigate(['/centros-empleados']);
        },
        error: (error) => console.error('Error al insertar empleado', error),
      });
      console.log(this.empleadosForm.value);
    }
  }
}
