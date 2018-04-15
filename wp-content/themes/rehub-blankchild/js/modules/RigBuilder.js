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

            //transform data set
            let dataSet = results.generalInfo.map((item, i) => [
                i + 1,
                `<img src="${item.img}" alt="${item.title}" height="42" width="42">
                 <a href="<?php the_permalink();?>">
                     ${item.title}
                 </a>`,
                item.manufacturer,
                `<div>${item.currency} ${item.price}</div>`,
                item.availability,
                `<button type="button">
                    Add
                </button>`,
                `<button type="button">
                    Buy
                </button>`
            ]) 

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
