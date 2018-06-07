import {Injectable} from '@angular/core';
import {Http, Response, Headers, RequestOptions} from '@angular/http';
import { map } from 'rxjs/operators'
import { Observable } from 'rxjs/internal/Observable';
import {GLOBAL} from './global';

@Injectable()
export class TaskService{
    public url: string;

    constructor(
        private _http: Http
    ){
        this.url = GLOBAL.url;
    }

    create(token, task){
        let json = JSON.stringify(task);
        let params = "json="+json+"&authorization="+token;
        let headers = new Headers({ 'Content-Type':'application/x-www-form-urlencoded'});

        return this._http.post(this.url+'/task/new', params, {headers:headers}).pipe(
            map(res => res.json())
        );
    }

    getTask(token, page=null){
        let params = "authorization="+token;
        let headers = new Headers({ 'Content-Type':'application/x-www-form-urlencoded'});

        if(page == null){
            page=1;
        }

        return this._http.post(this.url+'/task/list?page='+page, params, {headers:headers}).pipe(
            map(res => res.json())
        );

    }

    getTasks(token, id){
        let params = "id="+id+"&authorization="+token;
        let headers = new Headers({ 'Content-Type':'application/x-www-form-urlencoded'});

        return this._http.post(this.url+'/task/detail/'+id, params,{headers:headers}).pipe(
            map(res => res.json())
        );
    }
}