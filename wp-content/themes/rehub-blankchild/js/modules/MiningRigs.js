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

            //get the 3 images
            const getImages = miningHardware =>
                miningHardware
                .slice(0, 3)
                .map(
                    h => `<img src="${h.amzImg}" alt="${h.partTitle}" height="42" width="42">`
                )
                .join('\n');

            //transform data set
            let dataSet = results.generalInfo.map((item, i) => [
                i + 1,
                `
                ${ getImages(item.miningHardware) }
                <a href="${item.permalink}">
                    ${item.title}
                 </a>`,
                `$${item.totalPrice.toFixed(2)}`
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
                    }
                ]
            });
        });
    }
}

export default MiningRigs;
