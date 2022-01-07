import { ChangeDetectorRef, Component, ViewEncapsulation, ViewChild,Inject, OnInit, TemplateRef  } from '@angular/core';
import { DiagramComponent, ShadowModel, NodeConstraints } from '@syncfusion/ej2-angular-diagrams';
import { DataManager } from '@syncfusion/ej2-data';
import {
  Diagram, BpmnDiagrams, NodeModel, UndoRedo, ConnectorModel, PointPortModel, Connector, FlowShapeModel, BasicShapeModel,
  SymbolInfo, IDragEnterEventArgs, SnapSettingsModel, MarginModel, TextStyleModel, StrokeStyleModel,ContextMenuSettingsModel,
  OrthogonalSegmentModel, Node, PaletteModel, DiagramTools, DataBinding, HierarchicalTree, LayoutAnimation,
  IDropEventArgs,randomId, ISelectionChangeEventArgs, IExportOptions, FileFormats, ScrollSettingsModel, Container, StackPanel, ImageElement, TextElement, RulerSettingsModel, SymbolPalette, BpmnGatewayModel, PortVisibility, PortConstraints
} from '@syncfusion/ej2-diagrams';
import { ClickEventArgs, ExpandMode, ItemModel, MenuEventArgs, NodeAnimationSettings } from '@syncfusion/ej2-navigations';
import { paletteIconClick } from '../scripts/diagram-common';
import { NgForm } from '@angular/forms';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { BsModalService, BsModalRef } from 'ngx-bootstrap/modal';
import { PdfDocument } from '@syncfusion/ej2-pdf-export';
import { ɵangular_packages_platform_browser_dynamic_testing_testing_a } from '@angular/platform-browser-dynamic/testing';
import { TabsetComponent } from 'ngx-bootstrap/tabs';
import { ActivatedRoute, Router } from '@angular/router';
import { AsyncSettingsModel } from '@syncfusion/ej2-angular-inputs';
import { ToolService } from './tool.service';
import { ToastrService } from 'ngx-toastr';
import { Location } from '@angular/common';
import { interval, Subscription } from 'rxjs';

Diagram.Inject(UndoRedo, DataBinding, HierarchicalTree, LayoutAnimation, BpmnDiagrams);
SymbolPalette.Inject(BpmnDiagrams);
/**
 * Default FlowShape sample
 */

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.css'],
  encapsulation: ViewEncapsulation.None
})


export class AppComponent implements OnInit{
  @ViewChild('diagram')
  //Diagram Properties
  public diagram: DiagramComponent;
  getimg:any = {};
  zoominsize:any = 1;
  zoomoutsize:any = {};
  getcolor:any = {};
  public zoom: any = {};
  title:any;
  public tool: DiagramTools = DiagramTools.ZoomPan;
  selArray:any = [];
  clickargs:any = '';
  dragenterargs:any ='';
  delid:any = '';
  clickarray: any = [];
  selectElement:any = {};
  clrchng:any = {};
  templatevalue:any = {};
  rotatevalue = 0;
  showfill:Boolean = true;
  showlinefill:Boolean = true;
  showgridcolorpalette:Boolean = true;
  showgridbackground:Boolean = true;
  templatevaluestyle:any = {};
  selectedgradient:any = {};
  showgradientcolor:boolean;
  selectedborder:any = {};
  showselectedtab:Boolean;
  tempannotation:any = {};
  selectedfont:any = {};
  tempmargin:any = {};
  snapSettings123:any = {};
  fullscreenmobilepalette:any = {};
  fullscreeneditorpalette:any = {};
  modalRef?: BsModalRef;
  showoutlinebox:any;
  showundo:boolean = false;
  showredo:boolean = false;
  showpanel:Boolean = true;
  gradcolor:any;
  gradfill:any = '#0db1e7';
  getgradcolor:any = {};
  hidepanel:Boolean = false;
  showcolorpalette:Boolean = false;
  routerurl:any;
  initTab: Number = 0;
  getcsrf:any;
  texttab:boolean;
  jsonfile:any;
  getallcomments:any = [];
  getproject:any;
  commentdata:any = {};
  notedata:any = {};
  getallargs:any = {};
  shapeid:any = {};
  getowncomments:any = [];
  getusercomments:any = [];
  notesdata:any = {};
  getid:any = {};
  gettype:any = {};
  getauth:any = {};
  getcharttype:any = {};
  image:any = {};
  chartdata:any = {};
  hidenotesandcomments:boolean = true;
  chartjson:any = {};
  flowchartlist:any = [];
  filename:any = {};
  linkname:any = '';
  getdata:any = {};
  getnavigateid:any = {};
  flowchartdetails:any = {};
  hidecomment:Boolean = true;
  hidesaveoption:Boolean = true;
  getuserid:any = {};
  getidurl:any = {};
  flowchartlistwith:any = [];
  select:any = {};
  autosavefalse:boolean = true;
  orderid:number;
  navtab:any = {};
  hideallqrcode:boolean = true;
  getnotifyurl:any = {};
  commentid:any = {};
  @ViewChild('Rightsidetabs',{ static: false }) Rightsidetabs: TabsetComponent;   
  @ViewChild('CommentsTab', {static : false}) CommentsTab : TabsetComponent; 
  public contextMenuSettings: ContextMenuSettingsModel
  constructor(private cdr:ChangeDetectorRef, 
              private modalService: BsModalService,
              // private mScrollbarService: MalihuScrollbarService,
              private toastr : ToastrService,
              private route : ActivatedRoute, 
              private location : Location,
              private toolservice : ToolService,
              private router : Router) {
    this.fullscreenmobilepalette = 'sb-mobile-palette';
    this.fullscreeneditorpalette = 'editor_style';
    // this.showoutlinebox = '';
    this.gradfill = 'white';
  ​​​​​​​}
  public terminator: FlowShapeModel = { type: 'Flow', shape: 'Terminator' };
  public process: FlowShapeModel = { type: 'Flow', shape: 'Process' };
  public decision: FlowShapeModel = { type: 'Flow', shape: 'Decision' };
  public data: FlowShapeModel = { type: 'Flow', shape: 'Data' };
  public directdata: FlowShapeModel = { type: 'Flow', shape: 'DirectData' };
  // public shadow: ShadowModel;
  public margin: MarginModel = { left: 25, right: 25 };
  public connAnnotStyle: TextStyleModel = { fill: '#ffffff' };
  public strokeStyle: StrokeStyleModel = { strokeDashArray: '2,2' };
  public constraints: NodeConstraints;
  public segments: OrthogonalSegmentModel = [{ type: 'Orthogonal', direction: 'Top', length: 120 }];
  public segments1: OrthogonalSegmentModel = [
    { type: 'Orthogonal', direction: 'Right', length: 100 }
  ];
  // public scrollSettings: ScrollSettingsModel = {  };
  // scrollLimit: 'Infinity'
  public continuousDraw: boolean = false;
  public project_id:any;
  public data1: Object = {
    crudAction: {
       //Url which triggers the server side DeleteNodes method
        destroy: 'https://ej2services.syncfusion.com/development/web-services/api/Crud/DeleteNodes',
    },
    connectionDataSource: {
    crudAction: {
         //Url which triggers the server side DeleteConnectors method
         destroy: 'https://ej2services.syncfusion.com/development/web-services/api/Crud/DeleteConnectors',
      }
    }
  };

  public nodeDefaults(node: NodeModel): NodeModel {
    let obj: NodeModel = {};
    console.log(obj,"***********************");
    if (obj.width === undefined) {
      obj.width = 145;
    } else {
      let ratio: number = 100 / obj.width;
      obj.width = 100;
      // obj.height *= ratio;
    }
    obj.style = { fill: '#357BD2', strokeColor: '#ffffff' };
    obj.annotations = [{ style: { color: '#ffffff', fill: '#ffffff00' }}];
    obj.shadow = {angle : 45, color : 'grey', opacity: 0.5, distance : 45};
    obj.ports = getPorts(node);
    return obj;
  }
  public connDefaults(obj: Connector): void {
    if (obj.id.indexOf('connector') !== -1) {
      obj.type = 'Orthogonal';
      obj.targetDecorator = { shape: 'Arrow', width: 10, height: 10 };
    }
  }

