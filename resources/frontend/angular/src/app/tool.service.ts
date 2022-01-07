import { Injectable,OnInit } from '@angular/core';
import { HttpClient, HttpContext, HttpHeaders, HttpParams } from '@angular/common/http';
// import { Observable } from 'rxjs/Rx';
import { map, catchError } from 'rxjs/operators';
import { ActivatedRoute, Router } from '@angular/router'; 
import { Observable, Subject } from 'rxjs';
import { ToastrService } from 'ngx-toastr';
import { NgxSpinnerService } from "ngx-spinner";

@Injectable({
  providedIn: 'root'
})
export class ToolService {

  getcharttype:any = {};

  constructor(private http: HttpClient,
    private router: Router,
    public spinner: NgxSpinnerService,
    private toastr: ToastrService,
    /*private ngxService: NgxUiLoaderService, */
    ) {
      this.getcharttype = window.location.pathname.split("/")[1];
      if(this.getcharttype == 'flowchart'){
        var getcsrf = '/angular-tool-csrf';
        this.http.get(this.url + getcsrf).subscribe(csrf => {
          console.log(csrf,"csrf");
          this.getcsrf = csrf;
        });
      } else if(this.getcharttype == 'RU-flowchart'){
        var getcsrf = '/RU-angular-tool-csrf';
        this.http.get(this.url + getcsrf).subscribe(csrf => {
          console.log(csrf,"csrf");
          this.getcsrf = csrf;
        });
      } else if(this.getcharttype == 'SA-flowchart'){
        var getcsrf = '/SA-angular-tool-csrf';
        this.http.get(this.url + getcsrf).subscribe(csrf => {
          console.log(csrf,"csrf");
          this.getcsrf = csrf;
        });
      }
    }

    // url = 'http://127.0.0.1:8000';     
    url = 'https://apps.kaizenhub.ca';    
    getcsrf:any; 
 
    private extractData(res: Response) {
      let body = res;
      return body || {};
    }

    private handleError(error: any) {
      /*console.log(error)*/
      let errMsg = (error.message) ? error.message : error.status ? `${error.status} - ${error.statusText}` : 'Server error';
      /*console.log(errMsg);*/
      return errMsg;
    }
    /*  Post data to backend server */
    postData(modelroute: any, details: any): Observable < any > {    	
      // var getcsrf = '/angular-tool-csrf';
      // this.http.get(this.url + getcsrf).subscribe(csrf => {
      //   console.log(csrf,"csrf");
      //   this.getcsrf = csrf;
        let header = new HttpHeaders().set(
        "X-CSRF-TOKEN",this.getcsrf.csrf
      );
        return this.http.post(this.url + modelroute, details,{headers : header});
      // });
      
        
    }
    
    /*  Get data from backend server */
    getData(domain: string) {
        return this.http.get(this.url + domain);
    }

    /* Put / update data to backend server */
    putData(modelroute: string, details: any): Observable < any > {
        var updatedata = this.http.put(this.url + modelroute, details).pipe(map(res => {})).pipe(catchError(this.handleError));
        return updatedata;
    }

    /* Delete data from backend server*/
    delData(modelroute: string, details: { headers?: HttpHeaders | { [header: string]: string | string[]; }; context?: HttpContext; observe?: "body"; params?: HttpParams | { [param: string]: string | number | boolean | readonly (string | number | boolean)[]; }; reportProgress?: boolean; responseType: "arraybuffer"; withCredentials?: boolean; body?: any; }): Observable < any > {
        var deletedata = this.http.delete(this.url + modelroute, details);
        return deletedata;
    }

    startLoader(){
      this.spinner.show(); 
    }
    stopLoader(){
      this.spinner.hide(); 
    }
    
}
