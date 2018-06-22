import $ from 'jquery';
import dt from 'datatables.net';

class HardwareOverview {

    constructor() {
        this.events()
        this.allProfitableMiningRigs()
    } // end constructor

    events() {
        // Add spinner
        $(".allHardwareOverview").before("<div class='loading'>Loading&#8230;</div>")
    }

    allProfitableMiningRigs() {

        $.getJSON(miningRigData.root_url + '/wp-json/rigHardware/v1/allProfitableRigHardware?term=graphic-card ', (results) => {

            //remove spinner
            $(".loading").remove()

            const rentabilityHtml = function (daily_netProfit) {
                if (daily_netProfit < 0) {
                    return `<div style="color:red;"><b>$${daily_netProfit}/day</b></div>`
                } else {
                    return `<div style="color:green;"><b>$${daily_netProfit}/day</b></div>`
                }
            }

            //transform data set
            let dataSet = results.profRigHardware.map((item, i) => [
                `<img src="${ item.smallImg }" alt="${ item.title }" height="42" width="42"> 
                 <a href="${item.permalink}" target="_blank">
                    ${item.title}
                 </a>`,
                `${ item.manufacturer }`,
                `${ item.hashRatePerSecond } MH/s`,
                `${ item.watt }W`,
                `${ rentabilityHtml(parseFloat(item.daily_netProfit)) }`,
            ])

            $('#allHardwareOverview').DataTable({
                data: dataSet,
                destroy: true,
                iDisplayLength: 100,
                responsive: true,
                "bInfo": false,
                "order": [
                    [4, 'desc']
                ],
                columns: [{
                        title: "Model"
                    },
                    {
                        title: "Manufacturer"
                    },
                    {
                        title: "Hashrate"
                    },
                    {
                        title: "Watt Estimate"
                    },
                    {
                        title: "Profitability"
                    }
                ],
                "initComplete": function (settings, json) {
                    $('#datatablediv').css('opacity', 1);
                }
            });
        });
    }
}

export default HardwareOverview;
