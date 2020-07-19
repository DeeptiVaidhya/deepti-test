import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { User } from './user';
import { BehaviorSubject, Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { config } from './config/config';


@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private currentUserSubject: BehaviorSubject<User>;
    public currentUser: Observable<User>;
    baseUrl = config.baseUrl;
    constructor(private http: HttpClient) {
        this.currentUserSubject = new BehaviorSubject<User>(JSON.parse(localStorage.getItem('currentUser')));
        this.currentUser = this.currentUserSubject.asObservable();
        
    }

    public get currentUserValue(): User {
        return this.currentUserSubject.value;
    }

  login(email, password) {
    console.log(config.baseUrl);
    let headers = new HttpHeaders();
    headers = headers.set('Content-Type', 'application/json; charset=utf-8');
    
    return this.http.post<any>(`${this.baseUrl}/login`, { email, password }, {
        headers: headers
    })
    .pipe(map(user => {
        console.log(user);
        localStorage.setItem('currentUser', JSON.stringify(user));
        this.currentUserSubject.next(user);
        return user;
        
    }));
        
}

register(username, email, password, mobile) {
    let headers = new HttpHeaders();
    headers = headers.set('Content-Type', 'application/json; charset=utf-8');
    return this.http.post<any>(`${this.baseUrl}/register`, { username, email, password, mobile }, {
        headers: headers
    })
    .pipe(map(user => {            
        return user;            
    }));
        
}


  // public login(userInfo: User){
  //   localStorage.setItem('ACCESS_TOKEN', "access_token");
  // }

  public isLoggedIn(){
    return localStorage.getItem('ACCESS_TOKEN') !== null;

  }

  
//   logout() {
//     localStorage.clear();
//     this.currentUserSubject.next(null);
// }

  public logout(){
    localStorage.removeItem('ACCESS_TOKEN');
  }
}
