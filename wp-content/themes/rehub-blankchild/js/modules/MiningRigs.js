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
                `<a href="${item.permalink}">
                    ${item.title}
                 </a>`,
                `$${item.totalPrice.toFixed(2)}`,
                `Insert Upvote Plugin`
            ])

            $('#allMiningRigs').DataTable({
                data: dataSet,
                destroy: true,
                iDisplayLength: 100,
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
                        title: "Upvotes"
                    }
                ]
            });
        });
    }
}

export default MiningRigs;
