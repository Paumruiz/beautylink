import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Citas } from '../models/citas.interface';

@Injectable({
  providedIn: 'root',
})
export class ClientDashboardService {
  private readonly baseUrl = 'http://localhost:8000/client-dashboard/';

  constructor(private http: HttpClient) {}

  getCitasById(id: string): Observable<Citas[]> {
    const url = `${this.baseUrl}${id}`;
    return this.http.get<Citas[]>(url);
  }
}
