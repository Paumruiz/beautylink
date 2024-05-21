import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { HeaderComponent } from '../header/header.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { Citas } from '../../models/citas.interface';
import { CentrosCitasService } from '../../services/centros-citas.service';
import { HttpClient } from '@angular/common/http';
import moment from 'moment-timezone';
import { ChangeDetectorRef } from '@angular/core';
import { CenterBarComponent } from '../center-bar/center-bar.component';

interface BloqueHorario {
  horas: Array<{ citas: Citas[] }>;
}

interface BloquesHorarios {
  dias: BloqueHorario[];
  horas: string[]; // Asumiendo que quieres mostrar las horas como strings
}

interface Hora {
  hora: number;
  citas: any[]; // Replace 'any' with the actual type of your citas
}

interface Dia {
  dia: string;
  horas: Hora[];
}

@Component({
  selector: 'app-centros-agenda',
  standalone: true,
  imports: [
    CommonModule,
    HeaderComponent,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
    CenterBarComponent,
  ],
  templateUrl: './centros-agenda.component.html',
  styleUrl: './centros-agenda.component.css',
})
export class CentrosAgendaComponent {
  citas: Citas[] = [];
  bloquesHorarios: {
    dias: Dia[];
  };
  currentDate: moment.Moment = moment();
  diaActualIndex: number = 0;
  nombreCentro: string | null = '';

  diasSemana = [
    'Lunes',
    'Martes',
    'Miércoles',
    'Jueves',
    'Viernes',
    'Sábado',
    'Domingo',
  ];

  constructor(
    private centrosCitasService: CentrosCitasService,
    private http: HttpClient
  ) {
    this.bloquesHorarios = { dias: [] };
    this.diaActualIndex = moment().date();
    console.log('dia actual', this.diaActualIndex);
  }

  ngOnInit(): void {
    this.inicializarBloquesHorarios();
    this.loadCitas();
    this.nombreCentro = localStorage.getItem('nombre_centro');
  }

  isCurrentDay(dayIndex: string): boolean {
    const fechaNumero = parseInt(dayIndex, 10);
    return fechaNumero === this.diaActualIndex;
  }

  goToNextWeek(): void {
    // Asume que currentDate es un objeto moment. Ajusta según tu implementación.
    this.currentDate.add(7, 'days'); // Avanza currentDate una semana.
    this.inicializarBloquesHorarios(); // Recalcula los días de la semana basado en la nueva currentDate.
    this.loadCitas(); // Carga las citas para la nueva semana, si es necesario.
  }

  goToPreviousWeek(): void {
    this.currentDate.subtract(7, 'days'); // Retrocede currentDate una semana.
    this.inicializarBloquesHorarios(); // Recalcula los días de la semana basado en la nueva currentDate.
    this.loadCitas(); // Carga las citas para la nueva semana, si es necesario.
  }

  get currentMonth(): string {
    return this.currentDate.format('MMMM YYYY');
  }

  diasDeLaSemana: { dia: string; fecha: string }[] = [];
  nombresDias: string[] = [
    'Lunes',
    'Martes',
    'Miércoles',
    'Jueves',
    'Viernes',
    'Sábado',
    'Domingo',
  ];

  inicializarBloquesHorarios(): void {
    const fechaInicioSemana = this.currentDate.clone().startOf('isoWeek');
    this.diasDeLaSemana = [];

    for (let i = 0; i < 7; i++) {
      const fecha = fechaInicioSemana.clone().add(i, 'days');
      const nombreDia = this.nombresDias[fecha.isoWeekday() - 1];
      this.diasDeLaSemana.push({
        dia: nombreDia,
        fecha: fecha.format('D'),
      });
    }

    this.bloquesHorarios.dias = this.diasDeLaSemana.map((dia) => ({
      dia: dia.dia,
      horas: Array.from({ length: 13 }, (_, i) => ({
        hora: i + 8,
        citas: [],
      })),
    }));
  }

  loadCitas(): void {
    if (this.citas) {
      const idCliente = localStorage.getItem('idCliente');
      if (idCliente !== null) {
        this.centrosCitasService.getCitasById(idCliente).subscribe(
          (data) => {
            this.organizarCitas(data);
            console.log('Citas obtenidas', data);
          },
          (error) => {
            console.error('Error al obtener las citas', error);
          }
        );
      }
    }
  }

  organizarCitas(citas: Citas[]): void {
    const fechaInicioSemana = this.currentDate.clone().startOf('isoWeek');
    const fechaFinSemana = this.currentDate.clone().endOf('isoWeek');
    citas.forEach((cita) => {
      const fechaCita = moment(cita.fecha);

      if (fechaCita.isBetween(fechaInicioSemana, fechaFinSemana, 'day', '[]')) {
        let diaSemana = fechaCita.isoWeekday() - 1;
        let horaCita = fechaCita.hour();

        if (horaCita >= 8 && horaCita <= 20) {
          horaCita -= 8;
          this.bloquesHorarios.dias[diaSemana].horas[horaCita].citas.push(cita);
        }
      }
    });
  }
  generateHours(): number[] {
    // Genera horas solo de 8 a 20
    return Array.from({ length: 13 }, (_, i) => 8 + i);
  }

  formatHour(hour: number): string {
    return `${hour}:00`; // Formato simple, ajusta según necesidad
  }
}
