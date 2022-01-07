{{-- @extends('layouts.app')

@section('content') --}}

<!DOCTYPE html>
<html>
    <!--Copyright 2010 Scriptoid s.r.l-->
    <head>
        <title>Kaizen Hub</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
        <script type="text/javascript" src="{{ asset('/asset/assets/javascript/dropdownmenu.js') }}"></script>    
        
        <link rel="stylesheet" media="screen" type="text/css" href="{{ asset('asset/assets/css/style.css') }}" />
        <link rel="stylesheet" media="screen" type="text/css" href="{{ asset('asset/assets/css/minimap.css') }}" />
        
        <script type="text/javascript" src="{{ asset('asset/assets/javascript/json2.js') }}"></script>
        <script type="text/javascript" src="{{ asset('asset/assets/javascript/jquery-1.11.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('asset/assets/javascript/ajaxfileupload.js') }}"></script>
        
        <link type='text/css' href='{{ asset('asset/assets/simplemodal/css/diagramo.css') }}' rel='stylesheet' media='screen' />
        <script type="text/javascript" src="{{ asset('asset/assets/simplemodal/js/jquery.simplemodal.js') }}"></script>
        
        <?php 
        $WEBADDRESS = 'http://127.0.0.1:8000/asset';
        ?>
        <script type="text/javascript">
            "use strict";
            /*Option 1:
             *We can use window.location like this:
             * url = window.location.protocol + window.location.hostname + ":" + window.location.port + ....
             * @see http://www.w3schools.com/jsref/obj_location.asp
             * 
             * Option 2:
             * Use http://code.google.com/p/js-uri/
             **/
            var appURL = '<?=$WEBADDRESS?>';
            var figureSetsURL = appURL + '/lib/sets';
            var insertImageURL = appURL + '/data/import/';

            function showImport(){
                //alert("ok");
                var r = confirm("Current diagram will be deleted. Are you sure?");
                if(r === true){                    
                    $('#import-dialog').modal(); // jQuery object; this demo
                }                
            }
        </script>
        
         @include('layouts.editor')

        <!--[if IE]>
        <script src="./assets/javascript/excanvas.js"></script>
        <![endif]-->
       
    </head>
    <body onload="init('<?= isset($_REQUEST['diagramId']) ? $_REQUEST['diagramId']:''?>');" id="body">
        
        <? require_once dirname(__FILE__) . '/header.php'; ?>

        <div id="actions">
            {{-- <a style="text-decoration: none;" href="#" onclick="return save();" title="Save diagram (Ctrl-S)"><img src="{{ asset('asset/assets/images/icon_save.jpg') }}" border="0" width="16" height="16"/></a> --}}
            
            {{-- <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/> --}}
            
           {{--  <a style="text-decoration: none;" href="./myDiagrams.php" title="Open diagram"><img src="{{ asset('asset/assets/images/icon_open.jpg') }}" border="0" width="16" height="16"/></a>

            <?if(isset($_REQUEST['diagramId']) &&  is_numeric($_REQUEST['diagramId']) ){//option available ony when the diagram was saved?>
                <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
                <a style="text-decoration: none;" href="#" onclick="return print_diagram();" title="Print diagram"><img src="{{ asset('asset/assets/images/icon_print.png') }}" border="0" width="16" height="16"/></a>
            <?}?> 

            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>--}}
            {{--<img class="separator" src="assets/images/toolbar_separator.gif" border="0" width="1" height="16"/>
            
            <a href="javascript:action('container');" title="Container (Experimental)"><img src="assets/images/container.png" border="0" alt="Container"/></a>--}}
           
            <select name="forma" onclick="location = this.value;" title="Way Points">
                <option value="javascript:action('connector-straight');"><span><img src="https://themeselection.com/demo/chameleon-admin-template/app-assets/images/portrait/small/avatar-s-19.png" width="10px;" height="10px;">dfsdfsd</span></option>
                <option value="javascript:action('connector-jagged');">Jagged</option>
                <option value="javascript:action('connector-organic');">Organic</option>
            </select>
            
             {{-- <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            
            <a href="javascript:action('container');" title="Container (Experimental)"><img src="{{ asset('asset/assets/images/container.png') }}" border="0" alt="Container"/></a> --}}

            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>            
                        
            <input type="checkbox" onclick="showGrid();" id="gridCheckbox"  title="Show grid" /> <span class="toolbarText">Show grid</span>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            
            {{-- <input type="checkbox" onclick="snapToGrid();" id="snapCheckbox" title="Snap elements to grid" /> <span class="toolbarText">Snap to grid</span>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/> --}}

             <a href="javascript:action('front');" title="Move to front"><img src="{{ asset('asset/assets/images/icon_front.gif') }}" border="0"/></a>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            
            <a href="javascript:action('back');" title="Move to back"><img src="{{ asset('asset/assets/images/icon_back.gif') }}" border="0"/></a>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            
           <a href="javascript:action('moveforward');" title="Move (one level) to front"><img src="{{ asset('asset/assets/images/icon_forward.gif') }}" border="0"/></a>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            
            <a href="javascript:action('moveback');" title="Move (one level) back"><img src="{{ asset('asset/assets/images/icon_backward.gif') }}" border="0"/></a>
            
            
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('group');" title="Group (Ctrl-G)"><img src="{{ asset('asset/assets/images/group.gif') }}" border="0"/></a>
             
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('ungroup');" title="Ungroup (Ctrl-U)"><img src="{{ asset('asset/assets/images/ungroup.gif') }}" border="0"/></a>

            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:createFigure(figure_Text, '{{ asset('asset/assets/images/text.gif') }}');"  title="Add text"><img  src="{{asset('asset/assets/images/text.gif')}}" border="0" height ="16"/></a>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            
            {{-- <a href="javascript:showInsertImageDialog();"  title="Add image"><img src="{{ asset('asset/editor/assets/images/image.gif') }}" border="0" height ="16" alt="Image"/></a>
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/> --}}

            <a href="javascript:action('undo');" title="Undo (Ctrl-Z)"><img src="{{ asset('asset/assets/images/arrow_undo.png') }}" border="0"/></a>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('redo');" title="Redo (Ctrl-Y)"><img src="{{ asset('asset/assets/images/arrow_redo.png') }}" border="0"/></a>
            
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('deleteshape');" title="Delete (del)"><img src="{{ asset('asset/assets/images/deletediagram.png') }}" border="0" width="12px;"/></a>

            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('setShadow');" title="Shadow"><img src="{{ asset('asset/assets/images/shadow.png') }}" border="0"/></a>

            <!-- TODO: From Janis: we have to create a nice icon for duplicate, currently this is the only command without an icon -->
            <!--
            <a href="javascript:action('duplicate');">Copy (Ctrl-D)</a>
            -->

            
            {{-- <input type="text" id="output" />                
            <img style="vertical-align:middle;" src="../assets/images/toolbar_separator.gif" border="0" width="1" height="16"/> 
            <a href="javascript:action('duplicate');">Copy</a>
            <img style="vertical-align:middle;" src="../assets/images/toolbar_separator.gif" border="0" width="1" height="16"/>
            <a href="javascript:action('group');">Group</a>
            <img style="vertical-align:middle;" src="../assets/images/toolbar_separator.gif" border="0" width="1" height="16"/>
            <a href="javascript:action('ungroup');">Ungroup</a>--}}
            {{-- <a href="javascript:zoomin('zoomIn');"></a>
            <img style="vertical-align:middle;" src="../assets/images/toolbar_separator.gif" border="0" width="1" height="16"/> 
            <a onclick="zoomin()">zoom</a> --}}

            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('zoomin');" title="Undo (Ctrl-Z)"><img src="{{ asset('asset/assets/images/zoom-in.png') }}" border="0"/></a>

            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('zoomout');" title="Undo (Ctrl-Z)"><img src="{{ asset('asset/assets/images/zoom-in.png') }}" border="0"/></a>

            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            <a href="javascript:action('zoom');" title="Undo (Ctrl-Z)"><img src="{{ asset('asset/assets/images/zoom-in.png') }}" border="0"/></a>

            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>
            
           {{--  <a href="javascript:showInsertImageDialog();"  title="Add image"><img src="{{ asset('asset/assets/images/image.gif') }}" border="0" height ="16" alt="Image"/></a>
            <img class="separator" src="assets/images/toolbar_separator.gif" border="0" width="1" height="16"/> --}}
            
            <a href="javascript:action('duplicate');" title="copy"><img src="{{ asset('asset/assets/images/copy.png') }}" border="0"/></a>
            <img class="separator" src="{{ asset('asset/assets/images/toolbar_separator.gif') }}" border="0" width="1" height="16"/>    

            
            <a id="dropdown">
        
        <!--File menu-->
        <a class="dropdown_menu">
            <a href="#" onmouseover="dropdownSpace.menuOpen('file')" onmouseout="dropdownSpace.menuCloseTime()">Link</a>
            <div class="dropdown_menu_panel" id="file" onmouseover="dropdownSpace.menuCancelCloseTime()" onmouseout="dropdownSpace.menuCloseTime()" style="visibility: hidden; margin-left: 873px;" >
                {{-- <a style="text-decoration: none;" href="./common/controller.php?action=newDiagramExe" title="New diagram"><img style="vertical-align:middle; margin-right: 3px;" src="assets/images/icon_new.jpg" border="0" width="20" height="21"><span class="menuText">New</span></a> --}}
                <a href="javascript:showInsertImageDialog();"  title="Add image"><img src="{{ asset('asset/assets/images/image.gif') }}" border="0" height ="16" alt="Image"/> Image </a>
            </div> 
            </a>
        </a>
        <!-- /File menu-->
        
    </div>
        </div>
        
         <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
        <div id="editor">
            <div id="figures">
                <select style="width: 120px;" onchange="setFigureSet(this.options[this.selectedIndex].value);">
                    <script>
                        "use strict";
                        for(var setName in figureSets){
                            var set = figureSets[setName];
                            document.write('<option value="' + setName + '">' + set['name'] + '</option>');
                        }
                    </script>
                </select>
                <script>
                    "use strict";
                    /**Builds the figure panel*/
                    function buildPanel(){
                        //var first = true;
                        var firstPanel = true;
                        for(var setName in figureSets){                            
                            var set = figureSets[setName];
                            //creates the div that will hold the figures
                            var eSetDiv = document.createElement('div');
                            eSetDiv.setAttribute('id', setName);
                            //eSetDiv.style.border = '1px solid green';
                            if(firstPanel) {
                                firstPanel = false;
                            }
                            else{
                                eSetDiv.style.display = 'none';
                            }
                            document.getElementById('figures').appendChild(eSetDiv);
                            
                            //add figures to the div
                            for(var figure in set['figures']){
                                figure = set['figures'][figure];
                                
                                var figureFunctionName = 'figure_' + figure.figureFunction;  
                               
                                var figureThumbURL = 'asset/lib/sets/' + setName + '/' + figure.image;
                               
                                var eFigure = document.createElement('img');
                                eFigure.setAttribute('src', figureThumbURL);
                               
                                eFigure.addEventListener('mousedown', function (figureFunction, figureThumbURL){    
                                     
                                    return function(evt) {
                                        evt.preventDefault();
                                        //Log.info("editor.php:buildPanel: figureFunctionName:" + figureFunctionName);
                                        
                                        createFigure(window[figureFunction] /*we need to search for function in window namespace (as all that we have is a simple string)**/
                                            ,figureThumbURL);
                                    };
                                } (figureFunctionName, figureThumbURL)
                                , false);

                                //in case use drops the figure
                                eFigure.addEventListener('mouseup', function (){
                                    createFigureFunction = null;    
                                    selectedFigureThumb = null;
                                    state = STATE_NONE;
                                }
                                , false);                                                                                                
                                
                                
                                eFigure.style.cursor = 'pointer';
                                eFigure.style.marginRight = '5px';
                                eFigure.style.marginTop = '2px';
                                
                                eSetDiv.appendChild(eFigure);
                            }
                        }
                    }
                    
                    buildPanel();
                    
