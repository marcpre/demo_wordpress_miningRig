import $ from 'jquery';
import dt from 'datatables.net';

class MiningRigs {

    constructor() {
//        this.events()
        this.allMiningRigs()
    } // end constructor

/*    events() {

    } */

    allMiningRigs() {

        console.log(`allMiningRigs clicked`)
        $.getJSON(miningRigData.root_url + '/wp-json/miningRigs/v1/allRigs', (results) => {
            console.log(results)

            //transform data set
            let dataSet = results.generalInfo.map((item, i) => [
                i + 1,
                `<img src="${item.miningHardware.img}" alt="${item.miningHardware.title}" height="42" width="42">
                 <a href="<?php the_permalink();?>">
                     ${item.title}
                 </a>`,
                `$${item.totalPrice.toFixed(2)}`,
                `Test`,
                `Test`,
                `<a class="btn btn-primary" href="${item.miningHardware.affiliateLink}" target="_blank" role="button">
                    Buy
                </a>`,
                `Test`
            ])

            $('#allMiningRigs').DataTable({
                data: dataSet,
                destroy: true,
                columns: [{
                        title: "#"
                    },
                    {
                        title: "Title"
                    },
                    {
                        title: "Total Price"
                    },
                    {
                        title: "Price Alerts"
                    },
                    {
                        title: "Upvotes"
                    },
                                        {
                        title: "Where"
                    }
                ]
            });
        });
    }
}

export default MiningRigs;
