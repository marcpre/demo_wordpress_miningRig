import $ from 'jquery';
import dt from 'datatables.net';

class RigBuilder {
    constructor() {
        let resultsGlobal = []
        this.events();
    } // end constructor

    events() {
        $(".btn.btn-primary.btn-sm").on("click", this.ourClickDispatcher.bind(this));
        $('#table_id').on('click', 'button.addButton', this.addToTable.bind(this));
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
                `<button class="addButton" type="button" data-item-index="${i}">
                    Add
                </button>`,
                `<a class="btn btn-primary" href="${item.affiliateLink}" target="_blank" role="button">
                    Buy
                </a>`
            ])

            this.resultsGlobal = results //assign to global variable

            $('#table_id').DataTable({
                data: dataSet,
                destroy: true,
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
    }

    addToTable(e) {
        const currentButton = $(e.target).closest("button.addButton")
        const itemIndex = parseInt(currentButton.data('item-index'))
        const item = this.resultsGlobal.generalInfo[itemIndex]
        
        //replace button and append to table
        $("ul").append(`<li>${item.title}</li>`);
    }
}

export default RigBuilder;
