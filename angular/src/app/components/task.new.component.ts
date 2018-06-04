import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { UserService } from '../services/user.service';
import { TaskService } from '../services/task.service';
import { Task } from '../models/task';


@Component({
    selector: 'task-new',
    templateUrl: '../views/task.new.html',
    providers: [ UserService, TaskService ]
})
export class TaskNewComponent implements OnInit{
    public titlePrincipal: string;
    public identity: string;
    public token;
    public task: Task;
    public status_save;

    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService,
        private _taskService: TaskService
    ){
        this.titlePrincipal = 'Crear nueva tarea';
        this.identity = this._userService.getIdentity();
        this.token = this._userService.getToken();
    }

    ngOnInit(){
        if(this.identity == null && !this.identity.sub){
            this._router.navigate(['/login']);
        } else {
            this.task = new Task(1, "", "", '', 'null', 'null');
        }

    }

    onSubmit(){
        console.log(this.task);
        this._taskService.create(this.token, this.task).subscribe(
            response => {
                this.status_save = response.status;
                if(this.status_save != "success"){
                    this.status_save = 'error';
                } else {
                    this.task = response.data;
                }

                this._router.navigate(['/']);
            },
            error => {
                console.log(<any>error);
            }
        );
    }
}