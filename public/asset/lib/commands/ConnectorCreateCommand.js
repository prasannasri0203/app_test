"use strict";

function ConnectorCreateCommand(connectorId){
    this.oType = 'ConnectorCreateCommand';
    
    /**Any sequence of many mergeable actions can be packed by the history*/
    this.mergeable = false;
    
    this.firstExecute = true;
    
    this.connectorId = connectorId;

}


ConnectorCreateCommand.prototype = {
    /**This method got called every time the Command must execute*/
    execute : function(){
        throw "Should not be implemented";
    },
    
    
    /**This method should be called every time the Command should be undone*/
    undo : function(){ 
        CONNECTOR_MANAGER.connectorRemoveById(this.connectorId, true);

        // if current connector is in text editing state
        if (state == STATE_TEXT_EDITING) {
            // remove current text editor
            currentTextEditor.destroy();
            currentTextEditor = null;
        }
        
        state = STATE_NONE;
        selectedConnectorId = -1;
    }
}