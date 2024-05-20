import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { HeaderComponent } from '../header/header.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Citas } from '../../models/citas.interface';
import { HttpClient } from '@angular/common/http';
import { CentrosCitasService } from '../../services/centros-citas.service';

@Component({
  selector: 'app-centros-citas',
  standalone: true,
  imports: [
    CommonModule,
    HeaderComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
  ],
  templateUrl: './centros-citas.component.html',
  styleUrl: './centros-citas.component.css',
})
export class CentrosCitasComponent {
  citas: Citas[] = [];

  constructor(
    private centrosCitasService: CentrosCitasService,
    private http: HttpClient
  ) {}

  ngOnInit(): void {
    this.loadCitas();
  }

  loadCitas(): void {
    if (this.citas) {
      const idCliente = localStorage.getItem('idCliente');
      if (idCliente !== null) {
        this.centrosCitasService.getCitasById(idCliente).subscribe(
          (data) => {
            this.citas = data;
          },
          (error) => {
            console.error('Error al obtener las citas', error);
          }
        );
      }
    }
  }
}
