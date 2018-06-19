import $ from 'jquery';
import dt from 'datatables.net';

class HardwareOverview {

    constructor() {
        this.events()
        this.allMiningRigs()
    } // end constructor

    events() {
        //$(".btn.btn-primary.btn-lg.createRig").on("click", this.redirectToMiningRigBuilder.bind(this))
    }

    allMiningRigs() {

        console.log(`allMiningRigs clicked`)
        $.getJSON(miningRigData.root_url + '/wp-json/miningRigs/v1/allRigs', (results) => {
            console.log(results)

            //get the 3 images
            const getImages = miningHardware =>
                miningHardware
                .slice(0, 12)
                .map(
                    h => `<a href="${h.affiliateLink}" target="_blank"><img src="${h.amzImg}" alt="${h.partTitle}" height="80" width="80"></a>`
                )
                .join('\n');

            //get the shopping list
            const getShoppingList = miningHardware =>
                miningHardware
            //    .slice(0, 12)
                .map(
                    h => `<li><a href="${h.affiliateLink}" target="_blank">${h.partTitle}</a></li>`
                )
                .join('\n');    
                
            //transform data set
            let dataSet = results.generalInfo.map((item, i) => [
                i + 1,
                `${ getImages(item.miningHardware) }`,
                `<a href="${item.permalink}" target="_blank">
                    ${item.title}
                 </a>`,
                 `<ul>
                    ${getShoppingList(item.miningHardware)}
                  </ul>`,
                `$${item.totalPrice.toFixed(2)}`
            ])

            $('#allMiningRigs').DataTable({
                data: dataSet,
                destroy: true,
                iDisplayLength: 100,
                responsive: true,
                columns: [{
                        title: "#"
                    },
                    {
                        title: "Single Parts"
                    },
                    {
                        title: "Title"
                    },
                    {
                        title: "Shopping List"
                    },
                    {
                        title: "Total Price"
                    }
                ]
            });
        });
    }

    /*
    redirectToMiningRigBuilder() {
        let link = miningRigData.root_url + '/rig-builder'
        console.log("link: " + link)
        window.open(link, '_blank'); 
    }
    */
}

export default HardwareOverview;
