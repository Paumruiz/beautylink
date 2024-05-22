import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Citas } from '../models/citas.interface';

export interface CitasResponse {
  num_citas: number;
  citas: Citas[];
}

@Injectable({
  providedIn: 'root',
})
export class CentrosDashboardService {
  private readonly baseUrl = 'http://localhost:8000/admin-dashboard/';

  constructor(private http: HttpClient) {}

  getCitasById(id: string): Observable<CitasResponse> {
    const url = `${this.baseUrl}${id}`;
    return this.http.get<CitasResponse>(url);
  }
}
