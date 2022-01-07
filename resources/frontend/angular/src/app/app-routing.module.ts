import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { ToolComponent } from './tool/tool.component';

export const routes: Routes = [
    { path : '', component : AppComponent},
    { path : 'text/:tabLink', component : AppComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
