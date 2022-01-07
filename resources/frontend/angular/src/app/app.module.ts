import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppRoutingModule, routes } from './app-routing.module';
import { ToolComponent } from './tool/tool.component';
import { DialogAllModule } from '@syncfusion/ej2-angular-popups';
import { LegendService, ZoomService } from '@syncfusion/ej2-angular-charts';
import { AccumulationChartModule } from '@syncfusion/ej2-angular-charts';
import { DiagramModule, SymbolPaletteModule  } from '@syncfusion/ej2-angular-diagrams';
import { APP_BASE_HREF, Location } from '@angular/common';

import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { AccumulationAnnotationService, AccumulationDataLabelService, AccumulationLegendService, AccumulationTooltipService, ChartAllModule } from '@syncfusion/ej2-angular-charts';

import { DiagramAllModule, SymbolPaletteAllModule, OverviewAllModule } from '@syncfusion/ej2-angular-diagrams';

import { GridAllModule } from '@syncfusion/ej2-angular-grids';

import { ListViewAllModule } from '@syncfusion/ej2-angular-lists';

import { DateRangePickerModule } from '@syncfusion/ej2-angular-calendars';

import { CircularGaugeModule } from '@syncfusion/ej2-angular-circulargauge';

import { DropDownListAllModule } from '@syncfusion/ej2-angular-dropdowns';

import { MultiSelectModule } from '@syncfusion/ej2-angular-dropdowns';

import { ToolbarModule } from '@syncfusion/ej2-angular-navigations';

import { NumericTextBoxModule, ColorPickerModule, UploaderModule, TextBoxModule } from '@syncfusion/ej2-angular-inputs';

import { DropDownButtonModule } from '@syncfusion/ej2-angular-splitbuttons';

import { ButtonModule, CheckBoxModule, RadioButtonModule } from '@syncfusion/ej2-angular-buttons';

import { HttpModule } from '@angular/http';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { RouterModule } from '@angular/router';

import { CommonModule } from '@angular/common';

import { AppComponent } from './app.component';
import { TabsModule } from 'ngx-bootstrap/tabs';
import { HierarchicalTreeService, MindMapService, RadialTreeService, ComplexHierarchicalTreeService } from '@syncfusion/ej2-angular-diagrams';
import { DataBindingService, SnappingService, PrintAndExportService, BpmnDiagramsService} from '@syncfusion/ej2-angular-diagrams';
import { SymmetricLayoutService, ConnectorBridgingService, UndoRedoService, LayoutAnimationService} from '@syncfusion/ej2-angular-diagrams';
import { DiagramContextMenuService, ConnectorEditingService } from '@syncfusion/ej2-angular-diagrams';
import { ModalModule } from 'ngx-bootstrap/modal';
import { BsDropdownDirective, BsDropdownModule } from 'ngx-bootstrap/dropdown';
import { TooltipModule } from 'ngx-bootstrap/tooltip';
import { NgScrollbarModule, NG_SCROLLBAR_OPTIONS } from 'ngx-scrollbar';
import { MalihuScrollbarModule } from 'ngx-malihu-scrollbar';
import { HttpClientModule,HttpClient,HttpHeaders } from '@angular/common/http';
import { ToolService } from './tool.service';
import { ToastrModule } from 'ngx-toastr';
import { NgxSpinnerModule } from "ngx-spinner";
import { AccordionModule } from '@syncfusion/ej2-angular-navigations';

@NgModule({
  declarations: [
    AppComponent,
    ToolComponent,
    
  ],
  imports: [ 
    BrowserAnimationsModule,
    AppRoutingModule,
    BrowserModule,
    DiagramAllModule, 
    UploaderModule,
    ChartAllModule, 
    GridAllModule, 
    SymbolPaletteAllModule, 
    OverviewAllModule, 
    ButtonModule,       
    ColorPickerModule, 
    DateRangePickerModule, 
    CheckBoxModule, 
    AccumulationChartModule, 
    ToolbarModule, 
    DropDownButtonModule, 
    UploaderModule, 
    CircularGaugeModule, 
    DropDownListAllModule, 
    ListViewAllModule,       
    DialogAllModule, 
    TextBoxModule, 
    RadioButtonModule,       
    MultiSelectModule, 
    NumericTextBoxModule, 
    TabsModule.forRoot(),
    DiagramModule,
    SymbolPaletteModule,
    FormsModule,
    ModalModule.forRoot(),
    BsDropdownModule.forRoot(),
    TooltipModule.forRoot(),
    NgScrollbarModule,
    MalihuScrollbarModule.forRoot(),
    HttpClientModule,
    ToastrModule.forRoot(),
    NgxSpinnerModule,
    AccordionModule 
  ],
  providers: [
    LegendService,
    ZoomService,
    HierarchicalTreeService, 
    MindMapService, 
    RadialTreeService, 
    ComplexHierarchicalTreeService, 
    DataBindingService, 
    SnappingService, 
    PrintAndExportService, 
    BpmnDiagramsService, 
    SymmetricLayoutService, 
    ConnectorBridgingService, 
    UndoRedoService, 
    LayoutAnimationService, 
    DiagramContextMenuService, 
    ConnectorEditingService,
    ToolService,
    NgxSpinnerModule,
    { provide : BsDropdownDirective},
    { provide: APP_BASE_HREF, useValue: window['_app_base'] || '/' },
    
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
