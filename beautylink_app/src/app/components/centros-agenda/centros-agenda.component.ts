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
  }

  ngOnInit(): void {
    this.inicializarBloquesHorarios();
    this.loadCitas();
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
    this.diasDeLaSemana = this.nombresDias.map((nombreDia, index) => {
      const fecha = this.currentDate.clone().add(index, 'days');
      return {
        dia: nombreDia,
        fecha: fecha.format('D'), // 'D' para el número del día del mes
      };
    });
    this.diasSemana.forEach((dia, indexDia) => {
      this.bloquesHorarios.dias[indexDia] = { dia: dia, horas: [] };
      for (let i = 0; i < 24; i++) {
        this.bloquesHorarios.dias[indexDia].horas.push({
          hora: i,
          citas: [],
        });
      }
    });
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
    citas.forEach((cita) => {
      const fechaCita = moment(cita.fecha);
      console.log('Fecha de la cita', fechaCita);
      const fechaCitaFormat = fechaCita.format('YYYY-MM-DD'); // Formato 'YYYY-MM-DD' para comparación de fecha
      const fechaInicioSemana = this.currentDate.clone().startOf('isoWeek'); // Ajuste para usar inicio de semana según ISO (lunes como primer día)

      // Verifica si la fecha de la cita está dentro de la semana actual
      if (
        fechaCitaFormat >= fechaInicioSemana.format('YYYY-MM-DD') &&
        fechaCitaFormat <=
          fechaInicioSemana.clone().endOf('isoWeek').format('YYYY-MM-DD') // Ajuste para usar fin de semana según ISO
      ) {
        let diaSemana = fechaCita.isoWeekday(); // Ajuste para obtener el día de la semana según ISO (lunes = 1, domingo = 7)
        diaSemana = diaSemana - 1; // Ajuste para alinear con el índice de array (lunes = 0, domingo = 6)

        let horaCita = fechaCita.hour();
        if (horaCita >= 8 && horaCita <= 20) {
          horaCita -= 8; // Ajuste para alinear con el rango de horas
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
