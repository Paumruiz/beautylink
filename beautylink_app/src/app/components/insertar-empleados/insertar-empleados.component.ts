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
import { HeaderComponent } from '../header/header.component';
import { CenterBarComponent } from '../center-bar/center-bar.component';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatInputModule } from '@angular/material/input';
import { MatNativeDateModule } from '@angular/material/core';
import { MatDatetimepickerModule } from '@mat-datetimepicker/core';
import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';

@Component({
  selector: 'app-insertar-empleados',
  standalone: true,
  imports: [
    FormsModule,
    ReactiveFormsModule,
    NgFor,
    HeaderComponent,
    CenterBarComponent,
    MatDatepickerModule,
    MatInputModule,
    MatNativeDateModule,
    MatDatetimepickerModule,
    NgxMaterialTimepickerModule,
  ],
  templateUrl: './insertar-empleados.component.html',
  styleUrl: './insertar-empleados.component.css',
})
export class InsertarEmpleadosComponent {
  empleadosForm: FormGroup;
  nombreCentro: string | null = '';

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
    this.nombreCentro = localStorage.getItem('nombre_centro');
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
