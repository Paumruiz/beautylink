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
import { Empleados } from '../../models/empleados.interface';
import { HttpClient } from '@angular/common/http';
import { CentrosEmpleadosService } from '../../services/centros-empleados.service';
import { CenterBarComponent } from '../center-bar/center-bar.component';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatInputModule } from '@angular/material/input';
import { MatNativeDateModule } from '@angular/material/core';
import { MatDatetimepickerModule } from '@mat-datetimepicker/core';
import { NgxMaterialTimepickerModule } from 'ngx-material-timepicker';

@Component({
  selector: 'app-centros-empleados',
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
    MatDatetimepickerModule,
    NgxMaterialTimepickerModule,
  ],
  templateUrl: './centros-empleados.component.html',
  styleUrl: './centros-empleados.component.css',
})
export class CentrosEmpleadosComponent {
  empleados: Empleados[] = [];
  editMode: boolean = false;
  empleadosForm: FormGroup;
  empleadosToEdit: any;
  nombreCentro: string | null = '';

  constructor(
    private centrosEmpleadosService: CentrosEmpleadosService,
    private http: HttpClient,
    private fb: FormBuilder
  ) {
    this.empleadosForm = this.fb.group({
      centro: localStorage.getItem('idCentro'),
      nombre: '',
      apellidos: '',
      rol: '',
      horario_inicio: '',
      horario_fin: '',
    });
  }

  ngOnInit(): void {
    this.loadEmpleados();
    this.initForm();
    this.nombreCentro = localStorage.getItem('nombre_centro');
  }

  loadEmpleados(): void {
    if (this.empleados) {
      const idCentro = localStorage.getItem('idCentro');
      if (idCentro !== null) {
        this.centrosEmpleadosService.getEmpleadosById(idCentro).subscribe(
          (data) => {
            this.empleados = data;
          },
          (error) => {
            console.error('Error al obtener los empleados', error);
          }
        );
      }
    }
  }

  initForm(): void {
    this.empleadosForm = this.fb.group({
      nombre: '',
      apellidos: '',
      rol: '',
      horario_inicio: '',
      horario_fin: '',
    });
  }

  editarEmpleado(empleado: any): void {
    this.editMode = true;
    this.empleadosToEdit = empleado;
    this.empleadosForm.patchValue({
      nombre: empleado.nombre,
      apellidos: empleado.apellidos,
      rol: empleado.rol,
      horario_inicio: empleado.horario_inicio.date,
      horario_fin: empleado.horario_fin.date,
    });
  }

  actualizarEmpleado(): void {
    if (this.empleadosForm.valid) {
      const empleadosData = this.empleadosForm.value;
      this.http
        .patch(
          `http://localhost:8000/empleados/${this.empleadosToEdit.id}`,
          empleadosData
        )
        .subscribe({
          next: (response) => {
            console.log('Empleado actualizado correctamente', response);
            this.loadEmpleados(); // Actualiza la lista de citas después de la actualización
            this.cancelEditMode();
          },
          error: (error) =>
            console.error('Error al actualizar el empleado', error),
        });
    }
  }

  cancelEditMode(): void {
    this.editMode = false;
    this.empleadosToEdit = null;
    this.empleadosForm.reset();
  }

  eliminarEmpleado(id: number) {
    const url = `http://localhost:8000/empleados/${id}`; // Asegúrate de que la URL coincida con tu API
    if (confirm('¿Estás seguro de que deseas eliminar este empleado?')) {
      // Opcional: Diálogo de confirmación
      this.http.delete(url).subscribe({
        next: (response) => {
          console.log('Empleado eliminado correctamente', response);
          this.loadEmpleados(); // Actualiza la lista de citas después de la eliminación
        },
        error: (error) => console.error('Error al eliminar el empleado', error),
      });
    }
  }
}
