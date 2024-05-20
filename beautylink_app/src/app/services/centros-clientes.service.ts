import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Citas } from '../models/citas.interface';
import { Clientes } from '../models/clientes.interface';

@Injectable({
  providedIn: 'root',
})
export class CentrosClientesService {
  private readonly baseUrl = 'http://localhost:8000/clientes/';

  constructor(private http: HttpClient) {}

  getCitasById(id: string): Observable<Clientes[]> {
    const url = `${this.baseUrl}${id}`;
    return this.http.get<Clientes[]>(url);
  }
}
