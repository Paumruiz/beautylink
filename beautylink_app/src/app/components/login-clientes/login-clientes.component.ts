import { Component } from '@angular/core';
import {
  FormBuilder,
  FormGroup,
  FormsModule,
  Validators,
} from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { HttpClient } from '@angular/common/http';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { NgIf } from '@angular/common';

@Component({
  selector: 'app-login-clientes',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, FormsModule, NgIf],
  templateUrl: './login-clientes.component.html',
  styleUrl: './login-clientes.component.css',
})
export class LoginClientesComponent {
  formData = {
    email_cliente: '',
    password_cliente: '',
  };

  showError = false;

  constructor(private http: HttpClient, private router: Router) {}

  submitForm() {
    this.http
      .post<any>('http://localhost:8000/login_clientes', this.formData)
      .subscribe(
        (response) => {
          console.log('Respuesta del servidor:', response);
          if (response.success) {
            localStorage.setItem('idCliente', response.clientId);
            localStorage.setItem('idCentro', response.centroId);
            localStorage.setItem('nombre_cliente', response.nombre_cliente);
            localStorage.setItem(
              'apellidos_cliente',
              response.apellidos_cliente
            );
            localStorage.setItem('email_cliente', response.email_cliente);
            this.router.navigate(['/client-dashboard']);
          }
        },
        (error) => {
          console.error('Error en la solicitud:', error);
          if (error.status === 400) {
            this.showError = true;
            this.router.navigate(['/login-clientes']);
            console.log('credenciales incorrectas');
          }
        }
      );
  }
}
