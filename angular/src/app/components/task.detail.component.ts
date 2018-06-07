import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute, Params} from '@angular/router';
import { UserService } from '../services/user.service';
import { TaskService } from '../services/task.service';
import { Task } from '../models/task';

@Component({
    selector: 'task-detail',
    templateUrl: '../views/task.detail.html',
    providers: [ UserService, TaskService ]
})
export class TaskDetailComponent implements OnInit{
    public titlePrincipal: string;
    public identity: string;
    public token;
    public task: Task;
    public loading;
    constructor(
        private _route: ActivatedRoute,
        private _router: Router,
        private _userService: UserService,
        private _taskService: TaskService
    ){
        this.titlePrincipal = 'Detalle de la tarea';
        this.identity = this._userService.getIdentity();
        this.token = this._userService.getToken();
    }

    ngOnInit(){
        if(this.identity && this.identity.sub){
            this.getTask();
        } else {
            this._router.navigate(['/login']);
        }
    }

    getTask(){
        this.loading = 'show';
        this._route.params.forEach((params:Params)=>{
            let id = +params['id'];

            this._taskService.getTasks(this.token,id).subscribe(
                response => {
                    this.task = response.data;
                    this.loading = 'hide';
                    if(response.status == 'success'){
                        console.log(this.task);
                    } else {
                        this._router.navigate(['/login']);
                    }
                },
                error =>{
                    console.log(<any>error);
                }
            )
        })
    }
}