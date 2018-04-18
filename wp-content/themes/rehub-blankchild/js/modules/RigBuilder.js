import $ from 'jquery';
import dt from 'datatables.net';

class RigBuilder {
    constructor() {
        let resultsGlobal = []
        this.events();
    } // end constructor

    events() {
        $(".btn.btn-primary.btn-sm").on("click", this.ourClickDispatcher.bind(this));
        $(".btn.btn-danger.btn-sm").on("click", this.ourClickDispatcher.bind(this));
        $('#table_id').on('click', 'button.addButton', this.addToTable.bind(this));
    }

    // methods
    ourClickDispatcher(e) {
        let currentButton = $(e.target).closest(".btn.btn-primary.btn-sm");

        if (!(currentButton.length > 0)) {
            console.log("lolonator")
            currentButton = $(e.target).closest(".btn.btn-danger.btn-sm");
        }

        if (currentButton.data('exists') == 'cpu') {
            console.log("cpu clicked")
            this.loadMiningHardware('cpu')
        }

        if (currentButton.data('exists') == 'motherboard') {
            console.log("motherboard clicked")
            this.loadMiningHardware('motherboard')
        }

        if (currentButton.data('exists') == 'graphic-card') {
            console.log("graphic-card clicked")
            this.loadMiningHardware('graphic-card')
        }

        if (currentButton.data('exists') == 'power-supply') {
            console.log("power-supply clicked")
            this.loadMiningHardware('power-supply')
        }

        if (currentButton.data('exists') == 'rig-frame') {
            console.log("rig-frame clicked")
            this.loadMiningHardware('rig-frame')
        }

        if (currentButton.data('exists') == 'more-parts') {
            console.log("more-parts clicked")
            this.loadMiningHardware('more-parts')
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
        let currentButton = $(e.target).closest("button.addButton")

        const itemIndex = parseInt(currentButton.data('item-index'))
        const item = this.resultsGlobal.generalInfo[itemIndex]

        let targetButton = $(".btn.btn-primary.btn-sm." + item.category["0"].slug)

        if (targetButton.length > 0) {
            console.log("if part")

            let targetButtonParent = targetButton[0].parentElement

            targetButtonParent.insertAdjacentHTML('beforebegin', `
                <td>
                    <img src="${item.img}" alt="${item.title}" height="42" width="42">
                    <a href="${item.affiliateLink}">
                        ${item.title}
                    </a>
                </td>    
            `)

            targetButton.attr('class', 'btn btn-danger btn-sm ' + item.category["0"].slug); // change button class to red

            targetButton.text(function() {
                return $(this).text().replace("Add", "Edit");
            });

            // close modal window
            $('#exampleModal').modal('hide');
        }
        else {
            console.log("else part")

            targetButton = $(".btn.btn-danger.btn-sm." + item.category["0"].slug)

            let elementToBeModified = targetButton.closest('tr').find('td:nth-child(2)');
            elementToBeModified.empty();

            elementToBeModified.html(`
                   <img src="${item.img}" alt="${item.title}" height="42" width="42">
                    <a href="${item.affiliateLink}">
                        ${item.title}
                    </a>
            `)

            targetButton.attr('class', 'btn btn-danger btn-sm ' + item.category["0"].slug); // change button class to red

            targetButton.text(function() {
                return $(this).text().replace("Add", "Edit");
            });
            // close modal window
            $('#exampleModal').modal('hide');
        }
    }
}

export default RigBuilder;
