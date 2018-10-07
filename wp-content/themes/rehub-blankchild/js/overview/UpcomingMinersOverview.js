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
        $.getJSON(miningRigData.root_url + '/wp-json/rigHardware/v1/allUpcomingRigHardware?' + para, (results) => {
            console.log("get latest miners overview")
            console.log(results)

            //transform data set
            let dataSet = results.upcomingMiningRigHardware.map((item, i) => [
                `<img src="${ item.smallImg }" alt="${ item.title }" height="42" width="42"> 
         <a href="${item.permalink}" target="_blank">
            ${item.title}
             </a>`,
                `${ item.manufacturer }`,
                `${ item.releaseDate }`,
                `${ item.hashRatePerSecond } MH/s`,
                `${ item.watt }W`,
                // `${ rentabilityHtml(parseFloat(item.daily_netProfit)) }`,
            ])

            //remove spinner
            $(".loading").remove()

            $('#allLatestOverview' + table).DataTable({
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
                        title: "Release Date"
                    },
                    {
                        title: "Hashrate"
                    },
                    {
                        title: "Watt Estimate"
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

    $('#gpu-latest-tab').click(() => {
        console.log("#gpu pressed")
        loadHardware("cat1=graphic-card")
    })

    $('#asic-latest-tab').click(() => {
        console.log("#asic pressed")
        loadHardware("cat2=asic")
    })

});
