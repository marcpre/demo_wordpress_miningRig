import $ from 'jquery';
import dt from 'datatables.net';
//var dt = require('datatables.net')();

class MiningRigSelectionTable {
    constructor() {
        $(document).ready(function () {
            console.log("lolonator")
            $('#table_id').DataTable();
        });
        alert("This is a test message.")
    } // end constructor
}

export default MiningRigSelectionTable;
