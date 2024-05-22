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

  showError = false;

  constructor(private http: HttpClient, private router: Router) {}

  submitForm() {
    this.http
      .post<any>('http://localhost:8000/login_centros', this.formData)
      .subscribe(
        (response) => {
          console.log('Respuesta del servidor:', response);
          if (response.success) {
            localStorage.setItem('idCentro', response.centroId);
            localStorage.setItem('nombre_centro', response.nombre_centro);
            localStorage.setItem('email_centro', response.email_centro);
            this.router.navigate(['/centros-dashboard']);
          }
        },
        (error) => {
          console.error('Error en la solicitud:', error);
          if (error.status === 400) {
            this.showError = true;
            this.router.navigate(['/login-centros']);
            console.log('credenciales incorrectas');
          }
        }
      );
  }
}
