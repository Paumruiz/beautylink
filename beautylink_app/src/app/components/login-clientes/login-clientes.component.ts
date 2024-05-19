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

  showError = false; // Variable de bandera para controlar si se muestra el aviso de credenciales incorrectas

  constructor(private http: HttpClient, private router: Router) {}

  submitForm() {
    this.http
      .post<any>('http://localhost:8000/login_clientes', this.formData)
      .subscribe(
        (response) => {
          console.log('Respuesta del servidor:', response);
          // Aquí puedes manejar la respuesta del servidor, redireccionar, etc.
          if (response.success) {
            // Suponiendo que el servidor responde con un objeto JSON que contiene una clave 'success' que indica si el inicio de sesión fue exitoso
            localStorage.setItem('idCliente', response.clientId); // Guarda la id del cliente en el localStorage
            localStorage.setItem('idCentro', response.centroId); // Guarda la id del cliente en el localStorage
            this.router.navigate(['/client-dashboard']); // Redirecciona a la ruta centros-dashboard
          }
        },
        (error) => {
          console.error('Error en la solicitud:', error);
          if (error.status === 400) {
            // Mostrar el aviso de credenciales incorrectas si la respuesta del servidor es un Bad Request (400)
            this.showError = true;
            this.router.navigate(['/login-clientes']);
            console.log('credenciales incorrectas');
          }
        }
      );
  }
}