  ngOnInit(){
    this.select = 'Select';
    document.getElementById('appearance').onclick = this.documentClick.bind(this);
    
    this.getcharttype = window.location.pathname.split("/")[1];
    console.log(this.getcharttype,"this.getcharttype");
    console.log(this.getuserid,"this.getuserid");
    if(this.getcharttype == 'flowchart'){
      var getuser = '/angular-tool-user';
      this.toolservice.getData(getuser).subscribe(getdata => {
        this.getuserid = getdata;
        this.getuserid = this.getuserid.id;
      });
    } else if(this.getcharttype == 'RU-flowchart'){
      var getuser = '/RU-angular-tool-user';
      this.toolservice.getData(getuser).subscribe(getdata => {
        this.getuserid = getdata;
        this.getuserid = this.getuserid.id;
      });
    } else if(this.getcharttype == 'SA-flowchart'){
      var getuser = '/SA-angular-tool-user';
      this.toolservice.getData(getuser).subscribe(getdata => {
        this.getuserid = getdata;
        this.getuserid = this.getuserid.id;
      });
    }
    if(this.location.path().split("=")[1]){
      this.getidurl = this.location.path().split("=")[1];
      var typeobj = this.location.path().split("?")[1];
      this.gettype = typeobj.split("=")[0];
      this.getid = this.getidurl.split("&")[0];
      console.log(this.getidurl,typeobj,this.gettype);
      if(this.getidurl.split("&")[1] == 'process'){
        this.autosavefalse = false;
        console.log(this.getid,"this.getid");
        console.log(this.location.path().split("=")[2]);
        if(this.location.path().split("=")[2] == 'received'){
          this.hidenotesandcomments = false;
          if(this.getcharttype == 'RU-flowchart'){
            var notpath = '/RU-angular-tool-sharednotification/' + this.getid;
            this.toolservice.getData(notpath).subscribe(receiveddata => {
              console.log(receiveddata,"receiveddata");
            });
          } else if(this.getcharttype == 'flowchart'){
            var notpath = '/angular-tool-sharednotification/' + this.getid;
            this.toolservice.getData(notpath).subscribe(receiveddata => {
              console.log(receiveddata,"receiveddata");
            });
          }
        } else if(this.location.path().split("=")[2] == 'qrcode'){
            this.hideallqrcode = false;
            this.autosavefalse = false;
            // if(this.getcharttype == 'flowchart'){
            //   var path = '/angular-tool-comment-noti/' + this.getuserid + '/' + this.getid + '/' + this.commentid;
            //   this.toolservice.getData(path).subscribe(data => {
            //     console.log(data,"/angular-tool-comment-noti");
            //   });
            // } else if(this.getcharttype == 'RU-flowchart'){
            //   var path = '/RU-angular-tool-comment-noti/' + this.getuserid + '/' + this.getid + '/' + this.commentid;
            //   this.toolservice.getData(path).subscribe(data => {
            //     console.log(data,"/RU-angular-tool-comment-noti");
            //   });
            // }
        } else if(this.location.path().split("=")[2] == 'export'){
        setTimeout(() =>{            
          this.openexport();
        },1000);
        this.hidesaveoption = false;
        this.autosavefalse = false;
        
      }
      var geteditchart = '/angular-tool-edit-flowchart/' + this.getid + '/' + this.gettype;
      console.log(geteditchart,"geteditchart");
      this.toolservice.getData(geteditchart).subscribe(chartdata => {
        if(chartdata != null){
          // this.toolservice.startLoader();
          console.log(chartdata,"chartdata");
          this.chartdata = chartdata;
          this.filename = this.chartdata.flowchart_name;
          if((this.filename == null) || (this.filename == undefined) || (this.filename == undefined)){
            this.filename = "Untitled";
          }
          this.chartjson = this.chartdata.flowchart_json;
          if(chartdata != null){
            console.log(this.diagram.loadDiagram(this.chartjson));
            this.diagram.loadDiagram(this.chartjson);
          }
          // this.toolservice.stopLoader();
        }
      });
      }else if(this.getidurl.split("&")[1]){
        this.getnotifyurl = this.getidurl.split("&")[1];
        if(this.getnotifyurl.split("-")[0] == 'notif'){
          this.autosavefalse = false;
          var notifid = this.getnotifyurl.split("-")[1];
          if(this.getcharttype == 'flowchart'){
            var testurl = '/angular-tool-noti-update/' + notifid;
            this.toolservice.getData(testurl).subscribe(data => {
              console.log(data,"this.data");
            });
          } else if(this.getcharttype == 'RU-flowchart'){
            var testurl = '/RU-angular-tool-noti-update/' + notifid;
            this.toolservice.getData(testurl).subscribe(data => {
              console.log(data,"this.data");
            });
          }
        }
      } else {
      }
      console.log(this.getid,"this.getid");
    }
    
    
    if(this.getcharttype == 'NA-flowchart'){
      this.hidenotesandcomments = false;
      var geteditchart = '/angular-tool-edit-flowchart/' + this.getid + '/' + this.gettype;
      console.log(geteditchart,"geteditchart");
      this.toolservice.getData(geteditchart).subscribe(chartdata => {
        if(chartdata != null){
          // this.toolservice.startLoader();
          console.log(chartdata,"chartdata");
          this.chartdata = chartdata;
          this.filename = this.chartdata.flowchart_name;
          if((this.filename == null) || (this.filename == undefined) || (this.filename == undefined)){
            this.filename = "Untitled";
          }
          this.chartjson = this.chartdata.flowchart_json;
          if(chartdata != null){
            console.log(this.diagram.loadDiagram(this.chartjson));
            this.diagram.loadDiagram(this.chartjson);
          }
          // this.toolservice.stopLoader();
        }
      });
    }
    if(this.getcharttype == 'flowchart'){
    var geteditchart = '/angular-tool-edit-flowchart/' + this.getid + '/' + this.gettype;
    console.log(geteditchart,"geteditchart");
    this.toolservice.getData(geteditchart).subscribe(chartdata => {
      if(chartdata != null){
        // this.toolservice.startLoader();
        console.log(chartdata,"chartdata");
        this.chartdata = chartdata;
        this.filename = this.chartdata.flowchart_name;
        if((this.filename == null) || (this.filename == undefined) || (this.filename == undefined)){
          this.filename = "Untitled";
        }
        this.chartjson = this.chartdata.flowchart_json;
        if(chartdata != null){
          this.diagram.loadDiagram(this.chartjson);
          this.autosaveinedit();
        }
        // this.toolservice.stopLoader();
      }
    });
      var getallcomments = '/angular-tool-comments-list/' + this.getid;
      this.toolservice.getData(getallcomments).subscribe(data => {
        // this.toolservice.startLoader();
        this.getowncomments = [];
        this.getusercomments = [];
        this.getallcomments = data;
        this.getauth = this.getallcomments.Authuser;
        this.getallcomments = this.getallcomments.comments;
        for(let i=0; i<this.getallcomments.length;i++){
          if(this.getallcomments[i].user_id == this.getauth){
            this.getowncomments.push(this.getallcomments[i]);
          } else {
            this.getusercomments.push(this.getallcomments[i]);
          }
        }
        // this.toolservice.stopLoader();
      });

      var getflowchartname = '/angular-tool-flowchart-name/' + this.getid + '/' + this.gettype;
      this.toolservice.getData(getflowchartname).subscribe(data => {
        console.log(data,"this.flowchartdetails");
        // this.toolservice.startLoader();
        this.flowchartdetails = data;
        this.filename = this.flowchartdetails.flowchart_name;
        if((this.filename == null) || (this.filename == undefined) || (this.filename == undefined)){
          this.filename = "Untitled";
        }
        // this.flowchartlist = this.flowchartdetails.flowchart_list;
        // this.getdata.chartvaluename = this.flowchartlist[0].id;
        // console.log(this.flowchartlist,"this.flowchartlist");
        // this.toolservice.stopLoader();
      });

    } else if(this.getcharttype == 'SA-flowchart'){
      this.hidecomment = false;
      var geteditchart = '/SA-angular-tool-edit-flowchart/' + this.getid + '/' + this.gettype;
    console.log(geteditchart,"geteditchart");
    this.toolservice.getData(geteditchart).subscribe(chartdata => {
      // this.toolservice.startLoader();
      if(chartdata != null){
        console.log(chartdata,"chartdata");
        this.chartdata = chartdata;
        this.filename = this.chartdata.flowchart_name;
        if((this.filename == null) || (this.filename == undefined) || (this.filename == undefined)){
          this.filename = "Untitled";
        }
        this.chartjson = this.chartdata.flowchart_json;
        if(chartdata != null){
          this.diagram.loadDiagram(this.chartjson);
          this.autosaveinedit();
        }
      }
      // this.toolservice.stopLoader();
    });
      var getallcomments = '/SA-angular-tool-comments-list/' + this.getid;
      this.toolservice.getData(getallcomments).subscribe(data => {
        // this.toolservice.startLoader();
        this.getowncomments = [];
        this.getusercomments = [];
        this.getallcomments = data;
        this.getauth = this.getallcomments.Authuser;
        this.getallcomments = this.getallcomments.comments;
        for(let i=0; i<this.getallcomments.length;i++){
          if(this.getallcomments[i].user_id == this.getauth){
            this.getowncomments.push(this.getallcomments[i]);
          } else {
            this.getusercomments.push(this.getallcomments[i]);
          }
        }
        // this.toolservice.stopLoader();
      });

      var getflowchartname = '/SA-angular-tool-flowchart-name/' + this.getid + '/' + this.gettype;
      this.toolservice.getData(getflowchartname).subscribe(data => {
        // this.toolservice.startLoader();
        console.log(data,"this.flowchartdetails");
        this.flowchartdetails = data;
        this.filename = this.flowchartdetails.flowchart_name;
        if((this.filename == null) || (this.filename == undefined) || (this.filename == undefined)){
          this.filename = "Untitled";
        }
        // this.flowchartlist = this.flowchartdetails.flowchart_list;
        // this.getdata.chartvaluename = this.flowchartlist[0].id;
        // console.log(this.flowchartlist,"this.flowchartlist");
        // this.toolservice.stopLoader();
      });

    } else if(this.getcharttype == 'RU-flowchart'){
      var geteditchart = '/RU-angular-tool-edit-flowchart/' + this.getid + '/' + this.gettype;
    console.log(geteditchart,"geteditchart");
    this.toolservice.getData(geteditchart).subscribe(chartdata => {
      // this.toolservice.startLoader();
      if(chartdata != null){
        console.log(chartdata,"chartdata");
        this.chartdata = chartdata;
        this.filename = this.chartdata.flowchart_name;
        this.chartjson = this.chartdata.flowchart_json;
        if(chartdata != null){
          this.diagram.loadDiagram(this.chartjson);
          this.autosaveinedit();
        }
      }
      // this.toolservice.stopLoader();
    });
      var getallcomments = '/RU-angular-tool-comments-list/' + this.getid;
      this.toolservice.getData(getallcomments).subscribe(data => {
        // this.toolservice.startLoader();
        this.getowncomments = [];
        this.getusercomments = [];
        this.getallcomments = data;
        this.getauth = this.getallcomments.Authuser;
        this.getallcomments = this.getallcomments.comments;
        for(let i=0; i<this.getallcomments.length;i++){
          if(this.getallcomments[i].user_id == this.getauth){
            this.getowncomments.push(this.getallcomments[i]);
          } else {
            this.getusercomments.push(this.getallcomments[i]);
          }
        }
        // this.toolservice.stopLoader();
      });

      var getflowchartname = '/RU-angular-tool-flowchart-name/' + this.getid + '/' + this.gettype;
      this.toolservice.getData(getflowchartname).subscribe(data => {
        console.log(data,"this.flowchartdetails");
        // this.toolservice.startLoader();
        this.flowchartdetails = data;
        this.filename = this.flowchartdetails.flowchart_name;
        if((this.filename == null) || (this.filename == undefined) || (this.filename == undefined)){
          this.filename = "Untitled";
        }
        // this.flowchartlist = this.flowchartdetails.flowchart_list;
        // this.getdata.chartvaluename = this.flowchartlist[0].id;
        // console.log(this.flowchartlist,"this.flowchartlist");
        // this.toolservice.stopLoader();
      });
  
    }
    this.getmaplist();
    this.contextMenuSettings = {
      show: true,
    }
  }

