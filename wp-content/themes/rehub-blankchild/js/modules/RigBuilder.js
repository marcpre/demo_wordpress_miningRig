import $ from 'jquery';
import dt from 'datatables.net';

class RigBuilder {
    constructor() {
        this.events();
        // console.log("RigBuilder Console Log")
    } // end constructor

    events() {
        $(".btn.btn-primary.btn-sm").on("click", this.ourClickDispatcher.bind(this));
    }

    // methods
    ourClickDispatcher(e) {
        var currentButton = $(e.target).closest(".btn.btn-primary.btn-sm");
        console.log("lolonator")

        if (currentButton.data('exists') == 'cpu') {
            console.log("cpu clicked")
            this.loadMiningHardware('cpu').bind(this)
        }

        if (currentButton.data('exists') == 'motherboard') {
            console.log("motherboard clicked")
            this.loadMiningHardware('motherboard').bind(this)
        }

        if (currentButton.data('exists') == 'graphic-card') {
            console.log("graphic-card clicked")
            this.loadMiningHardware('graphic-card').bind(this)
        }
    }

    loadMiningHardware(part) {

        console.log(`loadMiningHardware ${part} clicked`)
        $.getJSON(miningRigData.root_url + '/wp-json/rigHardware/v1/manageRigHardware?term=' + part, (results) => {
            console.log(results)

            $('#exampleModal').modal('show');
            
            var dataSet = [
                [ "1", "System Architect", "Edinburgh", "5421", "2011/04/25", "$320,800", "$320,800" ],
                [ "2", "Accountant", "Tokyo", "8422", "2011/07/25", "$170,750", "$320,800" ],
                [ "3", "Junior Technical Author", "San Francisco", "1562", "2009/01/12", "$86,000", "$320,800" ],
            ];
            
            $('#table_id').DataTable({
                data: dataSet,
                columns: [{
                        title: "#"
                    },
                    {
                        title: "Title"
                    },
                    {
                        title: "Manufacturer"
                    },
                    {
                        title: "Price"
                    },
                    {
                        title: "Availability"
                    },
                    {
                        title: ""
                    },
                    {
                        title: ""
                    }
                ]
            });
            
        });
        
        // open modal
    }
}

export default RigBuilder;
