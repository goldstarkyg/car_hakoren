<?php
//when edit in sheet row, create currnet date time.
function currentTime() {
    var sheet = SpreadsheetApp.getActiveSheet();
    var cell = sheet.getActiveCell();
    var current_row = cell.getRow();
    var registed_val = sheet.getRange(current_row, 37).getValue();
    var date = Utilities.formatDate(new Date(), "GMT+09:00", "''yyyy-MM-dd hh:mm:ss");
    if(registed_val != '') date = registed_val;
    sheet.getRange(current_row, 37).setValue(date);
}
?>