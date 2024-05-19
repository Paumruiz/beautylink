import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Citas } from '../models/citas.interface';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class CitaService {
  private apiUrl = 'http://localhost:8000/citas'; // Ajusta según sea necesario

  constructor(private http: HttpClient) {}

  insertarCita(cita: Citas): Observable<any> {
    return this.http.post(this.apiUrl, { cita });
  }
}