  openexport(){
    // this.toolservice.startLoader();
    var exportbutton= document.getElementById("exportfile");
    exportbutton.click();
    // this.toolservice.stopLoader();
  }

  getmaplist(){
    if(this.getcharttype == 'flowchart'){
      var flochartpath = '/angular-tool-withoutfclist/' + this.getid;
      this.toolservice.getData(flochartpath).subscribe(data =>{
        console.log(data,"getmaplistdata");
        this.flowchartlist = data;
        this.flowchartlist = this.flowchartlist.flowchartlist_without;
        
      });
      var flochartlinkpath = '/angular-tool-withfclist/' + this.getid;
      this.toolservice.getData(flochartlinkpath).subscribe(data =>{
        console.log(data,"getlinklistdata");
        this.flowchartlistwith = data;
        this.flowchartlistwith = this.flowchartlistwith.flowchartlist_with;
      });
      this.getdata.chartvaluename = "0";
    } else if(this.getcharttype == 'RU-flowchart'){
      var flochartpath = '/RU-angular-tool-withoutfclist/' + this.getid;
      this.toolservice.getData(flochartpath).subscribe(data =>{
        console.log(data,"getmaplistdata");
        this.flowchartlist = data;
        this.flowchartlist = this.flowchartlist.flowchartlist_without;
        
      });
      var flochartlinkpath = '/RU-angular-tool-withfclist/' + this.getid;
      this.toolservice.getData(flochartlinkpath).subscribe(data =>{
        console.log(data,"getlinklistdata");
        this.flowchartlistwith = data;
        this.flowchartlistwith = this.flowchartlistwith.flowchartlist_with;
      });
      this.getdata.chartvaluename = "0";
    } else if(this.getcharttype == 'SA-flowchart'){
      // var flochartpath = '/SA-angular-tool-withoutfclist/' + this.getid;
      // this.toolservice.getData(flochartpath).subscribe(data =>{
      //   console.log(data,"getmaplistdata");
      //   this.flowchartlist = data;
      //   this.flowchartlist = this.flowchartlist.flowchartlist_without;
        
      // });
      // var flochartlinkpath = '/SA-angular-tool-withfclist/' + this.getid;
      // this.toolservice.getData(flochartlinkpath).subscribe(data =>{
      //   console.log(data,"getlinklistdata");
      //   this.flowchartlistwith = data;
      //   this.flowchartlistwith = this.flowchartlistwith.flowchartlist_with;
      // });
      // this.getdata.chartvaluename = "0";
    }
  }

  getchartname(id:any){
    this.getnavigateid = id;
    if(this.getcharttype == 'flowchart'){
      var apipath = '/angular-tool-fc/' + this.getid + '/' + id + '/' + this.getid;
      this.toolservice.getData(apipath).subscribe(data => {
        console.log(data,"getchartname");
        this.getmaplist();
        this.toastr.success("Flowchart Mapped Successfully");
      });
    } else if(this.getcharttype == 'RU-flowchart'){
      var apipath = '/RU-angular-tool-fc/' + this.getid + '/' + id + '/' + this.getid;
      this.toolservice.getData(apipath).subscribe(data => {
        console.log(data,"getchartname");
        this.getmaplist();
        this.toastr.success("Flowchart Mapped Successfully");
      });
    } else if(this.getcharttype == 'SA-flowchart'){
      var apipath = '/SA-angular-tool-fc/' + this.getid + '/' + id + '/' + this.getid;
      this.toolservice.getData(apipath).subscribe(data => {
        console.log(data,"getchartname");
        this.getmaplist();
        this.toastr.success("Flowchart Mapped Successfully");
      });
    }
  }

  addlink(template2: TemplateRef<any>){
    this.modalRef = this.modalService.show(template2);
    
  }

  savelink(linkname:any){
    console.log(linkname,"linkname");
    this.clrchng[0].annotations[0].hyperlink.link = linkname;
    // this.clrchng[0].annotations[0].hyperlink.content = "hello";
    console.log(this.clrchng[0].annotations[0]);
    this.modalRef.hide();
    this.linkname = '';
  }

  map_navigate(id:any){
    
    this.getcharttype; 
    var proid = this.router.url.split("?")[1];
    this.getproject = proid.split("=")[0];
    this.navtab = 'https://apps.kaizenhub.ca/' + this.getcharttype + '/?' + this.getproject + '=' + id;
    // this.navtab = 'http://127.0.0.1:8000/' + this.getcharttype + '/?' + this.getproject + '=' + id;
    console.log(this.navtab,"this.navtab");
    window.open(this.navtab,"_blank");   
  }

  addsidepalette(){
    if($('#palette-space').hasClass('sb-mobile-palette-open')){
        $('#palette-space').removeClass("sb-mobile-palette-open");  
    } else {
      $('#palette-space').addClass("sb-mobile-palette-open");  
    }
  }

  gotoTab(tab_id:any){
    this.showselectedtab = true;
    let _self = this;
    setTimeout(function(){            
      console.log(_self.Rightsidetabs,"this.rightsidetabs");
      _self.Rightsidetabs.tabs[tab_id].active = true;     
    },200);
    
  }

  public toolbarEditorClick(args: ClickEventArgs): void {
    let printOptions: IExportOptions = {};
    if (args.item.text === 'Save as PDF') {
        printOptions.mode = 'Data';
        printOptions.region = 'PageSettings';
        printOptions.multiplePage = this.multiplePage;
        printOptions.pageHeight = 400;
        printOptions.pageWidth = 400;   
        this.diagram.print(printOptions);
    } else if(args.item.text === 'Load') {
      document.getElementsByClassName('e-file-select-wrap')[0].querySelector('button').click();
  } 
}


public asyncSettings: AsyncSettingsModel = {
  saveUrl: 'https://aspnetmvc.syncfusion.com/services/api/uploadbox/Save',
  removeUrl: 'https://aspnetmvc.syncfusion.com/services/api/uploadbox/Remove'
};

public loadDiagram(event: ProgressEvent): void {
  this.diagram.loadDiagram((event.target as FileReader).result.toString());
}

public multiplePage: boolean = false;

public exportTypes: ItemModel[] = [
  { text: 'JPG' }, { text: 'PNG' },
  { text: 'BMP' }, { text: 'SVG' }
];

public onselect(args: MenuEventArgs): void {
  let exportOptions: IExportOptions = {};
  exportOptions.format = args.item.text as FileFormats;
  exportOptions.mode = 'Download';
  exportOptions.region = 'PageSettings';
  exportOptions.multiplePage = this.multiplePage;
  exportOptions.fileName = 'Export';
  exportOptions.pageHeight = 400;
  exportOptions.pageWidth = 400;
  this.diagram.exportDiagram(exportOptions);
  console.log(this.diagram.exportDiagram(exportOptions),"Exportoptions");
}

public documentClick(args: MouseEvent): void {
  let target: HTMLElement = args.target as HTMLElement;
  let drawingObject: NodeModel | ConnectorModel = null;
  if (target.className === 'image-pattern-style') {
      switch (target.id) {
          case 'text':
              drawingObject = { shape: { type: 'Text' } };
              break;
      }
      if (drawingObject) {
          this.diagram.drawingObject = drawingObject;
      }
  }
  console.log(this.diagram, "sdfsdfsdfsdfsdf", DiagramTools);
  this.diagram.tool = this.continuousDraw ? DiagramTools.ContinuousDraw : DiagramTools.DrawOnce;
  this.diagram.dataBind();
};

