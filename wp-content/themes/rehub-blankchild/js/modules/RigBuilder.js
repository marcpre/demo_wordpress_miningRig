import $ from 'jquery';
import dt from 'datatables.net';

class RigBuilder {

    constructor() {
        var pressedButton = null
        let resultsGlobal = []
        this.buildResultsObjGlobal = {}
        var globalPart = null // currently selected part
        this.overallPrice = 0
        this.overallWatt = 0
        this.identifier = 1
        //calculate Price
        this.calculatePrice()
        //calculate Watt
        this.calculateWatt()
        this.events()
    } // end constructor

    events() {
        // Save Build
        $(".btn.btn-primary.btn-lg.save-list").on("click", this.saveBuild.bind(this))
        //DataTable
        $('#table_id').on('click', 'button.addButton', this.addToTable.bind(this))
        //Mining Rig Table
        $("#miningRigTable").on("click", ".btn.btn-primary.btn-sm", this.ourClickDispatcher.bind(this))
        $("#miningRigTable").on("click", ".btn.btn-danger.btn-sm.deleteMe", this.deleteRow.bind(this))
        // Social Networks
        $(".sn-reddit").on("click", this.redditCode.bind(this))
        $(".sn-twitch").on("click", this.twitchCode.bind(this))
        $(".sn-vBCode").on("click", this.vBCodeCode.bind(this))
        // miningRig Description characters
        $(".form-control.description").keyup(this.countCharacters.bind(this))
        //remove content when modal is closed
        this.clearModals()
        //remove selected part when modal is closed
        this.deleteSelectedPart()
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
            }
        }
    }

    deleteRow(e) {
        console.log("delete row")
        let deleteBtn = $(e.target).closest(".deleteMe");
        //delete element from result build
        const elemId = deleteBtn.closest('tr').find("a[data-identifier]").data('identifier')
        delete this.buildResultsObjGlobal[elemId]
        //delete row
        deleteBtn.closest('tr').remove()
        $(".btn.btn-primary.btn-sm").attr("disabled", false);
        this.calculatePrice()
        this.calculateWatt()
    }

    ourClickDispatcher(e) {
        console.log("ourClickDispatcher")
        this.pressedButton = $(e.target).closest(".btn.btn-primary.btn-sm");

        if (this.pressedButton.data('exists') == 'cpu') {
            console.log("cpu clicked")
            this.loadMiningHardware('cpu')
        }

        if (this.pressedButton.data('exists') == 'motherboard') {
            console.log("motherboard clicked")
            this.loadMiningHardware('motherboard')
        }

        if (this.pressedButton.data('exists') == 'memory') {
            console.log("memory clicked")
            this.loadMiningHardware('memory')
        }

        if (this.pressedButton.data('exists') == 'graphic-card') {
            console.log("graphic-card clicked")
            this.loadMiningHardware('graphic-card')
        }
        
        if (this.pressedButton.data('exists') == 'pci-e') {
            console.log("pci-e clicked")
            this.loadMiningHardware('pci-e')
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
        //insert spinner before element
        $(".errors").before("<div class='loading'>Loading&#8230;</div>")
        //global Part
        this.globalPart = part

        console.log(`loadMiningHardware ${part} clicked`)
        $.getJSON(miningRigData.root_url + '/wp-json/rigHardware/v1/manageRigHardware?term=' + part, (results) => {
            console.log(results)

            //remove spinner
            $(".loading").remove()

            // show modal
            $('#exampleModal').modal('show');

            // mark the correct part as selected
            $(".part-node.ib.part-node-unselected." + part).addClass('part-node-selected').removeClass('part-node-unselected')

            //transform data set
            let dataSet = results.generalInfo.map((item, i) => [
                i + 1,
                `<img src="${item.img}" alt="${item.title}" height="42" width="42">
                 <a href="${item.affiliateLink}">
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
                responsive: true,
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

            let targetButtonParent = targetButton[0].parentElement.parentElement

            targetButtonParent.insertAdjacentHTML('afterend', `
                <tr>
                    <td></td>
                    <td>
                        <img src="${item.img}" alt="${item.title}" height="42" width="42">
                        <a href="${item.affiliateLink}" data-post="${item.post_id}" data-identifier="${++this.identifier}" target="_blank">
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

            // remove btn if they are not graphic card, other parts, memory
            if (item.category["0"].slug !== 'graphic-card' &&
                item.category["0"].slug != 'more-parts' &&
                item.category["0"].slug != 'memory') {
                targetButton.attr("disabled", true);
            }

            // close modal window
            $('#exampleModal').modal('hide');

            // add hardware item from global array
            this.buildResultsObjGlobal[this.identifier] = item

            // calculate price
            this.calculatePrice()
            // calculate watt
            this.calculateWatt()
        }
    }

    calculatePrice() {
        console.log("calculate Watt")
        this.overallPrice = 0.0
        for (var key in this.buildResultsObjGlobal) {
            this.overallPrice += parseFloat(this.buildResultsObjGlobal[key]['price'])
        }
        $(".total").text(this.overallPrice.toFixed(2))
    }

    calculateWatt() {
        console.log("calculate Watt")
        this.overallWatt = 0.0
        for (var key in this.buildResultsObjGlobal) {
            if (this.buildResultsObjGlobal[key]['watt'] == NaN || this.buildResultsObjGlobal[key]['watt'] == "") {
                this.overallWatt += 0
            }
            else {
                this.overallWatt += parseFloat(this.buildResultsObjGlobal[key]['watt'])
            }
        }
        $(".wattage").text(this.overallWatt.toFixed(2))
    }

    saveBuild() {
        //insert spinner before element
        $(".errors").before("<div class='loading'>Loading&#8230;</div>")
        let rigPostIds = []

        console.log("save build")

        for (var key in this.buildResultsObjGlobal) {
            rigPostIds.push(this.buildResultsObjGlobal[key]['post_id'])
        }

        let postTitle = $(".form-control.posttitle").val()
        let miningRigDescription = $(".form-control.miningRigDescription").val()

        // sharing codes
        this.clearModals()
        this.redditSharingCode()
        this.twitchSharingCode()
        this.vbcodeSharingCode()

        let redditSharing = $(".socialnetworkcontent.form-control.reddit").val()
        let twitchSharing = $(".socialnetworkcontent.form-control.twitch").val()
        let vbcodeSharing = $(".socialnetworkcontent.form-control.vBCode").val()

        const newBuild = {
            'title': postTitle,
            'content': miningRigDescription,
            'miningRigPostIds': rigPostIds,
            'reddit_sharing': redditSharing,
            'twitch_sharing': twitchSharing,
            'vbcode_sharing': vbcodeSharing,
            'status': 'publish'
        }

        console.log(newBuild)

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', miningRigData.nonce)
            },
            url: miningRigData.root_url + '/wp-json/miningRigs/v1/createRig',
            type: 'POST',
            data: newBuild,
            success: (response) => {
                //remove spinner
                $(".loading").remove()
                swal("Good job!", "success")
                console.log("Congrats");
                // console.log(response);
            },
            error: (response) => {
                //remove spinner
                $(".loading").remove()
                $(".errors").append(
                    `<div class="alert alert-danger active alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Error!</strong> ${response.responseText}
                </div>`
                );
                //scroll to the top of the page
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');
                swal("An error occured. Please try again!", "danger")
                console.log("Sorry");
                // console.log(response);
            }
        })
    }

    redditCode(e) {
        e.preventDefault()

        this.redditSharingCode()

        $('#redditModal').modal('show');
    }

    redditSharingCode() {
        let result = {}
        result = this.buildResultsObjGlobal

        let items = ""
        for (var key in result) {
            items += `**${result[key].category["0"].cat_name}** | [${result[key].title}](${result[key].affiliateLink}) | $${result[key].price}` + "\n"
        }
        items = items.replace(/\n$/, "")

        $(".socialnetworkcontent.form-control.reddit").append(
            `[Mining Rig Builder](${miningRigData.root_url})
          
Type|Item|Price
:----|:----|:----
${items.replace(/\n$/, "")}
 | | **Total**
 | Price may include shipping, rebates, promotions, and tax | $${this.overallPrice}
 | Generated by [Mining Rig Builder](${miningRigData.root_url}) |`
        );
    }

    twitchCode(e) {
        e.preventDefault()

        this.twitchSharingCode()

        $('#twitchModal').modal('show');
    }

    twitchSharingCode() {
        let result = {}
        result = this.buildResultsObjGlobal

        let items = ""
        for (var key in result) {
            items += `**${result[key].category["0"].cat_name}**|||| [${result[key].title}](${result[key].affiliateLink})` + "\n"
        }
        items = items.replace(/\n$/, "")

        $(".socialnetworkcontent.form-control").append(
            `# [Mining Rig Builder](${miningRigData.root_url})
 ||||
:----||||:----
${items.replace(/\n$/, "")}
 |||| 
 ||||
 |||| **[Generated by Mining Rig Builder](${miningRigData.root_url})**`
        );
    }

    vBCodeCode(e) {
        e.preventDefault()

        this.vbcodeSharingCode()

        $('#vBCodeModal').modal('show');
    }

    vbcodeSharingCode() {
        let result = {}
        result = this.buildResultsObjGlobal

        let items = ""
        for (var key in result) {
            items += `[b]${result[key].category["0"].cat_name}:[/b] [url=${result[key].affiliateLink}]${result[key].title}[/url]  ($${result[key].price})` + "\n"
        }
        items = items.replace(/\n$/, "")

        $(".socialnetworkcontent.form-control").append(
            `[url=${miningRigData.root_url}]Mining Rig Builder[/url]

${items.replace(/\n$/, "")}
[b]Total:[/b] $${this.overallPrice}
[i]Price may include shipping, rebates, promotions, and tax[/i]
[i]Generated by [url=${miningRigData.root_url}]Mining Rig Builder[/url][/i]`
        );
    }

    clearModals() {
        // Reddit
        $("#redditModal").on("hidden.bs.modal", () => {
            $('.socialnetworkcontent').html('')
        });
        // Twitch
        $("#twitchModal").on("hidden.bs.modal", () => {
            $('.socialnetworkcontent').html('')
        });
        // vBCode
        $("#vBCodeModal").on("hidden.bs.modal", () => {
            $('.socialnetworkcontent').html('')
        });
    }

    deleteSelectedPart() {
        $("#exampleModal").on("hidden.bs.modal", () => {
            let part = this.globalPart
            // mark the correct part as selected
            $(".part-node.ib.part-node-selected." + part).addClass('part-node-unselected').removeClass('part-node-selected')
        })
    }

    countCharacters() {
        let len = $(".form-control.miningRigDescription").val().length
        $(".typedChar").html(len + " characters");
    }
}
export default RigBuilder;
