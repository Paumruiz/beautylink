import { RouterModule, Routes } from '@angular/router';
import { ClientDashboardComponent } from './components/client-dashboard/client-dashboard.component';
import { LoginCentrosComponent } from './components/login-centros/login-centros.component';
import { NgModule } from '@angular/core';
import { LoginClientesComponent } from './components/login-clientes/login-clientes.component';
import { CentrosDashboardComponent } from './components/centros-dashboard/centros-dashboard.component';
import { RegisterCentrosComponent } from './components/register-centros/register-centros.component';
import { RegisterClientesComponent } from './components/register-clientes/register-clientes.component';
import { ReservarCitaComponent } from './components/reservar-cita/reservar-cita.component';
import { CentrosCitasComponent } from './components/centros-citas/centros-citas.component';
import { CentrosClientesComponent } from './components/centros-clientes/centros-clientes.component';
import { CentrosEmpleadosComponent } from './components/centros-empleados/centros-empleados.component';
import { InsertarEmpleadosComponent } from './components/insertar-empleados/insertar-empleados.component';
import { CentrosAgendaComponent } from './components/centros-agenda/centros-agenda.component';
import { InsertarCitaCentroComponent } from './components/insertar-cita-centro/insertar-cita-centro.component';

export const routes: Routes = [
  { path: 'reservar-cita', component: ReservarCitaComponent },
  { path: 'insertar-cita-centro', component: InsertarCitaCentroComponent },
  { path: 'login-centros', component: LoginCentrosComponent },
  { path: 'login-clientes', component: LoginClientesComponent },
  { path: 'client-dashboard', component: ClientDashboardComponent },
  { path: 'centros-dashboard', component: CentrosDashboardComponent },
  { path: 'centros-clientes', component: CentrosClientesComponent },
  { path: 'centros-citas', component: CentrosCitasComponent },
  { path: 'centros-empleados', component: CentrosEmpleadosComponent },
  { path: 'centros-agenda', component: CentrosAgendaComponent },
  { path: 'insertar-empleados', component: InsertarEmpleadosComponent },
  { path: 'register-centros', component: RegisterCentrosComponent },
  { path: 'register-clientes', component: RegisterClientesComponent },
  { path: '', redirectTo: 'login-clientes', pathMatch: 'full' },
  { path: '**', redirectTo: 'login-clientes' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
