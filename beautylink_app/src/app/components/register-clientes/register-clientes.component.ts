import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  Validators,
  FormsModule,
  NgControl,
} from '@angular/forms';
import { Observable } from 'rxjs';
import { ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { NgFor } from '@angular/common';

@Component({
  selector: 'app-register-clientes',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule, NgFor],
  templateUrl: './register-clientes.component.html',
  styleUrl: './register-clientes.component.css',
})
export class RegisterClientesComponent {
  registerForm: FormGroup;
  centros: any[] = [];

  private registerUrl = 'http://localhost:8000/register_clientes';
  private centrosUrl = 'http://localhost:8000/centros';

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) {
    this.registerForm = this.fb.group({
      email_cliente: '',
      nombre_cliente: '',
      apellidos_cliente: '',
      telefono_cliente: '',
      id_centro: '',
      password_cliente: '',
    });

    this.cargarCentros();
  }

  cargarCentros(): void {
    this.http.get<any[]>(this.centrosUrl).subscribe({
      next: (centros) => (this.centros = centros),
      error: (error) => console.error('Error al cargar centros', error),
    });
  }

  registerCliente(clienteData: any): Observable<any> {
    const headers = new HttpHeaders().set('Content-Type', 'application/json');
    return this.http.post(this.registerUrl, JSON.stringify(clienteData), {
      headers,
    });
  }

  onSubmit(): void {
    if (this.registerForm.valid) {
      this.registerCliente(this.registerForm.value).subscribe({
        next: (response) => {
          console.log('Registro exitoso', response);
          this.router.navigate(['/login-clientes']);
        },
        error: (error) => console.error('Error en el registro', error),
      });
    }
  }
}
