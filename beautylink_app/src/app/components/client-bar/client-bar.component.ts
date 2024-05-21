import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-client-bar',
  standalone: true,
  imports: [],
  templateUrl: './client-bar.component.html',
  styleUrl: './client-bar.component.css',
})
export class ClientBarComponent implements OnInit {
  nombreCliente: string | null = '';
  apellidosCliente: string | null = '';
  emailCliente: string | null = '';

  ngOnInit(): void {
    this.nombreCliente = localStorage.getItem('nombre_cliente');
    this.apellidosCliente = localStorage.getItem('apellidos_cliente');
    this.emailCliente = localStorage.getItem('email_cliente');
  }
}
