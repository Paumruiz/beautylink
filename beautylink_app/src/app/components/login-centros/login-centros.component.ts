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
  selector: 'app-login',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, FormsModule, NgIf],
  templateUrl: './login-centros.component.html',
  styleUrls: ['./login-centros.component.css'],
})
export class LoginCentrosComponent {
  formData = {
    email_centro: '',
    password_centro: '',
  };

  showError = false; // Variable de bandera para controlar si se muestra el aviso de credenciales incorrectas

  constructor(private http: HttpClient, private router: Router) {}

  submitForm() {
    this.http
      .post<any>('http://localhost:8000/login_centros', this.formData)
      .subscribe(
        (response) => {
          console.log('Respuesta del servidor:', response);
          // Aquí puedes manejar la respuesta del servidor, redireccionar, etc.
          if (response.success) {
            // Suponiendo que el servidor responde con un objeto JSON que contiene una clave 'success' que indica si el inicio de sesión fue exitoso
            this.router.navigate(['/centros-dashboard']); // Redirecciona a la ruta centros-dashboard
          }
        },
        (error) => {
          console.error('Error en la solicitud:', error);
          if (error.status === 400) {
            // Mostrar el aviso de credenciales incorrectas si la respuesta del servidor es un Bad Request (400)
            this.showError = true;
            this.router.navigate(['/login-centros']);
            console.log('credenciales incorrectas');
          }
        }
      );
  }
}