  public interval: number[] = [
    1, 9, 0.25, 9.75, 0.25, 9.75, 0.25, 9.75, 0.25, 9.75, 0.25,
    9.75, 0.25, 9.75, 0.25, 9.75, 0.25, 9.75, 0.25, 9.75
  ];

  public snapSettings: SnapSettingsModel = {
    horizontalGridlines: { lineColor: '#8e9c8e', lineIntervals: this.interval },
    verticalGridlines: { lineColor: '#8e9c8e', lineIntervals: this.interval }
  };

  public dragEnter(args: IDragEnterEventArgs): void {
    this.dragenterargs = [];
    console.log(this.diagram);
    this.dragenterargs = args;
    if(args != undefined){
      let obj: NodeModel = args.element as NodeModel;
      if (obj && obj.width && obj.height) {
        let oWidth: number = obj.width;
        let oHeight: number = obj.height;
        let ratio: number = 100 / obj.width;
        obj.width = 100;
        obj.height *= ratio;
        obj.offsetX += (obj.width - oWidth) / 2;
        obj.offsetY += (obj.height - oHeight) / 2;
        console.log(obj.shadow);
        obj.shadow = {angle : 45, color : 'grey', opacity: 0.5, distance : 45};
        console.log(obj,"Object");
        obj.style = { fill: '#357BD2', strokeColor: '#ffffff'};
      }
    } else {

    }
    this.showundo = true;
  }

