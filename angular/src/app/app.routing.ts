import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LoginComponent } from './components/login.component';
import { RegisterComponent } from './components/register.component';
import {DefaultComponent} from './components/default.component';
import {UserEditComponent} from './components/user.edit.component';
import {TaskNewComponent} from './components/task.new.component';
import {TaskDetailComponent} from './components/task.detail.component';

const appRoutes: Routes = [
    { path: '', component:DefaultComponent },
    { path: 'index', component:DefaultComponent },
    { path: 'index/:page', component:DefaultComponent },
    { path: 'login', component:LoginComponent },
    { path: 'login/:id', component:LoginComponent },
    { path: 'register', component:RegisterComponent },
    { path: 'user-edit', component:UserEditComponent },
    { path: 'task-new', component:TaskNewComponent },
    { path: 'task/:id', component:TaskDetailComponent },
    { path: '**', component:LoginComponent },

];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);