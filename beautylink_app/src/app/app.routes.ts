import { RouterModule, Routes } from '@angular/router';
import { ClientDashboardComponent } from './components/client-dashboard/client-dashboard.component';
import { LoginCentrosComponent } from './components/login-centros/login-centros.component';
import { NgModule } from '@angular/core';
import { LoginClientesComponent } from './components/login-clientes/login-clientes.component';
import { CentrosDashboardComponent } from './components/centros-dashboard/centros-dashboard.component';
import { RegisterCentrosComponent } from './components/register-centros/register-centros.component';
import { RegisterClientesComponent } from './components/register-clientes/register-clientes.component';
import { NotfoundComponent } from './components/notfound/notfound.component';
import { ReservarCitaComponent } from './components/reservar-cita/reservar-cita.component';

export const routes: Routes = [
  { path: 'reservar-cita', component: ReservarCitaComponent },
  { path: 'login-centros', component: LoginCentrosComponent },
  { path: 'login-clientes', component: LoginClientesComponent },
  { path: 'client-dashboard', component: ClientDashboardComponent },
  { path: 'centros-dashboard', component: CentrosDashboardComponent },
  { path: 'register-centros', component: RegisterCentrosComponent },
  { path: 'register-clientes', component: RegisterClientesComponent },
  { path: '404', component: NotfoundComponent },
  { path: '', redirectTo: 'login-clientes', pathMatch: 'full' },
  { path: '**', redirectTo: '404' },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
