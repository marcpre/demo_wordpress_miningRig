jQuery(document).ready(($) => {

    function loadHardware(para) {
        table = ""
        if (para === "cat1=graphic-card") {
            table = "gpu"
        }

        if (para === "cat2=asic") {
            table = "asic"
        }

        if (para === "") {
            table = ""
        }

        // Add spinner
        $(".hardwareOverviewSpinner").before("<div class='loading'>Loading&#8230;</div>")
        $.getJSON(miningRigData.root_url + '/wp-json/rigHardware/v1/allProfitableRigHardware?' + para, (results) => {
            console.log("get hardware overview")
            console.log(results)
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
                // `${ rentabilityHtml(parseFloat(item.daily_netProfit)) }`,
                item.daily_netProfit,
            ])

            //remove spinner
            $(".loading").remove()

            $('#allHardwareOverview' + table).DataTable({
                data: dataSet,
                destroy: true,
                iDisplayLength: 25,
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
                        title: "Profitability",
                        render: function (profit) {
                            return rentabilityHtml(parseFloat(profit))
                        }
                    }
                ],
                "initComplete": function (settings, json) {
                    $('#datatablediv').css('opacity', 1);
                }
            });
        });
    }

    // initialize load
    loadHardware("cat1=graphic-card&cat2=asic")


    $('#gpu-tab').click(() => {
        console.log("#gpu pressed")
        loadHardware("cat1=graphic-card")
    })

    $('#asic-tab').click(() => {
        console.log("#asic pressed")
        loadHardware("cat2=asic")
    })

});
