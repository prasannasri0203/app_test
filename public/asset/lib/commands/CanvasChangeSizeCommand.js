"use strict";

function CanvasChangeSizeCommand(newWidth, newHeight){

    this.previousWidth = canvasProps.width;
    this.previousHeight = canvasProps.height;

    this.width = newWidth;
    this.height = newHeight;

    this.oType = "CanvasChangeSizeCommand";
}

CanvasChangeSizeCommand.prototype = {

    constructor : CanvasChangeSizeCommand,

    /**This method got called every time the Command must execute*/
    execute : function(){
        //Attention: canvasProps is a global variable
        canvasProps.setWidth(this.width);
        canvasProps.setHeight(this.height);

        setUpEditPanel(canvasProps);
    },


    /**This method should be called every time the Command should be undone*/
    undo : function(){
        canvasProps.setWidth(this.previousWidth);
        canvasProps.setHeight(this.previousHeight);

        setUpEditPanel(canvasProps);
    }
};
