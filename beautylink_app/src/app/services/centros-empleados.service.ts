import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Empleados } from '../models/empleados.interface';

@Injectable({
  providedIn: 'root',
})
export class CentrosEmpleadosService {
  private readonly baseUrl = 'http://localhost:8000/empleados/';

  constructor(private http: HttpClient) {}

  getEmpleadosById(id: string): Observable<Empleados[]> {
    const url = `${this.baseUrl}${id}`;
    return this.http.get<Empleados[]>(url);
  }
}
