import $ from 'jquery';
import dt from 'datatables.net';

class RigBuilder {


    constructor() {
        var pressedButton = null
        let resultsGlobal = []
        var overallPrice = 0
        //calculate Price
        this.calculatePrice()
        this.events()
    } // end constructor

    events() {
        //$(".btn.btn-primary.btn-sm").on("click", this.ourClickDispatcher.bind(this));
        //$(".btn.btn-danger.btn-sm").on("click", this.ourClickDispatcher.bind(this))
        // Save Build
        $(".btn.btn-primary.btn-lg.save-list").on("click", this.saveBuild.bind(this))

        //DataTable
        $('#table_id').on('click', 'button.addButton', this.addToTable.bind(this))
        //Mining Rig Table
        $("#miningRigTable").on("click", ".btn.btn-primary.btn-sm", this.ourClickDispatcher.bind(this))
        $("#miningRigTable").on("click", ".btn.btn-danger.btn-sm.deleteMe", this.deleteRow.bind(this))
        //$("#miningRigTable").on("click", ".btn.btn-danger.btn-sm", this.ourClickDispatcher.bind(this))
    }

    // methods
    clickDispatcherTable(e) {
        let plusButton = $(e.target).closest(".btn.btn-dark.btn-sm");
        let plusButtonParent = plusButton[0].parentElement.parentElement

        if (plusButton.hasClass("graphic-card")) {
            if ($(plusButtonParent).children('td').length == 3) {
                plusButtonParent.insertAdjacentHTML('afterend', `
                    <tr>
                        <td></td>
                        <td>
                            <button type="button" data-exists="graphic-card" class="btn btn-primary btn-sm graphic-card" >
                                Add Graphic Card
                            </button>
                        </td>
                    </tr>
                `)
                console.log("Button DISABLED")
                // plusButton.prop("disabled", true);
            }
        }
    }

    deleteRow(e) {
        console.log("delete row")
        let deleteBtn = $(e.target).closest(".deleteMe");
        deleteBtn.closest('tr').remove()
        $(".btn.btn-primary.btn-sm").attr("disabled", false);
        this.calculatePrice()
    }

    ourClickDispatcher(e) {
        console.log("ourClickDispatcher")
        this.pressedButton = $(e.target).closest(".btn.btn-primary.btn-sm");

        console.log(this.pressedButton.length)

        if (this.pressedButton.data('exists') == 'cpu') {
            console.log("cpu clicked")
            this.loadMiningHardware('cpu')
        }

        if (this.pressedButton.data('exists') == 'motherboard') {
            console.log("motherboard clicked")
            this.loadMiningHardware('motherboard')
        }

        if (this.pressedButton.data('exists') == 'graphic-card') {
            console.log("graphic-card clicked")
            this.loadMiningHardware('graphic-card')
        }

        if (this.pressedButton.data('exists') == 'power-supply') {
            console.log("power-supply clicked")
            this.loadMiningHardware('power-supply')
        }

        if (this.pressedButton.data('exists') == 'rig-frame') {
            console.log("rig-frame clicked")
            this.loadMiningHardware('rig-frame')
        }

        if (this.pressedButton.data('exists') == 'more-parts') {
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
                `<div>${item.currency}${item.price}</div>`,
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
        let addButton = $(e.target).closest("button.addButton")

        const itemIndex = parseInt(addButton.data('item-index'))
        const item = this.resultsGlobal.generalInfo[itemIndex]

        let targetButton = $(".btn.btn-primary.btn-sm." + item.category["0"].slug)

        if (targetButton.length > 0) {
            console.log("if part")
            console.log(item)

            let targetButtonParent = targetButton[0].parentElement.parentElement

            targetButtonParent.insertAdjacentHTML('afterend', `
                <tr>
                    <td></td>
                    <td>
                        <img src="${item.img}" alt="${item.title}" height="42" width="42">
                        <a href="${item.affiliateLink}">
                            ${item.title}
                        </a>
                    </td>
                    <td class="price">${item.currency}<span class="priceComputerHardware">${item.price}</span></td>
                    <td class="buyMe">
                        <a class="btn btn-primary btn-sm" href="${item.affiliateLink}" target="_blank" role="button">
                            Buy
                        </a>
                    </td>
                    <td class="deleteMe">
                        <button type="button" class="btn btn-danger btn-sm deleteMe">x</button>
                    </td>
                </tr>
            `)

            //remove btn if they are not graphic card, other parts
            if (item.category["0"].slug !== 'graphic-card' &&
                item.category["0"].slug != 'more-parts') {
                targetButton.attr("disabled", true);
            }

            // close modal window
            $('#exampleModal').modal('hide');
            this.calculatePrice()
        }
    }

    calculatePrice() {
        console.log("calculate Price")

        $(".priceComputerHardware").each(() => {

            let price = parseFloat($(".priceComputerHardware").text().replace("$", ""))
            if (price !== 'NaN') {
                console.log("Price: " + price)
                this.overallPrice += parseFloat(price)
            }
            console.log("overall: " + this.overallPrice)
        });
        $(".total").text(this.overallPrice)
    }

    saveBuild() {
        
        console.log("save build")
        
        const newBuild = {
            'title': x,
            'content': x,
            'status': 'publish'
        }
/*
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/',
            type: 'POST',
            data: ourNewPost,
            success: (response) => {
                $(".new-note-title, .new-note-body").val('');
                $(`
                <li data-id="${response.id}">
                  <input readonly class="note-title-field" value="${response.title.raw}">
                  <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                  <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                  <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                  <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>
                `).prependTo("#my-notes").hide().slideDown();

                console.log("Congrats");
                console.log(response);
            },
            error: (response) => {
                if (response.responseText == "You have reached your note limit.") {
                    $(".note-limit-message").addClass("active");
                }
                console.log("Sorry");
                console.log(response);
            }
        });
        */
    }
}
export default RigBuilder;