  //SymbolPalette Properties
  public symbolMargin: MarginModel = { left: 15, right: 15, top: 15, bottom: 15 };
  public expandMode: ExpandMode = 'Multiple';
  //Initialize the basic shapes for the symbol palette
  public basicshapes: NodeModel[] = [
    {
       id : 'Rectangle', shape: { type: 'Basic', shape: 'Rectangle' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Ellipse', shape: { type: 'Basic', shape: 'Ellipse' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Triangle',shape: { type: 'Basic', shape: 'Triangle' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Plus',shape: { type: 'Basic', shape: 'Plus' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Star',shape: { type: 'Basic', shape: 'Star' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Pentagon',shape: { type: 'Basic', shape: 'Pentagon' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Heptagon',shape: { type: 'Basic', shape: 'Heptagon' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Octagon',shape: { type: 'Basic', shape: 'Octagon' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Trapezoid',shape: { type: 'Basic', shape: 'Trapezoid' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Decagon',shape: { type: 'Basic', shape: 'Decagon' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'RightTriangle',shape: { type: 'Basic', shape: 'RightTriangle' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
      id : 'Parallelogram',shape: { type: 'Basic', shape: 'Parallelogram' },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
];


  //Initialize the flowshapes for the symbol palatte
  private flowshapes: NodeModel[] = [
    { id: 'Terminator', shape: { type: 'Flow', shape: 'Terminator' },  
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Process', shape: { type: 'Flow', shape: 'Process' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Decision', shape: { type: 'Flow', shape: 'Decision' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Document', shape: { type: 'Flow', shape: 'Document' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'PreDefinedProcess', shape: { type: 'Flow', shape: 'PreDefinedProcess' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'PaperTap', shape: { type: 'Flow', shape: 'PaperTap' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'DirectData', shape: { type: 'Flow', shape: 'DirectData' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'SequentialData', shape: { type: 'Flow', shape: 'SequentialData' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Sort', shape: { type: 'Flow', shape: 'Sort' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'MultiDocument', shape: { type: 'Flow', shape: 'MultiDocument' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Collate', shape: { type: 'Flow', shape: 'Collate' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'SummingJunction', shape: { type: 'Flow', shape: 'SummingJunction' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Or', shape: { type: 'Flow', shape: 'Or' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    {
      id: 'InternalStorage',
      shape: { type: 'Flow', shape: 'InternalStorage' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ]
    },
    { id: 'Extract', shape: { type: 'Flow', shape: 'Extract' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    {
      id: 'ManualOperation',
      shape: { type: 'Flow', shape: 'ManualOperation' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ]
    },
    { id: 'Merge', shape: { type: 'Flow', shape: 'Merge' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    {
      id: 'OffPageReference',
      shape: { type: 'Flow', shape: 'OffPageReference' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ]
    },
    {
      id: 'SequentialAccessStorage',
      shape: { type: 'Flow', shape: 'SequentialAccessStorage' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ]
    },
    { id: 'Annotation', shape: { type: 'Flow', shape: 'Annotation' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] },
    { id: 'Annotation2', shape: { type: 'Flow', shape: 'Annotation2' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Data', shape: { type: 'Flow', shape: 'Data' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Card', shape: { type: 'Flow', shape: 'Card' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] },
    { id: 'Delay', shape: { type: 'Flow', shape: 'Delay' },
      ports: [
        { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
        { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
    ] }
  ];

  //Initializes connector symbols for the symbol palette
  private connectorSymbols: ConnectorModel[] = [
    {
      id: 'Link1',
      type: 'Orthogonal',
      sourcePoint: { x: 0, y: 0 },
      targetPoint: { x: 60, y: 60 },
      targetDecorator: { shape: 'Arrow', style: {strokeColor: '#120101', fill: '#120101'} },
      style: { strokeWidth: 1, strokeColor: '#120101' }
    },
    {
      id: 'link3',
      type: 'Orthogonal',
      sourcePoint: { x: 0, y: 0 },
      targetPoint: { x: 60, y: 60 },
      style: { strokeWidth: 1, strokeColor: '#120101' },
      targetDecorator: { shape: 'None' }
    },
    {
      id: 'Link21',
      type: 'Straight',
      sourcePoint: { x: 0, y: 0 },
      targetPoint: { x: 60, y: 60 },
      targetDecorator: { shape: 'Arrow', style: {strokeColor: '#120101', fill: '#120101'} },
      style: { strokeWidth: 1, strokeColor: '#120101' }
    },
    {
      id: 'link23',
      type: 'Straight',
      sourcePoint: { x: 0, y: 0 },
      targetPoint: { x: 60, y: 60 },
      style: { strokeWidth: 1, strokeColor: '#120101' },
      targetDecorator: { shape: 'None' }
    },
    {
      id: 'link33',
      type: 'Bezier',
      sourcePoint: { x: 0, y: 0 },
      targetPoint: { x: 60, y: 60 },
      style: { strokeWidth: 1, strokeColor: '#120101' },
      targetDecorator: { shape: 'None' }
    }
  ];

  private waypoints: ConnectorModel[] = [
    {
      id: 'Link2', 
      type: 'Orthogonal', 
      sourcePoint: { x: 0, y: 0 }, 
      targetPoint: { x: 40, y: 40 },
      targetDecorator: { 
        shape: 'Arrow', 
        style: {strokeColor: '#120101', fill: '#120101'} 
      }, 
      style: { strokeWidth: 1, strokeDashArray: '4 4', strokeColor: '#120101' }
    },
    {
        id: 'Link22', 
        type: 'Straight', 
        sourcePoint: { x: 0, y: 0 }, 
        targetPoint: { x: 60, y: 60 },
        targetDecorator: { 
          shape: 'Arrow', 
          style: {strokeColor: '#120101', fill: '#120101'} 
        }, 
        style: { strokeWidth: 1, strokeDashArray: '4 4', strokeColor: '#120101' }
    },
    {
      id: 'Link4', 
      type: 'Orthogonal', 
      sourcePoint: { x: 0, y: 0 }, 
      targetPoint: { x: 10, y: 10 },
      targetDecorator: { shape: 'Arrow', style: {strokeColor: '#120101', fill: '#120101'} }, 
      style: { strokeWidth: 1, strokeDashArray: '4,4', strokeColor: '#120101' }
  }
  ];
 
  public bpmnShapes: NodeModel[] = [
    {
        id: 'Start', width: 35, height: 35, shape: {
            type: 'Bpmn', shape: 'Event',
            event: { event: 'Start' },
        },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
        id: 'NonInterruptingIntermediate', width: 35, height: 35, shape: {
            type: 'Bpmn', shape: 'Event',
            event: { event: 'NonInterruptingIntermediate' }
        },ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
        id: 'End', width: 35, height: 35, offsetX: 665, offsetY: 230, shape: {
            type: 'Bpmn', shape: 'Event',
            event: { event: 'End' }
        },ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
        id: 'Task', width: 35, height: 35, offsetX: 700, offsetY: 700,
        shape: {
            type: 'Bpmn', shape: 'Activity', activity: {
                activity: 'Task',
            },
        },ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
        id: 'Transaction', width: 35, height: 35, offsetX: 300, offsetY: 100,
        constraints: NodeConstraints.Default | NodeConstraints.AllowDrop,
        shape: {
            type: 'Bpmn', shape: 'Activity',
            activity: {
                activity: 'SubProcess', subProcess: {
                    type: 'Transaction', transaction: {
                        cancel: { visible: false }, failure: { visible: false }, success: { visible: false }
                    }
                }
            }
        },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    }, 
    {
        id: 'Task_Service', width: 35, height: 35, offsetX: 700, offsetY: 700,
        shape: {
            type: 'Bpmn', shape: 'Activity', activity: {
                activity: 'Task', task: { type: 'Service' }
            },
        },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
        id: 'Gateway', width: 35, height: 35, offsetX: 100, offsetY: 100,
        shape: { type: 'Bpmn', shape: 'Gateway', gateway: { type: 'Exclusive' } as BpmnGatewayModel },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
        id: 'DataObject', width: 35, height: 35, offsetX: 500, offsetY: 100,
        shape: { type: 'Bpmn', shape: 'DataObject', dataObject: { collection: false, type: 'None' } },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
    {
        id: 'subProcess', width: 520, height: 250, offsetX: 355, offsetY: 230,
        constraints: NodeConstraints.Default | NodeConstraints.AllowDrop,
        shape: {
            shape: 'Activity', type: 'Bpmn',
            activity: {
                activity: 'SubProcess', subProcess: {
                    type: 'Transaction', collapsed: false,
                    processes: [], transaction: {
                        cancel: { visible: false }, failure: { visible: false }, success: { visible: false }
                    }
                }
            }
        },
        ports: [
          { offset: { x: 0, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 0 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 1, y: 0.5 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw },
          { offset: { x: 0.5, y: 1 }, visibility: PortVisibility.Connect | PortVisibility.Hover, constraints: PortConstraints.Draw }
      ] 
    },
  ];
  getargs:any;
  public paletteExpanding(args : Object) {
    console.log(args,"args************************");
    this.getargs = args;
  
  };
  public palettes: PaletteModel[] = [
    {
      id: 'basic',
      expanded : true,
      symbols : this.basicshapes,
      iconCss : 'shapes',
      title : 'Basic Shapes',
    },
    {
      id: 'flow',
      expanded: false,
      symbols: this.flowshapes,
      iconCss: 'shapes',
      title: 'Flow Shapes'
    },
    {
      id: 'bpmn',
      expanded: false,
      symbols: this.bpmnShapes,
      iconCss: 'shapes',
      title: 'BPMN Shapes'
    },
    {
      id: 'connectors',
      expanded: false,
      symbols: this.connectorSymbols,
      iconCss: 'shapes',
      title: 'Connectors'
    },
    {
      id: 'WayPoints',
      expanded: false,
      symbols: this.waypoints,
      iconCss: 'shapes',
      title: 'Way Points'
    }
  ];

  showoutlineclick(){
    if(this.showoutlinebox == 'dispblock'){
      this.showoutlinebox = 'dispnone';
    } else {
      this.showoutlinebox = 'dispblock';
    }
  }

  public getSymbolInfo(_symbol: NodeModel): SymbolInfo {
    // console.log(_symbol,"_symbol");
    return { fit: true };
  }

  gettoolvalue(template2 : TemplateRef<any>){
    this.selectElement = document.querySelector('#toolvalue');
    console.log(this.selectElement.value);
    if(this.selectElement.value == 'Addlink'){
      this.addlink(template2);
    } else if(this.selectElement.value == 'Image'){
      document.getElementsByClassName('e-file-select-wrap')[0].querySelector('button').click();
    } else if(this.selectElement.value == 'Text'){
      // document.getElementById('appearance').click();
      document.getElementById('text').click();
    }
    this.selectElement.value = '0';
  }

  clicktext(){
    document.getElementById('text').click();
    console.log("condition true");
  }

  clickimage(){
    this.toolservice.startLoader();
      document.getElementsByClassName('e-file-select-wrap')[0].querySelector('button').click();
    this.toolservice.stopLoader();
  }
  
  public getSymbolDefaults(symbol: NodeModel): void {
    // this.getcolor = symbol.style;
    // this.getcolor.strokeColor = '#757575';
    if (symbol.id === 'Terminator' || symbol.id === 'Process') {
      symbol.width = 80;
      symbol.height = 40;
    } else if (
      symbol.id === 'Decision' ||
      symbol.id === 'Document' ||
      symbol.id === 'PreDefinedProcess' ||
      symbol.id === 'PaperTap' ||
      symbol.id === 'DirectData' ||
      symbol.id === 'MultiDocument' ||
      symbol.id === 'Data'
    ) {
      symbol.width = 50;
      symbol.height = 40;
    } else {
      symbol.width = 50;
      symbol.height = 50;
    }
  }

  public clicks(args: IDragEnterEventArgs): void {
    this.clickarray = [];
    // this.clickargs = '';
    console.log(this.snapSettings);
    this.showundo = true;
    if(args != undefined){
      this.clickarray.push(args);
      this.dragenterargs = '';
      if(this.clickarray[0].actualObject != undefined){
        this.clickargs = this.clickarray[0];  
        this.dragenterargs = this.clickarray[0];
      } 
    }else {

    }
  }

  public selectionChange(_args: ISelectionChangeEventArgs): void {
    console.log(_args,"testing args");
    console.log(_args.newValue.length);
    this.getallargs = _args;
    if(this.getallargs.newValue.length > 0){
      this.shapeid = this.getallargs.newValue[0].id;
      console.log(this.shapeid,"this.shapeid");
      this.getshapenotes(this.shapeid);
    }    
    if(_args.newValue.length >= 1){
      this.showselectedtab = true;
      this.clrchng = _args.newValue;
      this.templatevalue = this.clrchng[0];
      this.templatevaluestyle = this.templatevalue.style;
      this.tempannotation = this.clrchng[0].annotations[0].style;
      this.tempmargin = this.clrchng[0].annotations[0].margin;
    } else if(_args.newValue.length == 0) {
      this.showselectedtab = false;
      let _self = this;
      setTimeout(function(){            
        console.log(_self.CommentsTab,"this.CommentsTab");
        _self.CommentsTab.tabs[0].active = true;     
      },200);
      console.log(_args);
    }
    console.log(this.clrchng,"this.clrchng");
  }

  undo(){
    this.diagram.undo();
    this.showredo = true;
  }

  redo(){
    this.diagram.redo();
    console.log(this.diagram.redo());
  }

  changecolor(event:any){
    console.log(event.currentValue.hex);
    console.log(this.clrchng);
    if(this.clrchng != ''){
      this.clrchng[0].style.fill = event.currentValue.hex;
      this.diagram.dataBind();
    } else {
  }
  }

  changegridcolor(event:any){
    this.diagram.snapSettings.horizontalGridlines.lineColor = event.currentValue.hex;
    this.diagram.snapSettings.verticalGridlines.lineColor = event.currentValue.hex;
  }

  changegridbackground(event:any){
    console.log(this.diagram);
    this.diagram.backgroundColor = event.currentValue.hex;
  }

  changegraidentcolor(event : any){
    console.log(event);
    this.selectedgradient = document.querySelector('#changegradientid');
    console.log(this.selectedgradient.value);
    this.clrchng[0].style.gradient.type = this.selectedgradient.value;
    this.clrchng[0].style.gradient.x1 = 0;
    this.clrchng[0].style.gradient.x2 = 50;
    this.clrchng[0].style.gradient.y1 = 0;
    this.clrchng[0].style.gradient.y2 = 50;
    this.gradcolor = this.clrchng[0].style.fill;
    this.clrchng[0].style.gradient.stops[0].color = event.currentValue.hex;
    this.gradfill = event.currentValue.hex;
    this.clrchng[0].style.gradient.stops =[
      {
          offset: 0,
          color : event.currentValue.hex
      },
      {
          color: this.gradcolor,
          offset: 200
      }
    ],
    this.diagram.dataBind();
  }

  changebordercolor(event:any){
    console.log(event.currentValue.hex);
    console.log(this.clrchng);
    if(this.clrchng != ''){
      this.clrchng[0].style.strokeColor =  event.currentValue.hex;
    } else {
  }
  }

  changefillcolor(event:any){
    
  }

  tofront(){
    this.diagram.bringToFront();
    this.clrchng[0].style.strokeDashArray = '2,2';
  }
  toback(){
    this.diagram.sendToBack();
  }

  getformatvalue(){
    this.selectElement = document.querySelector('#Formatpanel');
    console.log(this.selectElement.value);
    if(this.selectElement.value == 'Show Panel'){
      if(this.showpanel == true){
        this.showpanel = false;
      } else {
        this.showpanel = true;
      }
    } else if(this.selectElement.value == 'Outline'){
      this.showoutlinebox = 'dispblock';
    } else if(this.selectElement.value == 'Hide Panel'){
      this.showpanel = false;
    }
  }

  getzoomvalue(){
    console.log("this.diagram",this.diagram);
    this.selectElement = document.querySelector('#zoomvalue');
    console.log(this.selectElement.value);
    this.diagram.zoom(1.1,{
      x:100,
      y:100
    });
    if((this.selectElement.value == '100') || (this.selectElement.value == 'Reset')){
      // this.diagram.zoom(1.01, {
      //   x: 100,
      //   y: 100
      // });
      // this.diagram.zoom(1.01, {
      //   x: 100,
      //   y: 100
      // });
      this.diagram.pageSettings.fitOptions.canFit = true;
      this.diagram.pageSettings.fitOptions.canZoomIn = true;
    } else if(this.selectElement.value == '75') {
      this.diagram.zoom(0.75, {
        x: 100,
        y: 100
      });
      this.diagram.zoom(1.1, {
        x: 100,
        y: 100
      });
    } else if(this.selectElement.value == '50'){
     
      this.diagram.zoom(0.5, {
        x: 100,
        y: 100
      });
      this.diagram.zoom(1.1, {
        x: 100,
        y: 100
      });
    } else if(this.selectElement.value == '25'){
   
      this.diagram.zoom(0.25, {
        x: 100,
        y: 100
      });
      this.diagram.zoom(1.1, {
        x: 100,
        y: 100
      });
    }
  }
  
  changegradient(){
    this.selectedgradient = document.querySelector('#changegradientid');
    console.log(this.selectedgradient.value);
    this.clrchng[0].style.gradient.type = this.selectedgradient.value;
    this.clrchng[0].style.gradient.x1 = 0;
    this.clrchng[0].style.gradient.x2 = 50;
    this.clrchng[0].style.gradient.y1 = 0;
    this.clrchng[0].style.gradient.y2 = 50;
    this.gradcolor = this.clrchng[0].style.fill;
    if(this.gradfill != '' ){
      this.clrchng[0].style.gradient.stops =[
        {
            color: this.gradfill,
            offset: 0
        },
        {
            color: this.gradcolor,
            offset: 200
        }
      ]
    } else {
      this.gradfill = 'white';
      this.clrchng[0].style.gradient.stops =[
        {
            color: 'white',
            offset: 0
        },
        {
            color: this.gradcolor,
            offset: 200
        }
      ]
    }
    this.getgradcolor = this.clrchng[0].style.gradient.stops[0];
    console.log(this.clrchng[0].style.gradient.stops[0]);
    this.showcolorpalette = true;
    this.diagram.dataBind();
  }

  changeshapeborderfunc(){
    this.selectedborder = document.querySelector('#changeshapeborder');
    console.log(this.selectedborder.value);
    this.clrchng[0].annotations[0].shape.type = this.selectedborder.value;
  }

  changefontfunc(){
    this.selectedfont = document.querySelector('#changefont');
    console.log(this.selectedfont.value);
    this.clrchng[0].style.fontFamily = this.selectedfont.value;
    this.clrchng[0].annotations[0].style.fontFamily = this.selectedfont.value;
  }

  clickbold(){
    if(this.clrchng[0].annotations[0].style.bold == false){
      this.clrchng[0].annotations[0].style.bold = true;
    } else {
      this.clrchng[0].annotations[0].style.bold = false;
    }
  }

  clickitalic(){
    if(this.clrchng[0].annotations[0].style.italic == false){
      this.clrchng[0].annotations[0].style.italic = true;
    } else {
      this.clrchng[0].annotations[0].style.italic = false;
    }
  }

  clickunderline(){
    if(this.clrchng[0].annotations[0].style.textDecoration != "Underline"){
      this.clrchng[0].annotations[0].style.textDecoration = "Underline";
    } else if(this.clrchng[0].annotations[0].style.textDecoration == 'Underline') {
      this.clrchng[0].annotations[0].style.textDecoration = "None";
    }
  }

  clickleftalign(){
    this.clrchng[0].annotations[0].style.textAlign = "Left";
  }

  clickcenteralign(){
    this.clrchng[0].annotations[0].style.textAlign = "Center";
  }

  clickrightalign(){
    this.clrchng[0].annotations[0].style.textAlign = "Right";
  }

  clicktopalign(){
    this.clrchng[0].annotations[0].verticalAlignment = "Top";
  }

  clickmiddlealign(){
    this.clrchng[0].annotations[0].verticalAlignment = "Center";
  }

  clickbottomalign(){
    this.clrchng[0].annotations[0].verticalAlignment = "Bottom";
  }


  changerotate(){
    if(this.rotatevalue == 0){
      this.clrchng[0].rotateAngle = 90;
      this.rotatevalue = 1;
    } else if(this.rotatevalue == 1){
      this.clrchng[0].rotateAngle = 180;
      this.rotatevalue = 2;
    } else if(this.rotatevalue == 2){
      this.clrchng[0].rotateAngle = 270;
      this.rotatevalue = 3;
    } else if(this.rotatevalue == 3){
      this.clrchng[0].rotateAngle = 360;
      this.rotatevalue = 0;
    }

  }
 
  shadowfun(){
    this.clrchng[0].shadow.angle = 50;
    this.clrchng[0].shadow.opacity = 0.8;
    this.clrchng[0].shadow.distance = 9;
    this.clrchng[0].shadow.color = 'grey';
    console.log(this.clrchng[0]);
    this.diagram.dataBind();
  }

  changebackgroundorientation(value: any, args:any){
    console.log(value.target.checked, args);
    console.log(this.diagram,"");
    if(args == 'Landscape'){
      this.diagram.pageSettings.orientation = args;
      this.diagram.height = "601";
      this.diagram.width = "801";
    } else if(args == 'Portrait') {
      this.diagram.pageSettings.orientation = args;
      this.diagram.width = "601";
      this.diagram.height = "801";
    } else if(args == 'Default') {
      this.diagram.pageSettings.orientation = args;
      this.diagram.width = "100%";
      this.diagram.height = "700";
    }
    // width: 800, height: 600, orientation: 'Landscape',
  }

  changepagesize(){
    this.selectedfont = document.querySelector('#changesize');
    console.log(this.selectedfont.value);
    console.log(this.selectedfont.value.split(",")[0]);
    if((this.selectedfont.value.split(",")[0] == 841) && (this.selectedfont.value.split(",")[1] == 1189)){
      this.diagram.height = '3299';
      this.diagram.width = '4860';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 549) && (this.selectedfont.value.split(",")[1] == 841)){
      this.diagram.height = '2338';
      this.diagram.width = '3299';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 420) && (this.selectedfont.value.split(",")[1] == 549)){
      this.diagram.height = '1653';
      this.diagram.width = '2335';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 297) && (this.selectedfont.value.split(",")[1] == 420)){
      this.diagram.height = '1168';
      this.diagram.width = '1653';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 210) && (this.selectedfont.value.split(",")[1] == 297)){
      this.diagram.height = '826';
      this.diagram.width = '1168';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 148) && (this.selectedfont.value.split(",")[1] == 210)){
      this.diagram.height = '582';
      this.diagram.width = '826';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 105) && (this.selectedfont.value.split(",")[1] == 148)){
      this.diagram.height = '412';
      this.diagram.width = '1165';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 74) && (this.selectedfont.value.split(",")[1] == 105)){
      this.diagram.height = '290';
      this.diagram.width = '825';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 250) && (this.selectedfont.value.split(",")[1] == 353)){
      this.diagram.height = '979';
      this.diagram.width = '1389';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 176) && (this.selectedfont.value.split(",")[1] == 250)){
      this.diagram.height = '689';
      this.diagram.width = '979';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 1600) && (this.selectedfont.value.split(",")[1] == 900)){
      this.diagram.height = '899';
      this.diagram.width = '1599';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 1920) && (this.selectedfont.value.split(",")[1] == 1200)){
      this.diagram.height = '1199';
      this.diagram.width = '1919';
      console.log(this.diagram,"sdfsdsdf");
    } else if((this.selectedfont.value.split(",")[0] == 1600) && (this.selectedfont.value.split(",")[1] == 1200)){
      this.diagram.height = '1199';
      this.diagram.width = '1599';
      console.log(this.diagram,"sdfsdsdf");
    }
  }

  changeshapeborder(){
    this.selectedfont = document.querySelector('#changeborder');
    console.log(this.selectedfont.value);
    this.clrchng[0].style.strokeDashArray = this.selectedfont.value;
  }

  Enablefullscreen(){
    if(this.fullscreenmobilepalette == 'sb-mobile-palette'){
      this.fullscreenmobilepalette = 'sb-mobile-palette-dispnone';
    } else {
      this.fullscreenmobilepalette = 'sb-mobile-palette';
    }

    if(this.fullscreeneditorpalette == 'editor_style'){
      this.fullscreeneditorpalette = 'editor_style-dispnone';
    } else {
      this.fullscreeneditorpalette = 'editor_style';
    }
  }

  showcolorbox(value:any){
    console.log(value.target.checked,"value");
    if(value.target.checked == true){
      this.showfill = true;
    } else {
      this.showfill = false;
      this.clrchng[0].style.fill = "#f7f1f1";
      // this.clrchng[0].annotations[0].style.color = 'black';
    }
  }

  showbackgroundgridcolor(value:any){
    if(value.target.checked == true){
      this.showgridcolorpalette = true;
    } else {
      this.showgridcolorpalette = false;
    }
  }

  gotoback(){
    // this.location.back();
    window.location.replace(document.referrer);
  }

  showlinecolorbox(value:any){
    if(value.target.checked == true){
      this.showlinefill = true;
    } else {
      this.showlinefill = false;
    }
  }

  showgridbackgroundcolor(value:any){
    if(value.target.checked == true){
      this.showgridbackground = true;
    } else {
      this.showgridbackground = false;
    }
  }

  showgradientbox(value:any){
    console.log(value.target.checked,"value");
    if(value.target.checked == true){
      this.showgradientcolor = true;
    } else {
      this.showgradientcolor = false;
    }
  }

  changefontcolor(event:any){
   this.clrchng[0].annotations[0].style.color = event.currentValue.hex; 
  }

  changebackcolor(event:any){
    this.clrchng[0].annotations[0].style.fill = event.currentValue.hex;
  }

  changefontbordercolor(event:any){
    this.clrchng[0].annotations[0].style.strokeColor = event.currentValue.hex;
    this.clrchng[0].annotations[0].style.strokeWidth = "1";
    this.clrchng[0].style.strokeColor = event.currentValue.hex;
    this.clrchng[0].style.strokeWidth = "2";
  }
  
  changeflip(value:any){
    this.clrchng[0].flip = value;
  }


  zoomin(){
    // this.getimg = document.getElementById("diagram_diagramLayer");
    // this.zoominsize = this.zoominsize + 0.1;
    // this.getimg.style.transform = "scale(" + this.zoominsize +")";
    // this.getimg.style.transition = "transform 0.25s ease";
    this.diagram.zoom(1.2, {
      x: 100,
      y: 100
    });
  }

  zoomout(){
    this.diagram.zoom(0.8, {
      x: 100,
      y: 100
    });
  }

  deletechart(){
    this.diagram.remove();
  }

  download(){
    // this.getdownloadname(this.diagram.saveDiagram());
    console.log(this.diagram.saveDiagram());
    // var jsonfile = JSON.stringify(this.diagram.saveDiagram());
    // console.log(jsonfile);
    // this.jsonfile = jsonfile;
  }

  public options: IExportOptions;
  saveflowchart(){
    var jsonfile = JSON.stringify(this.diagram.saveDiagram());
    console.log(jsonfile);
    this.jsonfile = jsonfile;
    console.log(this.router.url);
    var proid = this.router.url.split("?")[1];
    this.getproject = proid.split("=")[0];
    this.options = {};
    this.options.mode = 'Data';
    this.options.format = 'PNG';
    this.image = this.diagram.exportDiagram(this.options);
    console.log(this.options,"this.options");
    if(this.getcharttype == 'flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        
        var passdata = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata);
        var savechart = '/angular-tool-save';
        this.toolservice.postData(savechart,passdata).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
          this.autosave();
        }); 
        // this.location.back();
        // window.location.reload();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata1 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata1);
        var savechart = '/angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata1).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
          this.autosave();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'SA-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata01 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata01);
        var savechart = '/SA-angular-tool-save';
        this.toolservice.postData(savechart,passdata01).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
          this.autosave();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata10 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata10);
        var savechart = '/SA-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata10).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
          this.autosave();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'RU-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata2 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata2);
        var savechart = '/RU-angular-tool-save';
        this.toolservice.postData(savechart,passdata2).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
          this.autosave();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata3 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata3);
        var savechart = '/RU-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata3).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
          this.autosave();
        });
      }
      // this.location.back();
    }
  }

  autosaveinedit(){
    if(this.autosavefalse == true){
      const source = interval(30000);
      source.subscribe(val => this.autosaveineditFN());
    }
  }

  autosaveineditFN(){
    var jsonfile = JSON.stringify(this.diagram.saveDiagram());
    console.log(jsonfile);
    this.jsonfile = jsonfile;
    console.log(this.router.url);
    var proid = this.router.url.split("?")[1];
    this.getproject = proid.split("=")[0];
    this.options = {};
    this.options.mode = 'Data';
    this.options.format = 'PNG';
    this.image = this.diagram.exportDiagram(this.options);
    console.log(this.options,"this.options");
    if(this.getcharttype == 'flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        
        var passdata = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata);
        var savechart = '/angular-tool-save';
        this.toolservice.postData(savechart,passdata).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
        // this.location.back();
        // window.location.reload();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        var passdata1 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata1);
        var savechart = '/angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata1).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'SA-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        var passdata01 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata01);
        var savechart = '/SA-angular-tool-save';
        this.toolservice.postData(savechart,passdata01).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        var passdata10 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata10);
        var savechart = '/SA-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata10).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'RU-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        var passdata2 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata2);
        var savechart = '/RU-angular-tool-save';
        this.toolservice.postData(savechart,passdata2).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        var passdata3 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata3);
        var savechart = '/RU-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata3).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    }
  }

  autosave(){
    const source = interval(30000);
    source.subscribe(val => this.autosavefc());
  }

  autosavefc(){
    var jsonfile = JSON.stringify(this.diagram.saveDiagram());
    console.log(jsonfile);
    this.jsonfile = jsonfile;
    console.log(this.router.url);
    var proid = this.router.url.split("?")[1];
    this.getproject = proid.split("=")[0];
    this.options = {};
    this.options.mode = 'Data';
    this.options.format = 'PNG';
    this.image = this.diagram.exportDiagram(this.options);
    console.log(this.options,"this.options");
    if(this.getcharttype == 'flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        
        var passdata = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata);
        var savechart = '/angular-tool-save';
        this.toolservice.postData(savechart,passdata).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
        }); 
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata1 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata1);
        var savechart = '/angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata1).subscribe(data => {
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
        });
      }
    } else if(this.getcharttype == 'SA-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata01 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata01);
        var savechart = '/SA-angular-tool-save';
        this.toolservice.postData(savechart,passdata01).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata10 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata10);
        var savechart = '/SA-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata10).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
    } else if(this.getcharttype == 'RU-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata2 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata2);
        var savechart = '/RU-angular-tool-save';
        this.toolservice.postData(savechart,passdata2).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","1");
        var passdata3 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "1",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata3);
        var savechart = '/RU-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata3).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.info(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
    }
  }

  getshapenotes(shapeidparam: any){
    if(this.getcharttype == 'flowchart'){
      var getnote = '/angular-tool-notes-shapeid/' + shapeidparam + '/' +  this.getid;
      this.toolservice.getData(getnote).subscribe(getshapes => {
        console.log(getshapes);
        this.notesdata = getshapes;
        if(this.notesdata.notes.length > 0){
          this.notedata = this.notesdata.notes[0];
        } else {
          this.notedata.note = '';
        }
      });
    } else if(this.getcharttype == 'SA-flowchart'){
      var getnote = '/SA-angular-tool-notes-shapeid/' + shapeidparam + '/' +  this.getid;
      this.toolservice.getData(getnote).subscribe(getshapes => {
        console.log(getshapes);
        this.notesdata = getshapes;
        if(this.notesdata.notes.length > 0){
          this.notedata = this.notesdata.notes[0];
        } else {
          this.notedata.note = '';
        }
      });
    } else if(this.getcharttype == 'RU-flowchart'){
      var getnote = '/RU-angular-tool-notes-shapeid/' + shapeidparam + '/' + this.getid;
      this.toolservice.getData(getnote).subscribe(getshapes => {
        console.log(getshapes);
        this.notesdata = getshapes;
        if(this.notesdata.notes.length > 0){
          this.notedata = this.notesdata.notes[0];
        } else {
          this.notedata.note = '';
        }
      });
    }
  }

  draftflowchart(){
    var jsonfile = JSON.stringify(this.diagram.saveDiagram());
    console.log(jsonfile);
    this.jsonfile = jsonfile;
    console.log(this.router.url);
    var proid = this.router.url.split("?")[1];
    this.getproject = proid.split("=")[0];
    this.options = {};
    this.options.mode = 'Data';
    this.options.format = 'PNG';
    this.image = this.diagram.exportDiagram(this.options);
    console.log(this.options,"this.options");
    if(this.getcharttype == 'flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        
        var passdata = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata);
        var savechart = '/angular-tool-save';
        this.toolservice.postData(savechart,passdata).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          this.autodraft();
          // this.toolservice.stopLoader();
        }); 
        
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata1 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata1);
        var savechart = '/angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata1).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          this.autodraft();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'SA-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata01 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata01);
        var savechart = '/SA-angular-tool-save';
        this.toolservice.postData(savechart,passdata01).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          this.autodraft();
          // this.toolservice.stopLoader();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata10 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata10);
        var savechart = '/SA-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata10).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          this.autodraft();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'RU-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata2 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata2);
        var savechart = '/RU-angular-tool-save';
        this.toolservice.postData(savechart,passdata2).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          this.autodraft();
          // this.toolservice.stopLoader();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata3 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata3);
        var savechart = '/RU-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata3).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          this.autodraft();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    }
  }

  autodraft(){
    const source = interval(300000);
    source.subscribe(val => this.autodraftfc());
  }

  autodraftfc(){
    var jsonfile = JSON.stringify(this.diagram.saveDiagram());
    console.log(jsonfile);
    this.jsonfile = jsonfile;
    console.log(this.router.url);
    var proid = this.router.url.split("?")[1];
    this.getproject = proid.split("=")[0];
    this.options = {};
    this.options.mode = 'Data';
    this.options.format = 'PNG';
    this.image = this.diagram.exportDiagram(this.options);
    console.log(this.options,"this.options");
    if(this.getcharttype == 'flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        
        var passdata = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata);
        var savechart = '/angular-tool-save';
        this.toolservice.postData(savechart,passdata).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
        
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata1 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata1);
        var savechart = '/angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata1).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'SA-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata01 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata01);
        var savechart = '/SA-angular-tool-save';
        this.toolservice.postData(savechart,passdata01).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata10 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata10);
        var savechart = '/SA-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata10).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    } else if(this.getcharttype == 'RU-flowchart'){
      if(this.getproject == 'user'){
        this.project_id = this.getid;
        console.log(this.filename);
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata2 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata2);
        var savechart = '/RU-angular-tool-save';
        this.toolservice.postData(savechart,passdata2).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        }); 
        // this.location.back();
      } else if(this.getproject == 'default'){
        this.project_id = this.getid;
        console.log(this.filename);
        
        var formData = new FormData();
        formData.append("template_id", this.project_id);
        formData.append("flowchart_name", this.filename);
        formData.append("flowchart", this.jsonfile);
        formData.append("status","0");
        var passdata3 = {
          "template_id" : this.project_id,
          "flowchart_name" : this.filename,
          "flowchart" : this.jsonfile,
          "status" : "0",
          "image" : this.image
        };  
        console.log(typeof(this.jsonfile),"this.jsonfile");
        console.log(passdata3);
        var savechart = '/RU-angular-tool-defaulttemplate-save';
        this.toolservice.postData(savechart,passdata3).subscribe(data => {
          // this.toolservice.startLoader();
          console.log(data,"savechartresponse");
          this.toastr.success(data.status);
          this.modalRef?.hide();
          // this.toolservice.stopLoader();
        });
      }
      // this.location.back();
    }
  }

  

  // public getdownloadname(data: string): void {
  //   console.log(data);
  //   if (window.navigator.msSaveBlob) {
  //       let blob: Blob = new Blob([data], { type: 'data:text/json;charset=utf-8,' });
  //       window.navigator.msSaveBlob(blob, this.filename+'.json');
  //       console.log(this.filename,blob,"blobbbbbb");
  //   } else {
  //       let dataStr: string = 'data:text/json;charset=utf-8,' + encodeURIComponent(data);
  //       let a: HTMLAnchorElement = document.createElement('a');
  //       a.href = dataStr;
  //       a.download = this.filename + '.json';      
  //       document.body.appendChild(a);  
  //       a.click();
  //       a.remove();
  //   }
  // }

  sendcomment(commentform:NgForm){
    
      if(this.getcharttype == 'flowchart'){
        var commentapi = '/angular-tool-commentsave';
        var passcomment = {
          "comments" : this.commentdata.commentinput,
          "flowchart_id" : this.getid
        };
        // this.toolservice.startLoader();
        this.toolservice.postData(commentapi,passcomment).subscribe(res => {
          console.log(res,"response");
          this.toastr.success(res.status);
          this.ngOnInit();
          this.commentdata.commentinput = '';
          this.commentid = res.comment_id;
          // this.toolservice.stopLoader();
        var path = '/angular-tool-comment-noti/' + this.getuserid + '/' + this.getid + '/' + res.comment_id;
        this.toolservice.getData(path).subscribe(data => {
          console.log(data,"/angular-tool-comment-noti");
        });
      });
     } else if(this.getcharttype == 'RU-flowchart'){
      var commentapi = '/RU-angular-tool-commentsave';
      var passcomment = {
        "comments" : this.commentdata.commentinput,
        "flowchart_id" : this.getid
      };
      // this.toolservice.startLoader();
      this.toolservice.postData(commentapi,passcomment).subscribe(res => {
        console.log(res,"response");
        this.toastr.success(res.status);
        this.ngOnInit();
        this.commentdata.commentinput = '';
        this.commentid = res.comment_id;
        // this.toolservice.stopLoader();
        var path = '/RU-angular-tool-comment-noti/' + this.getuserid + '/' + this.getid + '/' + res.comment_id;
        this.toolservice.getData(path).subscribe(data => {
          console.log(data,"/RU-angular-tool-comment-noti");
        });
      });
    }  else if(this.getcharttype == 'SA-flowchart'){
      var commentapi = '/SA-angular-tool-commentsave';
      var passcomment = {
        "comments" : this.commentdata.commentinput,
        "flowchart_id" : this.getid
      };
      // this.toolservice.startLoader();
      this.toolservice.postData(commentapi,passcomment).subscribe(res => {
        console.log(res,"response");
        this.toastr.success(res.status);
        this.ngOnInit();
        this.commentdata.commentinput = '';
        this.commentid = res.comment_id;
        // this.toolservice.stopLoader();
        var path = '/SA-angular-tool-comment-noti/' + this.getuserid + '/' + this.getid + '/' + res.comment_id;
        this.toolservice.getData(path).subscribe(data => {
          console.log(data,"/SA-angular-tool-comment-noti");
        });
      });
    }
  }

  addnote(noteform:NgForm){
    if(this.getcharttype == 'flowchart'){
      var noteapi = '/angular-tool-notesave/' + this.shapeid;
      var passnote = {
        "note" : this.notedata.note,
        "flowchart_id" : this.getid,
        "shape_id" : this.shapeid
      };
      // this.toolservice.startLoader();
      this.toolservice.postData(noteapi,passnote).subscribe(res => {
        console.log(res,"noteresponse");
        this.toastr.success(res.status);
        // this.notedata.note = '';
        // this.toolservice.stopLoader();
      });
    } if(this.getcharttype == 'SA-flowchart'){
      var noteapi = '/SA-angular-tool-notesave/' + this.shapeid;
      var passnote = {
        "note" : this.notedata.note,
        "flowchart_id" : this.getid,
        "shape_id" : this.shapeid
      };
      // this.toolservice.startLoader();
      this.toolservice.postData(noteapi,passnote).subscribe(res => {
        console.log(res,"noteresponse");
        this.toastr.success(res.status);
        // this.notedata.note = '';
        // this.toolservice.stopLoader();
      });
    } if(this.getcharttype == 'RU-flowchart'){
      var noteapi = '/RU-angular-tool-notesave/' + this.shapeid;
      var passnote = {
        "note" : this.notedata.note,
        "flowchart_id" : this.getid,
        "shape_id" : this.shapeid
      };
      // this.toolservice.startLoader();
      this.toolservice.postData(noteapi,passnote).subscribe(res => {
        console.log(res,"noteresponse");
        this.toastr.success(res.status);
        // this.notedata.note = '';
        // this.toolservice.stopLoader();
      });
    } 
  }

  diagramCreate(args: Object):any {
    if(args != undefined){
      paletteIconClick();
    } else {
    }
  }

  openModal(template1: TemplateRef<any>) {
    if((this.hidenotesandcomments == true) && (this.hidesaveoption == true) && (this.hideallqrcode == true)){
      this.modalRef = this.modalService.show(template1);
    }
  }

  public path: Object = {
    saveUrl: 'https://ej2.syncfusion.com/services/api/uploadbox/Save',
      removeUrl: 'https://ej2.syncfusion.com/services/api/uploadbox/Remove',
  };

  public node: NodeModel = {
    // Size of the node
    width: 100,
    height: 100,
    style: {
        fill: '#6BA5D7',
        strokeColor: 'white'
    },
};

  private getshape: NodeModel[] = [
    { id: 'Imagebasic', shape: { type: 'Image', source: '/assets/bold.png' } },
  ];

  public dropElement: HTMLElement = document.getElementsByClassName(
    'control-fluid'
  )[0] as HTMLElement;
  
  public onUploadSuccess(arg: any): void {
    console.log(arg,"onuploadsuccess");
    let file1: any = arg.file;
    let file: any = file1.rawFile;
    let reader: FileReader = new FileReader();
    let _self = this;
    this.toolservice.startLoader();
    setTimeout(function() {
      reader.addEventListener('load', (event: any): void => {
        console.log(event,"event");
        console.log(_self.diagram);
        _self.diagram.add(_self.node);
        _self.getshape[0].shape = {
          type : 'Image',
          source : event.target.result
        };
        console.log(_self.getshape[0].shape);
        console.log(_self.diagram.nodes.length);
          var totalobj = _self.diagram.nodes.length - 1;
          _self.diagram.nodes[totalobj].shape = _self.getshape[0].shape;
        // console.log(_self.diagram.nodes[0].shape);
        _self.diagram.dataBind();
      });
      reader.readAsDataURL(file);
      _self.toolservice.stopLoader();
    },2000);
  }

  changefilename(event:any){
    // console.log(event.target.value);
    this.filename = event.target.value;
  }
  changelinkname(event:any){
    this.linkname = event.target.value;
  }
}

function getPorts(_obj: NodeModel): PointPortModel[] {
  let ports: PointPortModel[] = [
    { id: 'port1', shape: 'Circle', offset: { x: 0, y: 0.5 } },
    { id: 'port2', shape: 'Circle', offset: { x: 0.5, y: 1 } },
    { id: 'port3', shape: 'Circle', offset: { x: 1, y: 0.5 } },
    { id: 'port4', shape: 'Circle', offset: { x: 0.5, y: 0 } }
  ];
  return ports;
}

function snapsettings(_snapsettings: any, _arg1: string) {
  console.log("It works")
  throw new Error('Function not implemented.');
}
