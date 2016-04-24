$(document).ready(function(){
    
    var numOfFields = $(".descriptionField").size();
    var oldDescVals = [];
    var oldPercentsVals = [];
    
    if(numOfFields > 0){
        var oldDesc = $(".descriptionField");
        var oldPercents = $(".thresholdField");
        
        for(var i = 0; i < oldDesc.size(); i++){
            oldDescVals[i] = oldDesc[i].value;
            oldPercentsVals[i] = oldPercents[i].value;
        }
    }
    
    $("#saveParkingLotsButton").click(function(){
        var newDesc = $(".descriptionField");
        var newPercents = $(".thresholdField");
        var newIDs = $(".parkingSpotID");
        var newDescVals = [], newPercentsVals = [], newIDsVals = [];
        
        for(var i = 0; i < numOfFields; i++){
            if(newDesc[i].value != oldDescVals[i] || newPercents[i].value != oldPercentsVals[i]){
                newDescVals[i] = newDesc[i].value;
                newPercentsVals[i] = newPercents[i].value/100;
                newIDsVals[i] = newIDs[i].value;
            }
        }
        
        if(newDescVals.length > 0){
            saveChanges(newIDsVals, newDescVals, newPercentsVals);
        } 
    });
    
    function saveChanges(ids, descs, percentages) {
        $.post( "spots/saveSpots.php", { parkingLotID: window.location.href.match(/=(.*)/)[1], ids: JSON.stringify(ids), descs: JSON.stringify(descs), percentages: JSON.stringify(percentages)}, function() {
            location.reload();
        });
    }
    
});