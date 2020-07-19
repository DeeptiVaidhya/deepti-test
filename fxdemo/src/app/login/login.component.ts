import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl,FormBuilder, Validators} from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { Router } from  '@angular/router';
import { User } from  '../user';
import { AlertService } from '../_services/alert.service';
import { AuthService } from '../_services/auth.service';
import { first } from 'rxjs/operators';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  constructor(private authService: AuthService,private alertService: AlertService, private router: Router, private formBuilder: FormBuilder,private Http: HttpClientModule ) { }
    loginForm: FormGroup;
    isSubmitted  =  false;
    res: any;

  ngOnInit() {
    this.loginForm  =  this.formBuilder.group({
        email: ['', Validators.required],
        password: ['', Validators.required]
    });
  }
  get formControls() { return this.loginForm.controls; }
  onSubmit() {
      this.alertService.clear();
      console.log(this.loginForm.value);
      if (this.loginForm.invalid) {
          return;
      }
      this.authService.login(this.formControls.email.value, this.formControls.password.value)
          .pipe(first())
          .subscribe(
              data => {
              this.res = data;             
              if (this.res.status === true) {
                  localStorage.setItem('userToken', this.res.token);
                  localStorage.setItem('userId', this.res.data.user_id);
                  localStorage.setItem('userData', JSON.stringify(this.res.data));
                  this.router.navigateByUrl('/admin');
              } else {                            
                this.alertService.error(data.message, 'Error');
              }
              },
          error => {             
              this.alertService.error(error.message, 'Error');
          });
      }
}




