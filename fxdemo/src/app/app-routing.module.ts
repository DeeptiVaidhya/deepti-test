import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { RegisterComponent } from './register/register.component';
import { LoginComponent } from './login/login.component';
import { ProfileComponent } from './profile/profile.component';
import { AdminComponent } from './admin/admin.component';

const routes: Routes = [

    { path: '',           component : LoginComponent},
    { path: 'login',           component : LoginComponent},
    { path: 'register',   component : RegisterComponent},
    { path: 'profile',    component : ProfileComponent},
    { path: 'admin',      component : AdminComponent},
    

    ];


@NgModule({
  imports: [RouterModule.forRoot(routes),CommonModule],
  exports: [RouterModule]
})
export class AppRoutingModule { }
