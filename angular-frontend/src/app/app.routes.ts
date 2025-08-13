import { Routes } from '@angular/router';
import { LandingComponent } from './pages/landing/landing.component';
import { LoginComponent } from './pages/login/login.component';
import { MainLayoutComponent } from './layouts/main-layout/main-layout.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { InvitationsComponent } from './pages/invitations/invitations.component';
import { RegisterComponent } from './pages/register/register.component';

export const routes: Routes = [
  // Rotas Públicas
  { path: '', component: LandingComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register/:token', component: RegisterComponent }, // Rota para o novo colaborador

  // Rotas Protegidas (dentro do layout principal)
  {
    path: 'app',
    component: MainLayoutComponent,
    // canActivate: [AuthGuard], // Futuramente, adicionaremos o guardião de rota aqui
    children: [
      { path: 'dashboard', component: DashboardComponent },
      { path: 'invitations', component: InvitationsComponent },
      // Adicione outras rotas do painel aqui
    ]
  },

  // Redireciona para a landing page se a rota não for encontrada
  { path: '**', redirectTo: '' }
];