//                    var first = true;
//                    for(var setName in figureSets){
//                        
//                        document.write('<div id="' + setName + '" ' + (!first ? 'style="display: none"' : '')+'>');
//                        document.write('<table border="0" cellpadding="0" cellspacing="0" width="120">');
//                        var counter = 0;
//                        var set = figureSets[setName];
//                        for(var figure in set['figures']){
//                            figure = set['figures'][figure];
//                            if(counter % 3 == 0){
//                                document.write('<tr>');
//                            }
//                            
//                            var figureFunctionName = 'figure_' + figure.figureFunction;
//                            var figureThumbURL = 'lib/sets/' + setName + '/' + figure.image;
//                            
//                            document.write('<td align="center">');
//                            document.write('<a href="javascript:createFigure(' + figureFunctionName + "," + "'" + figureThumbURL + "'" + ');">');
//                            
//                            //TODO: how to prevent default behaviour?
//                            var figureImageId = 'fig' + setName + '_' + figure.figureFunction;
//                            document.write('<img id="' + figureImageId +'" onmousedown="javascript:createFigure(' + figureFunctionName + "," + "'" + figureThumbURL + "'" + ');" src="' + figureThumbURL + '" border="0" alt="'+ figure.figureFunction + '" />');
//                            
//                            var figureImageElem = document.getElementById(figureImageId);
//                            figureImageId.onMouseDown = function(evt){
//                                alert('I am here');
//                                evt.preventDefault();
//                            }
//                            
//                            //document.write('</a>');
//                            document.write('</td>');
//                            
//                            counter ++;
//                            if(counter % 3 == 0){
//                                document.write('</tr>');
//                            }
//                        }
//                        if(counter % 3 != 0){
//                            document.write('</tr>');
//                        }
//                        document.write('</table></div>');
//                        first = false;
//                    }
                </script>
                
                <div style="display:none;" id="more">
                    More sets of figures {{-- <a href="http://diagramo.com/figures.php" target="_new">here</a> --}}
                </div>
            </div>
            
            <!--THE canvas-->
            <div style="width: 100%" >
                <div  id="container">
                    <canvas id="a" width="800" height="600">
                        Your browser does not support HTML5. Please upgrade your browser to any modern version.
                    </canvas>
                    <div id="text-editor"></div>
                    <div id="text-editor-tools"></div>
                </div>
            </div>
            
            <!--Right panel-->
            <div id="right">
                <center>
                    <div id="minimap">
                    </div>
                </center>
                <div style="overflow: scroll;" id="edit">
                </div>
            </div>
            
        </div>
        
        <!--The import panel-->
        <div id="import-dialog" style="background-color: white; display: none; margin-top: auto; margin-bottom: auto;">
            <form action="./common/controller.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="importDiagramExe"/>
                <h2>Import Diagramo file </h2>
                <p/>
                <input type="file" name="diagramFile" id="diagramFile"/>  
                <p/>
                <input type="image" src="./assets/images/import.gif"/>
            </form>
        </div>

        <!--Insert Image dialog content-->
        <div id="insert-image-dialog">
            <h2>Insert Image</h2>
            <form action="./common/controller.php" method="POST" target="upload_target" enctype="multipart/form-data">
                <input type="hidden" name="action" value="insertImage"/>
                <div class="insert-image-line">
                    <input type="radio" name="image-group" value="URL" checked>
                    <label>From URL:</label>
                    <input type="text" class="url-input" name="imageURL" id="imageURL"/>
                </div>
                <div class="insert-image-line">
                    <input type="radio" name="image-group" value="Upload">
                    <label>Upload:</label>
                    <input type="file" class="right-offset" name="imageFile" id="imageFile"/>
                </div>
                <div class="insert-image-line">
                    <input type="radio" name="image-group" value="Reuse" id="insert-image-reuse-group">
                    <label>Reuse:</label>
                    <select id="insert-image-reuse"  name="reuseImageFile">
                    </select>
                </div>
                <div id="upload-image-error">
                </div>
                <div class="submit-container">
                    <input type="submit" value="Insert" />
                </div>
            </form>
        </div>
        
        <!--Insert Image hidden iframe-->
        <iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px;"></iframe>

        <script type="text/javascript">
            "use strict";
            function loadFill(check){
                if(check.checked === true){
                    if($('#colorpickerHolder3').css('display') === 'none'){
                        $('#colorSelector3').click();
                    }
                }
                else{
                    if($('#colorpickerHolder3').css('display') === 'block'){
                        $('#colorSelector3').click();
                    }
                }
            }
            function zoomIn(){
                var body = document.querySelector("body");
                var currWidth = body.clientWidth;
                if(currWidth == 1000000){
                    alert("Maximum zoom-in level of 1 Million reached.");
                } else{
                    body.style.width = (currWidth + 50) + "px";
                } 
            }
            function zoomOut(){
                var body = document.querySelector("body");
                var currWidth = body.clientWidth;
                if(currWidth == 500000){
                    alert("Maximum zoom-out level reached.");
                } else{
                    body.style.width = (currWidth - 50) + "px";
                }
            }
        </script>
        <br/>
         <? //require_once dirname(__FILE__) . '/common/analytics.php';?>
    </body>
</html>
{{-- @endsection --}}
