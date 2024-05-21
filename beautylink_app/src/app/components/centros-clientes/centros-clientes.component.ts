import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { HeaderComponent } from '../header/header.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Clientes } from '../../models/clientes.interface';
import { CentrosClientesService } from '../../services/centros-clientes.service';
import { HttpClient } from '@angular/common/http';
import { CenterBarComponent } from '../center-bar/center-bar.component';

@Component({
  selector: 'app-centros-clientes',
  standalone: true,
  imports: [
    CommonModule,
    HeaderComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
    CenterBarComponent,
  ],
  templateUrl: './centros-clientes.component.html',
  styleUrl: './centros-clientes.component.css',
})
export class CentrosClientesComponent {
  clientes: Clientes[] = [];
  nombreCentro: string | null = '';

  constructor(
    private centrosClientesService: CentrosClientesService,
    private http: HttpClient
  ) {}

  ngOnInit(): void {
    this.loadClientes();
    this.nombreCentro = localStorage.getItem('nombre_centro');
  }

  loadClientes(): void {
    if (this.clientes) {
      const idCentro = localStorage.getItem('idCentro');
      if (idCentro !== null) {
        this.centrosClientesService.getCitasById(idCentro).subscribe(
          (data) => {
            this.clientes = data;
          },
          (error) => {
            console.error('Error al obtener las citas', error);
          }
        );
      }
    }
  }
}
