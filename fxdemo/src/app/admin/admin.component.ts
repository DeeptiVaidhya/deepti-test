import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';
import { User } from  '../user';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.scss']
})
export class AdminComponent implements OnInit {
  userinfo : any;
  constructor(private authService: AuthService, private router: Router) { }

  ngOnInit() {

    this.userinfo = JSON.parse(localStorage.getItem('userData'));
  }

  logout(){
    this.authService.logout();
    this.router.navigateByUrl('/login');
  }

}