import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  constructor(private http: HttpClient, private router: Router) {}

  login(credentials: {
    email_centro: string;
    password_centro: string;
  }): Observable<any> {
    return this.http.post('/api/login', credentials);
  }
}
