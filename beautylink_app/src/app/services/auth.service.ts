import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  constructor(private http: HttpClient) {}

  login(credentials: {
    email_centro: string;
    password_centro: string;
  }): Observable<any> {
    return this.http.post('/api/login', credentials);
  }
}
