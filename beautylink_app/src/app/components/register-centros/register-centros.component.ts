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

@Component({
  selector: 'app-register-centros',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule],
  templateUrl: './register-centros.component.html',
  styleUrl: './register-centros.component.css',
})
export class RegisterCentrosComponent {
  registerForm: FormGroup;

  private registerUrl = 'http://localhost:8000/register';

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) {
    this.registerForm = this.fb.group({
      nombre_centro: '',
      direccion_centro: '',
      telefono_centro: '',
      email_centro: '',
      password_centro: '',
    });
  }

  register(user: any): Observable<any> {
    const headers = new HttpHeaders().set('Content-Type', 'application/json');
    return this.http.post(this.registerUrl, JSON.stringify(user), { headers });
  }

  onSubmit(): void {
    console.log(this.registerForm.value);
    if (this.registerForm.valid) {
      this.register(this.registerForm.value).subscribe({
        next: (response) => {
          console.log('Registro exitoso', response);
          this.router.navigate(['/login-centros']);
        },
        error: (error) => console.error('Error en el registro', error),
      });
    }
  }
}
