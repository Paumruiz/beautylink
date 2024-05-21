import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-center-bar',
  standalone: true,
  imports: [],
  templateUrl: './center-bar.component.html',
  styleUrl: './center-bar.component.css',
})
export class CenterBarComponent implements OnInit {
  nombreCentro: string | null = '';
  emailCentro: string | null = '';

  constructor() {}

  ngOnInit(): void {
    this.nombreCentro = localStorage.getItem('nombre_centro');
    this.emailCentro = localStorage.getItem('email_centro');
  }
}
